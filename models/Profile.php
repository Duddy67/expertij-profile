<?php namespace Codalia\Profile\Models;

use Model;

/**
 * Profile Model
 */
class Profile extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codalia_profile_profiles';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['first_name', 'last_name', 'street', 'city', 'postcode', 'country'];
    //protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
	'first_name' => 'required|between:2,255',
	'last_name' => 'required|between:2,255',
	'street' => 'required|between:5,255',
	'city' => 'required|between:2,255',
	'postcode' => 'required|between:2,255',
    ];
    //public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'user' => ['RainLab\User\Models\User']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];


    public static function getFromUser($user)
    {
        if ($user->profile) {
	    return $user->profile;
	}

	$profile = new static;
	$profile->user = $user;
	// Important: Creates a profile without validation.
	// NB. The validation has been performed earlier in the code.
	$profile->forceSave();
	$user->profile = $profile;

	return $profile;
    }

    public static function getAttributeNames()
    {
        return ['first_name', 'last_name', 'street', 'postcode', 'city', 'country'];
    }

    public static function getSharedFields($pluginName, $modelName)
    {
	$fields = [];

	if(file_exists('plugins/codalia/'.strtolower($pluginName).'/models/'.strtolower($modelName).'/share/fields.php')) {
	    $fields = include 'plugins/codalia/'.strtolower($pluginName).'/models/'.strtolower($modelName).'/share/fields.php';
	}

	return $fields;
    }
}
