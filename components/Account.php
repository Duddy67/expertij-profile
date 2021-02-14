<?php namespace Codalia\Profile\Components;

use Cms\Classes\ComponentBase;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Models\Settings as UserSettings;
use Cms\Classes\CodeBase;
use Codalia\Profile\Models\Profile;
use Codalia\Membership\Models\Member as MemberModel;
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
            'hostedFields' => [
                'title'       => 'codalia.profile::lang.account.hostedFields',
                'description' => 'codalia.profile::lang.account.hostedFields_desc',
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
	$this->page['thumbSize'] = Profile::getThumbnailSize();
	$this->page['blankProfile'] = Profile::getBlankProfileUrl();

	$this->page['appealCourts'] = Profile::getAppealCourts();
	$this->page['courts'] = Profile::getCourts();
	$this->page['years'] = Profile::getYears();
	$this->page['languages'] = $this->setOptionTexts('language');
	$this->page['citizenships'] = $this->setOptionTexts('citizenship');
	$this->page['licenceTypes'] = $this->setOptionTexts('licenceType');

	if ($this->page['user']) {
	    $this->page['profile'] = $this->page['user']->profile;
	}

	$this->setExternalPluginRegistration();
    }

    protected function setExternalPluginRegistration()
    {
        // It's not a registration page.
	if ($this->property('template') != 'register') {

	    if (\Session::has('registration_context')) {
	        \Session::forget('registration_context');
	    }

	    return;
	}

	// Gets the external plugin name and model.
	$plugin = explode(':', $this->property('hostedFields'));

	if (empty($plugin[0])) {
	    return;
	}

	$this->page['hostedPartial'] = strtolower($plugin[0]);
	$this->page['hostedFields'] = $this->getHostedFields($plugin);

	if (!\Session::has('registration_context')) {
	    // Sets the registration context according to the external plugin. 
	    \Session::put('registration_context', $plugin[0]);
	}
    }

    public function onRegister()
    {
	$data = post();
	// Concatenates the first and last name in the User plugin's 'name' field.
	Input::merge(['name' => $data['profile']['first_name'].' '.$data['profile']['last_name']]);

	$licences = (isset($data['profile']['honorary_member'])) ? false : true;
        $rules = Profile::getRules($licences);

	if ($licences) {
	    $rules = $this->setFileValidationRules($rules, $data);
	    $rules = $this->setLanguageValidationRules($rules, $data);
	}

	$attributes = $this->getValidationRuleAttributes($rules);
	$messages = ['licences.*.attestations.*.languages.*.interpreter.required_unless' => Lang::get('codalia.profile::lang.messages.skill_checkboxes')];

	// Adds the validation rules of the hosted fields.
	if (\Session::has('registration_context') && \Session::get('registration_context') == 'membership') {
	    if (\Session::get('registration_context') == 'membership' && !isset($data['profile']['honorary_member'])) {
		$extra = MemberModel::getRules();
		$rules = array_merge($rules, $extra);
		$extra = $this->getExternalValidationRuleAttributes();
		$attributes = array_merge($attributes, $extra);
		$extra = $this->getExternalValidationRuleMessages();
		$messages = array_merge($messages, $extra);
	    }
	}

	$validation = Validator::make(Input::all(), $rules, $messages, $attributes);
	if ($validation->fails()) {
	    throw new ValidationException($validation);
	}

	return parent::onRegister();
    }

    public function getTemplateOptions()
    {
        return ['signin' => 'Sign in', 'register' => 'Register'];
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

	$licences = (isset($data['licences'])) ? true : false;
        $rules = Profile::getRules($licences);

	if ($licences) {
	    $rules = $this->setLanguageValidationRules($rules, $data);
	}

	$attributes = $this->getValidationRuleAttributes($rules);
	$messages = ['licences.*.attestations.*.languages.*.interpreter.required_unless' => Lang::get('codalia.profile::lang.messages.skill_checkboxes')];

	$validation = Validator::make($data, $rules, $messages, $attributes);
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
	    $profile->save();
	}
	else {
	    return;
	}

        Flash::success(Lang::get('codalia.membership::lang.action.file_replace_success'));

	$size = Profile::getThumbnailSize();

	return [
	  '#new-photo' => '<img src="'.$file->getThumb($size['width'], $size['height']).'" />',
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

    private function setFileValidationRules($rules, $data)
    {
        for($i = 0; $i < count($data['licences']); $i++) {
	    for($j = 0; $j < count($data['licences'][$i]['attestations']); $j++) {
	        $rules['licences__file_'.$i.'_'.$j] = 'required';
	    }
	}

	return $rules;
    }

    /*
     * Sets the validation rules of the skill checkboxes (interpreter / translator).
     */
    private function setLanguageValidationRules($rules, $data)
    {
        for($i = 0; $i < count($data['licences']); $i++) {
	    // Skill checkboxes are only available for experts.
	    if ($data['licences'][$i]['type'] == 'expert') {
		for($j = 0; $j < count($data['licences'][$i]['attestations']); $j++) {
		    for($k = 0; $k < count($data['licences'][$i]['attestations'][$j]['languages']); $k++) {
		        $path = 'licences.'.$i.'.attestations.'.$j.'.languages.'.$k;
			$rules[$path.'.interpreter'] = 'required_unless:'.$path.'.translator,1';
			$rules[$path.'.translator'] = 'required_unless:'.$path.'.interpreter,1';
		    }
		}
	    }
	}

	return $rules;
    }

    private function getValidationRuleAttributes($rules)
    {
        $attributes = [];

        foreach ($rules as $attribute => $rule) {
	    if (strpos($attribute, 'licences__file_') !== false) {
		$attributes[$attribute] = Lang::get('codalia.profile::lang.licences.attestations.file');
	    }
	    elseif (strpos($attribute, '*.') !== false) {
	        $lang = str_replace('*.', '', $attribute);
		$attributes[$attribute] = Lang::get('codalia.profile::lang.'.$lang);
	    }
	    elseif (strpos($attribute, '.interpreter') !== false || strpos($attribute, '.translator') !== false) {
	        preg_match('#\.([a-z]*)$#', $attribute, $matches);
		$attributes[$attribute] = Lang::get('codalia.profile::lang.licences.attestations.languages.'.$matches[1]);
	    }
	    elseif (strpos($attribute, 'profile.') !== false) {
		$attributes[$attribute] = Lang::get('codalia.profile::lang.'.$attribute);
	    }
	    else {
		$attributes[$attribute] = Lang::get('codalia.profile::lang.profile.'.$attribute);
	    }
	}

	return $attributes;
    }

    private function getExternalValidationRuleAttributes()
    {
        $attributes = [];
	$plugin = explode(':', $this->property('hostedFields'));

	if (isset($plugin[0]) && isset($plugin[1])) {
	    $model = '\Codalia\\'.$plugin[0].'\\Models\\'.$plugin[1];
	    $attributes = $model::getValidationRuleAttributes();

	    foreach ($attributes as $key => $langVar) {
	        $attributes[$key] = Lang::get($langVar);
	    }
	}

	return $attributes;
    }

    private function getExternalValidationRuleMessages()
    {
        $messages = [];
	$plugin = explode(':', $this->property('hostedFields'));

	if (isset($plugin[0]) && isset($plugin[1])) {
	    $model = '\Codalia\\'.$plugin[0].'\\Models\\'.$plugin[1];
	    $messages = $model::getValidationRuleMessages();

	    foreach ($messages as $key => $langVar) {
	        $messages[$key] = Lang::get($langVar);
	    }
	}

	return $messages;
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

    private function getHostedFields($plugin)
    {
	$hostedFields = [];

	if (isset($plugin[0]) && isset($plugin[1])) {
	    $model = '\Codalia\\'.$plugin[0].'\\Models\\'.$plugin[1];

	    // Ensures the class and method exists.
	    if (method_exists($model, 'getHostedFields')) {
		$hostedFields = $model::getHostedFields();

		foreach ($hostedFields as $key => $value) {
		    // Ensures a language variable is available.
		    if (!is_array($value) && strpos($value, '::lang') !== false) {
		        // Replaces the language variable with the actual label.
			$hostedFields[$key] = Lang::get($value);
		    }
		}
	    }
	}

	return $hostedFields;
    }
}
