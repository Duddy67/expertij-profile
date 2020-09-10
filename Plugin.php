<?php namespace Codalia\Profile;

use Backend;
use System\Classes\PluginBase;
use System\Classes\PluginManager;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Controllers\Users as UsersController;
use Codalia\Profile\Models\Profile as ProfileModel;
use Codalia\Profile\Helpers\ProfileHelper;
use BackendAuth;
use Event;
use Lang;
use Flash;

/**
 * Profile Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Profile',
            'description' => 'No description provided yet...',
            'author'      => 'Codalia',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
	// Ensures first that the RainLab User plugin is installed and activated.
	if (!PluginManager::instance()->exists('RainLab.User')) {
	    return;
	}

        UserModel::extend(function($model) {
	    // Relation
	    $model->hasOne['profile'] = ['Codalia\Profile\Models\Profile'];

	    $model->bindEvent('model.afterSave', function() use ($model) {
	        $data = post();
		$names = ProfileModel::getAttributeNames();

		// A brand new user has just been registered.
		if ($model->profile === null) {
		    // Creates a new profile model for this user.
		    $profile = ProfileModel::getFromUser($model);

		    if (isset($data['context']) && $data['context'] == 'membership') {
			Event::fire('codalia.profile.registerMember', [$model, $profile, $data]);
		    }
		}

		if (isset($data[$names[0]])) {
		    $update = [];

		    foreach ($names as $name) {
			$update[$name] = $data[$name];
		    }

		    $model->profile()->update($update);
		}
	    });

	    $model->bindEvent('model.beforeDelete', function () use ($model) {
		if ($model->profile->checked_out) {
		    throw new \Exception(Lang::get('codalia.profile::lang.action.checked_out_item'));
		}
	    });
	});

	UsersController::extendFormFields(function($form, $model, $context) {
	    // Ensures that the model exists and it's a User model. 
	    if (!$model->exists || !$model instanceOf UserModel) {
	        return;
	    }

	    $form->addTabFields([
	        'profile[first_name]' => [
		    'label' => 'First Name',
		    'tab' => 'Profile'
		],
	        'profile[last_name]' => [
		    'label' => 'Last Name',
		    'tab' => 'Profile'
		],
	        'profile[street]' => [
		    'label' => 'Street',
		    'tab' => 'Profile'
		],
	        'profile[city]' => [
		    'label' => 'City',
		    'tab' => 'Profile'
		],
	        'profile[postcode]' => [
		    'label' => 'Postcode',
		    'tab' => 'Profile'
		],
	        'profile[country]' => [
		    'label' => 'Country',
		    'tab' => 'Profile'
		],
	    ]);
	});

	UsersController::extend( function($controller) {
	    $controller->addViewPath('$/codalia/profile/partials');

		    //file_put_contents('debog_file_test.txt', print_r('', true)); 
            $controller->addDynamicMethod('index_onCheckIn', function() use ($controller) {
		 // Ensures one or more items are selected.
		 if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
		     $count = 0;

		     foreach ($checkedIds as $recordId) {
			 ProfileHelper::instance()->checkIn((new ProfileModel)->getTable(), null, $recordId);
			 $count++;
		     }

		     Flash::success(Lang::get('codalia.profile::lang.action.check_in_success', ['count' => $count]));
		 }

                 return $controller->listRefresh();
	    });

	});

	// Extends some backend methods.

	Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
            // Only for the User controller
	    if (!$controller instanceof \RainLab\User\Controllers\Users) {
		return;
	    } 

	    if ($action == 'index' || $action == 'preview') {
		$controller->addCss(url('plugins/codalia/profile/assets/css/disable-toolbar.css'));
		// Unlocks the checked out items of this user (if any).
		ProfileHelper::instance()->checkIn((new ProfileModel)->getTable(), BackendAuth::getUser());
	    }
	    elseif ($action == 'update') {
	        $user = UserModel::find($params[0]);
		$backendUser = BackendAuth::getUser();

		// Checks for check out matching.
		if ($user->profile->checked_out && $backendUser->id != $user->profile->checked_out) {
		    Flash::error(Lang::get('codalia.profile::lang.action.check_out_do_not_match'));
		    return redirect('backend/rainlab/user/users');
		}

		// Locks the item for this backend user.
		ProfileHelper::instance()->checkOut((new ProfileModel)->getTable(), $backendUser, $params[0]);

		$controller->addCss(url('plugins/codalia/profile/assets/css/disable-toolbar.css'));
		$controller->addJs(url('plugins/codalia/profile/assets/js/disable-toolbar.js'));
	    }
	});

	Event::listen('backend.list.injectRowClass', function ($listWidget, $record, &$value) {
            // Only for the User controller
	    if (!$listWidget->getController() instanceof \RainLab\User\Controllers\Users) {
		return;
	    } 

	    if ($record->profile->checked_out) {
		$value .= 'safe disabled nolink';
	    }
	});

	Event::listen('backend.list.overrideColumnValue', function ($listWidget, $record, $column, &$value) {
            // Only for the User controller
	    if (!$listWidget->getController() instanceof \RainLab\User\Controllers\Users) {
		return;
	    } 

	       //file_put_contents('debog_file_test.txt', print_r($column->columnName, true)); 
	    if ($record->profile->checked_out && $column->columnName == 'name') {
		return ProfileHelper::instance()->getCheckInHtml($record, BackendAuth::findUserById($record->profile->checked_out));
	    }
	});

	// Extend all backend list usage
        Event::listen('backend.list.extendColumns', function($widget) {
            // Only for the User controller
            if (!$widget->getController() instanceof \RainLab\User\Controllers\Users) {
                return;
            }

	    // Add an extra birthday column
	});

	// Events fired by the User plugin.
	
	Event::listen('rainlab.user.beforeRegister', function(&$data) {
	    // 
	});

	Event::listen('rainlab.user.register', function($user, $data) {

	});

	Event::listen('rainlab.user.beforeAuthenticate', function($model, $credentials) {
	    //
	});

	Event::listen('rainlab.user.logout', function($user) {
	    //
	});
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        //return []; // Remove this line to activate

        return [
            'Codalia\Profile\Components\Account' => 'account',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'codalia.profile.some_permission' => [
                'tab' => 'Profile',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'profile' => [
                'label'       => 'Profile',
                'url'         => Backend::url('codalia/profile/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['codalia.profile.*'],
                'order'       => 500,
            ],
        ];
    }
}
