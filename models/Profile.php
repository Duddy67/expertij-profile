<?php namespace Codalia\Profile\Models;

use Model;
use Codalia\Profile\Models\Licence;
use Db;

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
    protected $guarded = ['id', 'user_id', 'created_at', 'updated_at'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];
    //protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
	'profile.first_name' => 'sometimes|required|between:2,255',
	'profile.last_name' => 'sometimes|required|between:2,255',
	'profile.street' => 'sometimes|required|between:5,255',
	'profile.city' => 'sometimes|required|between:2,255',
	'profile.postcode' => 'sometimes|required|between:2,255',
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
    public $hasMany = [
        'licences' => ['Codalia\Profile\Models\Licence', 'delete' => true],
    ];
    public $belongsTo = [
        'user' => ['RainLab\User\Models\User']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];


    public static function getFromUser($user, $data)
    {
        if ($user->profile) {
	    return $user->profile;
	}

	$profile = new static;
	$profile->user = $user;
	//$profile->first_name = $data['profile']['first_name'];
	//$profile->last_name = $data['profile']['last_name'];
	// Important: Creates a profile without validation.
	// NB. The validation has been performed earlier in the code.
	$profile->save();
	$user->profile = $profile;

	// Updates the newly created profile with the corresponding data.
	$profile->update($data['profile']);
	$profile->saveLicences($data['licences']);

	return $profile;
    }

    public static function getAttributeNames()
    {
        return ['first_name', 'last_name', 'street', 'postcode', 'city', 'country'];
    }

    public static function getAppealCourts()
    {
	return Db::table('codalia_profile_appeal_court_list')->get()->pluck('name', 'id')->toArray();
    }

    public static function getLanguages()
    {
	return Db::table('codalia_profile_language_list')->get()->pluck('alpha_2')->toArray();
    }

    public static function getCitizenships()
    {
	return Db::table('codalia_profile_country_list')->get()->pluck('alpha_3')->toArray();
    }

    public function saveLicences($licences)
    {
        $types = (new Licence)->types;

        foreach ($licences as $licence) {
	    if (isset($licence['type']) && in_array($licence['type'], $types)) {
	        // Searches for an existing licence item in the collection.
		$item = $this->licences->where('type', $licence['type'])->first();
		// Important: Remove the languages from the licence data or an unpredictable behavior
		//            will occur during updating.  
		$languages = $licence['languages'];
		unset($licence['languages']);

	        if ($item) {
		    $item->update($licence);
		}
		else {
		    $item = $this->licences()->create($licence);
		}

		$item->saveLanguages($languages);

		// Removes the newly created or updated licences from the type array.
                $key = array_search($licence['type'], $types);
		unset($types[$key]);
	    }
	}

	// Deletes the possibly unselected licences.
        foreach ($types as $type) {
	    if ($licence = $this->licences()->where('type', $type)->first()) {
		$licence->delete();
	    }
	}
    }
}
