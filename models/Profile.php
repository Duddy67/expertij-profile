<?php namespace Codalia\Profile\Models;

use Model;
use Codalia\Profile\Models\Licence;
use Illuminate\Support\Arr;
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
    protected $guarded = ['id', 'user_id', 'created_at', 'updated_at', 'honorary_member'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

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
    public $attachOne = [
        'photo' => ['System\Models\File', 'delete' => true],
    ];
    public $attachMany = [];

    const THUMBNAIL_WIDTH = 100;
    const THUMBNAIL_HEIGHT = 100;


    public static function getFromUser($user, $data)
    {
        if ($user->profile) {
	    return $user->profile;
	}

	$profile = new static;
	$profile->user = $user;
	$input = $data['profile'];

	// Checks for honorary members (only available in the membership registration context).
	if (\Session::has('registration_context') && \Session::get('registration_context') == 'membership' && isset($data['profile']['honorary_member'])) {
	    $profile->honorary_member = 1;
	    // Removes the field from the data or an exception will be thrown while updating.
	    $input = Arr::except($data['profile'], ['honorary_member']);
	}

	$profile->save();
	$user->profile = $profile;

	// Updates the newly created profile with the corresponding data.
	$profile->update($input);

	// Honorary members don't have licences.
	if ($profile->honorary_member) {
	    return $profile;
	}

	$profile->saveLicences($data['licences']);

	return $profile;
    }

    public static function getRules($licences = true) 
    {
	$rules = [
	    'email'    => 'required|between:6,255|email|unique:users',
	    'username' => 'sometimes|required|between:2,255|unique:users',
	    'password' => 'required:create|between:8,255|confirmed',
	    'password_confirmation' => 'required_with:password|between:8,255',
	    'profile.civility' => 'required',
	    'profile.first_name' => 'required|between:2,255',
	    'profile.last_name' => 'required|between:2,255',
	    'profile.street' => 'sometimes|required|between:5,255',
	    'profile.city' => 'sometimes|required|between:2,255',
	    'profile.postcode' => 'sometimes|required|between:2,255',
	    'profile.phone' => 'sometimes|required',
	    'profile.citizenship' => 'sometimes|required',
	    'profile.birth_date' => 'sometimes|required|date',
	    'profile.birth_name' => 'sometimes|required|between:2,255',
	    'profile.birth_location' => 'sometimes|required|between:2,255',
	];

	if ($licences ) {
	    $rules['licences.*.type'] = 'required';
	    $rules['licences.*.since'] = 'required';
	    $rules['licences.*.appeal_court_id'] = 'required_if:licences.*.type,expert';
	    $rules['licences.*.court_id'] = 'required_if:licences.*.type,ceseda';
	    $rules['licences.*.attestations.*.languages.*.alpha_2'] = 'required';
	}

	return $rules;
    }

    public function getCivilityOptions()
    {
	return ['mr' => 'codalia.profile::lang.profile.mr',
	        'mrs' => 'codalia.profile::lang.profile.mrs'];
    }

    public function getCitizenshipOptions()
    {
        $codes = self::getCitizenships();
	$citizenships = [];

	foreach ($codes as $code) {
	    $citizenships[$code] = 'codalia.profile::lang.citizenship.'.$code;
	}

	return $citizenships;
    }

    public function getLanguageOptions()
    {
        $codes = self::getLanguages();
	$languages = [];

	foreach ($codes as $code) {
	    $languages[$code] = 'codalia.profile::lang.language.'.$code;
	}

	return $languages;
    }

    public function getLicenceTypeOptions()
    {
	$types = Licence::getTypes();
	$licenceTypes = [];

	foreach ($types as $type) {
	    $licenceTypes[$type] = 'codalia.profile::lang.licence.'.$type;
	}

	return $licenceTypes;
    }

    public static function getAttributeNames()
    {
        return ['first_name', 'last_name', 'street', 'postcode', 'city', 'country'];
    }

    public static function getAppealCourts()
    {
	return Db::table('codalia_profile_appeal_court_list')->get()->pluck('name', 'id')->toArray();
    }

    public static function getCourts()
    {
	return Db::table('codalia_profile_court_list')->get()->pluck('name', 'id')->toArray();
    }

    public static function getLanguages()
    {
	return Db::table('codalia_profile_language_list')->get()->pluck('alpha_2')->toArray();
    }

    public static function getCitizenships()
    {
	return Db::table('codalia_profile_country_list')->get()->pluck('alpha_2')->toArray();
    }

    public static function getYears()
    {
	$year = date('Y');
	$years = [$year];

	while ($year >= 1980) {
	    $years[] = $year--;
	}

	return $years;
    }

    public static function getThumbnailSize()
    {
        return ['width' => self::THUMBNAIL_WIDTH, 'height' => self::THUMBNAIL_HEIGHT];
    }

    public static function getBlankProfileUrl()
    {
        return url('/').'/plugins/codalia/profile/assets/img/blank_profile.jpg';
    }

    public function saveLicences($licences)
    {
        $ids = $this->licences->pluck('id')->toArray();

        foreach ($licences as $key => $licence) {
	    if (!empty($licence['type'])) {
	        // Searches for an existing licence item in the collection.
		$item = $this->licences->where('id', $licence['_id'])->first();
		// Removes data which is not part of the Licence model attributes.
		$input = Arr::except($licence, ['attestations', '_id']);

	        if ($item) {
		    $item->update($input);
		}
		else {
		    $item = $this->licences()->create($input);
		}

		$item->saveAttestations($licence['attestations'], $key);

		// Removes the newly created or updated licences from the type array.
                if (($key = array_search($licence['_id'], $ids)) !== false) {
		    unset($ids[$key]);
		}
	    }
	}

	// Deletes the possibly unselected licences.
        foreach ($ids as $id) {
	    if ($licence = $this->licences()->where('id', $id)->first()) {
		$licence->delete();
	    }
	}
    }
}
