<?php namespace Codalia\Profile\Models;

use Model;
use Codalia\Profile\Models\Licence;

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
/*	'first_name' => 'required|between:2,255',
	'last_name' => 'required|between:2,255',
	'street' => 'required|between:5,255',
	'city' => 'required|between:2,255',
	'postcode' => 'required|between:2,255',*/
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
        'licences' => ['Codalia\Profile\Models\Licence'],
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
	// Important: Creates a profile without validation.
	// NB. The validation has been performed earlier in the code.
	$profile->forceSave();
	$user->profile = $profile;

	// Updates the newly created profile with the corresponding data.
	$profile->update($data['profile']);
	$profile->saveLicences($data);

	return $profile;
    }

    public static function getAttributeNames()
    {
        return ['first_name', 'last_name', 'street', 'postcode', 'city', 'country'];
    }

    public function saveLicences($data)
    {
        $types = ['expert', 'ceseda'];

        foreach ($data['licences'] as $values) {
	    if (isset($values['type']) && in_array($values['type'], $types)) {
	        // 
		$licence = $this->licences()->where('type', $values['type'])->first();

	        if ($licence) {
		    $licence->update($values);
		}
		else {
		    $licence = $this->licences()->create($values);
		}

		$licence->saveLanguages($values['languages']);
	    }
	}
    }
}
