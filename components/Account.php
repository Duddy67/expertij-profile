<?php namespace Codalia\Profile\Components;

use Cms\Classes\ComponentBase;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Models\Settings as UserSettings;
use Cms\Classes\CodeBase;
use Codalia\Profile\Models\Profile;
use Codalia\Membership\Models\Member as MemberModel;
use Codalia\Profile\Models\Licence;
use Auth;
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
        // User Plugin
        parent::prepareVars();

	$this->addJs('assets/js/profile.js');
        $this->page['template'] = $this->property('template');
	$this->page['locale'] = \App::getLocale();
        // Gets the external plugin name and model.
	$plugin = explode(':', $this->property('sharedFields'));
	$this->page['sharedPartial'] = strtolower($plugin[0]);
	$this->page['sharedFields'] = $this->getSharedFields($plugin);

	$this->page['appealCourts'] = Profile::getAppealCourts();
	$this->page['languages'] = $this->setOptionTexts('language');
	$this->page['citizenships'] = $this->setOptionTexts('citizenship');
	$this->page['licenceTypes'] = $this->setOptionTexts('licenceType');

	if ($this->page['user']) {
	    $this->page['profile'] = $this->page['user']->profile;
	}
    }

    public function onRegister()
    {
	$data = post();
	// Concatenates the first and last name in the User plugin's 'name' field.
	Input::merge(['name' => $data['profile']['first_name'].' '.$data['profile']['last_name']]);
return;
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

    public function setOptionTexts($optionName)
    {
        $function = 'get'.ucfirst($optionName).'Options';
        $options = (new Profile)->$function();

	foreach ($options as $key => $langVar) {
	    $options[$key] = Lang::get($langVar);
	}

	return $options;
    }

    public function onUpdate()
    {
	$data = post();
	// Concatenates the first and last name in the User plugin's 'name' field.
	Input::merge(['name' => $data['profile']['first_name'].' '.$data['profile']['last_name']]);
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
	$profile->update($data['profile']);
	$profile->saveLicences($data['licences']);

        /*
         * Redirect
         */
        if ($redirect = $this->makeRedirection()) {
            return $redirect;
        }
    }

    public function onAddItem()
    {
        $params = $this->getItemParameters(true);
	$indexPattern = $params['itemNewIndex'];

	if ($params['type'] == 'licence') {
	    $params['i'] = $params['itemNewIndex'];
	    $params['appealCourts'] = Profile::getAppealCourts();
	    $params['licenceTypes'] = $this->setOptionTexts('licenceType');
	}
	elseif ($params['type'] == 'attestation') {
	    $indexPattern = $params['i'].'-'.$params['itemNewIndex'];
	    $params['j'] = $params['itemNewIndex'];
	}
	elseif ($params['type'] == 'language') {
	    $indexPattern = $params['i'].'-'.$params['j'].'-'.$params['itemNewIndex'];
	    $params['k'] = $params['itemNewIndex'];
	    $params['languages'] = $this->setOptionTexts('language');
	}


	return ['#'.$params['type'].'-'.$indexPattern => $this->renderPartial('@extra/'.$params['type'], $params)];
    }

    public function onDeleteItem()
    {
        $params = $this->getItemParameters();
	$indexPattern = $params['i'];

	if ($params['id']) {
	}

	//file_put_contents('debog_file.txt', print_r(post(), true));
	if ($params['type'] == 'attestation') {
	    $indexPattern = $indexPattern.'-'.$params['j'];
	}
	elseif ($params['type'] == 'language') {
	    $indexPattern = $indexPattern.'-'.$params['j'].'-'.$params['k'];
	}

	return ['#'.$params['type'].'-'.$indexPattern => ''];
    }

    private function getItemParameters()
    {
	$type = post('_item_type');
	$id = post('_item_id');
	$i = post('_licence_index');
	$j = post('_attestation_index');
	$k = post('_language_index');
	$newIndex = post('_item_new_index');

	return ['i' => $i, 'j' => $j, 'k' => $k, 'itemNewIndex' => $newIndex, 'type' => $type, 'id' => $id];
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
