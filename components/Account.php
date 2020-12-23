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
            'extraRegistrationFields' => [
                'title'       => 'codalia.profile::lang.account.extraRegistrationFields',
                'description' => 'codalia.profile::lang.account.extraRegistrationFields_desc',
                'type'        => 'string',
                'default'     => '',
		'showExternalParam' => false
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
        $this->page['template'] = $this->property('template');
        $pluginName = $this->page['extraRegistrationFields'] = $this->property('extraRegistrationFields');
	$this->page['sharedFields'] = $this->getSharedFields();

        parent::prepareVars();
    }

    public function onRegister()
    {
	$data = post();
	// Concatenates the first and last name in the User plugin's 'name' field.
	Input::merge(['name' => $data['first_name'].' '.$data['last_name']]);

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

        $this->prepareVars();
    }

    private function getSharedFields()
    {
	$plugin = explode(':', $this->property('sharedFields'));
	$sharedFields = [];

	if (isset($plugin[0]) && isset($plugin[1])) {
	    // Gets the field variable names.
	    $fields = Profile::getSharedFields($plugin[0], $plugin[1]);

	    // Now adds the associated labels.
	    foreach ($fields as $key => $value) {
	        // Handles the possible option arrays.
	        if (is_array($value)) {
		    foreach ($value as $val) {
			$sharedFields[$key][$val] = Lang::get('codalia.'.$plugin[0].'::lang.profile.'.$key.'.'.$val);
		    }
		}
		else {
		    $sharedFields[$value] = Lang::get('codalia.'.$plugin[0].'::lang.profile.'.$value);
		}
	    }
	}

	return $sharedFields;
    }
}
