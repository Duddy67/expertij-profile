<?php namespace Codalia\Profile\Components;

use Cms\Classes\ComponentBase;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Models\Settings as UserSettings;
use Cms\Classes\CodeBase;
use Codalia\Profile\Models\Profile;
use Codalia\Membership\Models\Member as MemberModel;
use Validator;
use Input;
use ValidationException;
use Db;
use Flash;
use Lang;


class Account extends \RainLab\User\Components\Account
{
    public function componentDetails()
    {
        return [
            'name'        => 'Account Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'redirect' => [
                'title'       => /*Redirect to*/'rainlab.user::lang.account.redirect_to',
                'description' => /*Page name to redirect to after update, sign in or registration.*/'rainlab.user::lang.account.redirect_to_desc',
                'type'        => 'dropdown',
                'default'     => ''
            ],
            'template' => [
                'title'       => /*Redirect to*/'codalia.profile::lang.account.template',
                'description' => /*Page name to redirect to after update, sign in or registration.*/'codalia.profile::lang.account.template_desc',
                'type'        => 'dropdown',
                'default'     => ''
            ],
            'sharedFields' => [
                'title'       => 'codalia.profile::lang.account.sharedFields',
                'description' => 'codalia.profile::lang.account.sharedFields_desc',
                'type'        => 'string',
                'default'     => '',
		'showExternalParam' => false
            ],
            'paramCode' => [
                'title'       => /*Activation Code Param*/'rainlab.user::lang.account.code_param',
                'description' => /*The page URL parameter used for the registration activation code*/ 'rainlab.user::lang.account.code_param_desc',
                'type'        => 'string',
                'default'     => 'code'
            ],
            'forceSecure' => [
                'title'       => /*Force secure protocol*/'rainlab.user::lang.account.force_secure',
                'description' => /*Always redirect the URL with the HTTPS schema.*/'rainlab.user::lang.account.force_secure_desc',
                'type'        => 'checkbox',
                'default'     => 0
            ],
            'requirePassword' => [
                'title'       => /*Confirm password on update*/'rainlab.user::lang.account.update_requires_password',
                'description' => /*Require the current password of the user when changing their profile.*/'rainlab.user::lang.account.update_requires_password_comment',
                'type'        => 'checkbox',
                'default'     => 0
            ],
        ];
    }

    /**
     * Executed when this component is initialized
     */
    public function prepareVars()
    {
	$this->addJs('assets/js/profile.js');
        $this->page['template'] = $this->property('template');
	$this->page['locale'] = \App::getLocale();
        // Gets the plugin name and model.
	$plugin = explode(':', $this->property('sharedFields'));
	$this->page['sharedPartial'] = strtolower($plugin[0]);
	$this->page['sharedFields'] = $this->getSharedFields($plugin);
	$this->page['appealCourts'] = $this->getAppealCourts();
	$this->page['languages'] = $this->getLanguages();
	$this->page['citizenships'] = $this->getCitizenships();

        parent::prepareVars();
    }

    public function onRegister()
    {
	$data = post();
	// Concatenates the first and last name in the User plugin's 'name' field.
	Input::merge(['name' => $data['profile']['first_name'].' '.$data['profile']['last_name']]);

//return;

        $rules = (new Profile)->rules;
	$messages = [];

	// Adds Membership extra rules.
	if (isset($data['_context']) && $data['_context'] == 'membership') {
	    $extra = (new MemberModel)->rules;
	    $rules = array_merge($rules, $extra);
	    $messages = (new MemberModel)->ruleMessages;
	}

	$validation = Validator::make(Input::all(), $rules, $messages);
	if ($validation->fails()) {
	    throw new ValidationException($validation);
	}

	return parent::onRegister();
    }

    public function getTemplateOptions()
    {
        return ['signin' => 'Sign in', 'register' => 'Register', 'both' => 'Both'];
    }

    public function onUpdate()
    {
	$data = post();
	// Concatenates the first and last name in the User plugin's 'name' field.
	Input::merge(['name' => $data['first_name'].' '.$data['last_name']]);
	// TODO: Find a way to delete 'email' variable from post (just in case).
        $rules = (new Profile)->rules;

	$validation = Validator::make($data, $rules);
	if ($validation->fails()) {
	    throw new ValidationException($validation);
	}

	// Updates first the User data.
        parent::onUpdate();

	$user = $this->user();
        $profile = Profile::where('user_id', $user->id)->first();
	$profile->update($data);

        /*
         * Redirect
         */
        if ($redirect = $this->makeRedirection()) {
            return $redirect;
        }

        //$this->prepareVars();
    }

    public function getAppealCourts()
    {
	return Db::table('codalia_profile_appeal_court_list')->get()->pluck('name', 'id')->toArray();
    }

    public function getLanguages()
    {
	return Db::table('codalia_profile_language_list')->get()->pluck('alpha_2')->toArray();
    }

    public function getCitizenships()
    {
	return Db::table('codalia_profile_country_list')->get()->pluck('alpha_3')->toArray();
    }

    private function getSharedFields($plugin)
    {
	$sharedFields = [];

	if (isset($plugin[0]) && isset($plugin[1])) {
	    $model = '\Codalia\\'.$plugin[0].'\\Models\\'.$plugin[1];

	    // Ensures the class and method exists.
	    if (method_exists($model, 'getSharedFields')) {
		$sharedFields = $model::getSharedFields();

		foreach ($sharedFields as $key => $value) {
		    // Ensures a language variable is available.
		    if (!is_array($value) && strpos($value, '::lang') !== false) {
		        // Replaces the language variable with the actual label.
			$sharedFields[$key] = Lang::get($value);
		    }
		}
	    }
	}

	return $sharedFields;
    }
}
