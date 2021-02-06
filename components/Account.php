<?php namespace Codalia\Profile\Components;

use Cms\Classes\ComponentBase;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Models\Settings as UserSettings;
use Cms\Classes\CodeBase;
use Codalia\Profile\Models\Profile;
use Codalia\Membership\Models\Member as MemberModel;
use Codalia\Membership\Models\Settings;
use System\Models\File;
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
	$thumbSize = explode(':', Settings::get('photo_thumbnail', '100:100'));
	$this->page['thumbSize'] = ['width' => $thumbSize[0], 'height' => $thumbSize[1]];

	$this->page['appealCourts'] = Profile::getAppealCourts();
	$this->page['courts'] = Profile::getCourts();
	$this->page['years'] = Profile::getYears();
	$this->page['languages'] = $this->setOptionTexts('language');
	$this->page['citizenships'] = $this->setOptionTexts('citizenship');
	$this->page['licenceTypes'] = $this->setOptionTexts('licenceType');

	if ($this->page['user']) {
	    $this->page['profile'] = $this->page['user']->profile;

	    if (\Session::has('registration_context')) {
	        \Session::forget('registration_context');
	    }
	}
	// Registration
	else {
	    // Gets the external plugin name and model.
	    $plugin = explode(':', $this->property('sharedFields'));
	    $this->page['sharedPartial'] = strtolower($plugin[0]);
	    $this->page['sharedFields'] = $this->getSharedFields($plugin);
            // Sets the registration context according to the external plugin. 
	    \Session::put('registration_context', $plugin[0]);
	}
    }

    public function onRegister()
    {
	$data = post();
	// Concatenates the first and last name in the User plugin's 'name' field.
	Input::merge(['name' => $data['profile']['first_name'].' '.$data['profile']['last_name']]);

        $rules = (new Profile)->rules;
	$messages = [];

	// Adds Membership extra rules.
	if (\Session::has('registration_context') && \Session::get('registration_context') == 'membership') {
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

	if ($data['task'] == 'replace-photo') {
            return $this->replacePhoto();
	}

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

	// Saves the licences of the regular members.
	if (!$profile->honorary_member) {
	    $profile->saveLicences($data['licences']);
	}

        /*
         * Redirect
         */
        if ($redirect = $this->makeRedirection()) {
            return $redirect;
        }
    }

    public function replacePhoto()
    {
	$file = null;

	if (Input::hasFile('photo')) {
	    $file = (new File())->fromPost(Input::file('photo'));

	    $user = $this->user();
	    $profile = Profile::where('user_id', $user->id)->first();
	    $profile->photo()->add($file);
	    $profile->forceSave();
	}
	else {
	    return;
	}

        Flash::success(Lang::get('codalia.membership::lang.action.file_replace_success'));

	return [
	  '#new-photo' => '<img src="'.$file->getThumb(100, 100).'" />',
	  // Replaces the old file input by a new one to clear the previous file selection. 
	  '#photo-upload-field' => '<input type="file" name="photo" class="form-control">'
	];
    }

    /*
     * Adds a given item dynamically.
     * Index pattern description: identifier-i[, -j[, -k]]
     * ie: i = licence index, j = attestation index, k = language index.
     */
    public function onAddItem()
    {
        $params = $this->getItemParameters();
        // Sets the licence index pattern by default.
	$indexPattern = $params['newIndex'];

	if ($params['type'] == 'licence') {
	    // Updates the licence index.
	    $params['i'] = $params['newIndex'];
	    // Sets the variables needed in the licence partial.
	    $params['appealCourts'] = Profile::getAppealCourts();
	    $params['courts'] = Profile::getCourts();
	    $params['years'] = Profile::getYears();
	    $params['licenceTypes'] = $this->setOptionTexts('licenceType');
	    $params['languages'] = $this->setOptionTexts('language');
	}
	elseif ($params['type'] == 'attestation') {
	    $indexPattern = $params['i'].'-'.$params['newIndex'];
	    $params['j'] = $params['newIndex'];
	    $params['languages'] = $this->setOptionTexts('language');
	}
	elseif ($params['type'] == 'language') {
	    $indexPattern = $params['i'].'-'.$params['j'].'-'.$params['newIndex'];
	    $params['k'] = $params['newIndex'];
	    $params['languages'] = $this->setOptionTexts('language');
	}

        // Renders the new item in the div container previously created in JS.
	return ['#'.$params['type'].'-'.$indexPattern => $this->renderPartial('@licences/'.$params['context'].'-'.$params['type'], $params)];
    }

    /*
     * Deletes a given item dynamically.
     */
    public function onDeleteItem()
    {
        $params = $this->getItemParameters();
        // Sets the licence index pattern by default.
	$indexPattern = $params['i'];

	// Deletes the item from the database.
	if ($params['id']) {
	    $model = '\Codalia\Profile\Models\\'.ucfirst($params['type']);
	    $item = $model::where('id', $params['id'])->first();
	    $item->delete();

	    Flash::success(Lang::get('codalia.profile::lang.action.delete_success'));
	}

        // Sets the index pattern accordingly.
	if ($params['type'] == 'attestation') {
	    $indexPattern = $indexPattern.'-'.$params['j'];
	}
	elseif ($params['type'] == 'language') {
	    $indexPattern = $indexPattern.'-'.$params['j'].'-'.$params['k'];
	}

	// Removes the given item from the div container.
	return ['#'.$params['type'].'-'.$indexPattern => ''];
    }

    /*
     * Retrieves and gathers the variables sent through the POST array
     * by the data attributes API.
     */
    private function getItemParameters()
    {
	$type = post('_item_type');
	$id = post('_item_id');
	$newIndex = post('_item_new_index');
	$context = post('_item_context');
	$i = post('_licence_index');
	$j = post('_attestation_index');
	$k = post('_language_index');

	return ['i' => $i, 'j' => $j, 'k' => $k, 'newIndex' => $newIndex, 'type' => $type, 'context' => $context, 'id' => $id];
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
