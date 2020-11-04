<?php namespace Codalia\Profile;

use Backend;
use System\Classes\PluginBase;
use System\Classes\PluginManager;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Controllers\Users as UsersController;
use Codalia\Profile\Models\Profile as ProfileModel;
use Codalia\Profile\Helpers\ProfileHelper;
use Codalia\Membership\Models\Member as MemberModel;
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

	$isMembershipPlugin = false;
	// Checks whether the Membership plugin is installed and activated.
	if (PluginManager::instance()->exists('Codalia.Membership')) {
	    $isMembershipPlugin = true;
	}

        UserModel::extend(function($model) use($isMembershipPlugin) {
	    // Sets the relationship.
	    $model->hasOne['profile'] = ['Codalia\Profile\Models\Profile'];

	    $model->bindEvent('model.afterSave', function() use ($model) {
		// A brand new user has just been registered.
		if ($model->profile === null) {
		    // Creates a new profile model for this user.
		    $profile = ProfileModel::getFromUser($model);

		    $data = post();
		    // Updates the newly created profile with the corresponding data.
		    $profile->update($data);

		    if (isset($data['context']) && $data['context'] == 'membership') {
			Event::fire('codalia.profile.registerMember', [$profile, $data]);
		    }
		}
		else {
		    $data = post();
		    if (isset($data['context']) && $data['context'] == 'membership') {
			$profile = ProfileModel::find($model->profile->id);
			$profile->update($data);
			// Informs the Membership plugin that the corresponding member can now be updated.
			Event::fire('codalia.profile.updateMember', [$model->profile->id, $data]);
		    }
		    else {
		      // In case of user updating from the back-end, there is no need to update the profile attributes 
		      // as it's already done in the User model through the hasOne relationship.
		      //
		      // N.B: The afterSave event is also triggered during the signin/signout processes in front-end.
		    }
		}
	    });

	    // A user is about to be deleted.
	    $model->bindEvent('model.beforeDelete', function () use ($model, $isMembershipPlugin) {
		// Checks for a possible event loop.
		if ($model->profile === null) {
		    return;
		}

		if ($isMembershipPlugin) {
		    $member = MemberModel::where('profile_id', $model->profile->id)->first();

		    if ($member->checked_out) {
			throw new \Exception(Lang::get('codalia.profile::lang.action.checked_out_item'));
		    }
		}

	    });

	    // A user has been deleted.
	    $model->bindEvent('model.afterDelete', function () use ($model, $isMembershipPlugin) {
	        // Gets the corresponding profile model.
	        $profile = ProfileModel::where('user_id', $model->id)->first();

		// Checks for a possible event loop.
		if ($profile === null) {
		    return;
		}

		$profileId = $profile->id;
		// Deletes the profile model BEFORE firing a Membership event in order to 
		// prevent of being caught in a event loop.
		$profile->delete();

		if ($isMembershipPlugin) {
		    // Informs the Membership plugin about a user deletion.
		    Event::fire('codalia.profile.userDeletion', [$profileId]);
		    // N.B: An afterDelete event is going to be triggered after the member deletion (if any).
		}
	    });
	});

	if ($isMembershipPlugin) {
	    MemberModel::extend(function($model) {
		// A member has been deleted.
		$model->bindEvent('model.afterDelete', function () use ($model) {
		    // Retrieves the profile model linked to the deleted member.
		    $profile = ProfileModel::find($model->profile_id);

		    // Checks for a possible event loop.
		    if ($profile === null) {
			return;
		    }

		    // Gets the corresponding user model.
		    $userModel = UserModel::find($profile->user_id);
		    // Deletes the profile model BEFORE deleting the user in order to 
		    // prevent of being caught in a event loop.
		    $profile->delete();
		    $userModel->forceDelete();
		    // N.B: An afterDelete event is going to be triggered after the user deletion.
		});
	    });
	}

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
            // Only for the User controller and the existing user profile.
	    if (!isset($record->profile) || !$listWidget->getController() instanceof \RainLab\User\Controllers\Users) {
		return;
	    } 

	    if ($record->profile->checked_out) {
		$value .= 'safe disabled nolink';
	    }
	});

	Event::listen('backend.list.overrideColumnValue', function ($listWidget, $record, $column, &$value) {
            // Only for the User controller and the existing user profile.
	    if (!isset($record->profile) || !$listWidget->getController() instanceof \RainLab\User\Controllers\Users) {
		return;
	    } 

	    if ($record->profile->checked_out && $column->columnName == 'name') {
		return ProfileHelper::instance()->getCheckInHtml($record, BackendAuth::findUserById($record->profile->checked_out));
	    }
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
