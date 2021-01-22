<?php namespace Codalia\Profile\Models;

use Model;
use Input;

/**
 * Licence Model
 */
class Licence extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codalia_profile_licences';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['id, created_at, updated_at'];

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
        'attestations' => ['Codalia\Profile\Models\Attestation', 'delete' => true],
    ];
    public $belongsTo = [
        'profile' => ['Codalia\Profile\Models\Profile'],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];


    public static function getTypes()
    {
	return ['expert', 'ceseda'];
    }

    public function saveAttestations($attestations)
    {
        $ids = $this->attestations->pluck('id')->toArray();

        foreach ($attestations as $attestation) {
	    if (!empty($attestation['expiry_date'])) {
	        $id = $attestation['_id'];
	        unset($attestation['_id']);

		$item = $this->attestations->where('id', $id)->first();
		// Important: Remove the languages from the licence data or an unpredictable behavior
		//            will occur during updating.  
		$languages = $attestation['languages'];
		unset($attestation['languages']);

	        if ($item) {
		    $item->update($attestation);
		}
		else {
		    $item = $this->attestations()->create($attestation);
		}

		if (Input::hasFile('file')) {
		    $item->file = Input::file('file');
		    $item->save();
		}

		$item->saveLanguages($languages);

		// Removes the newly created or updated attestations from the id array.
                if (array_search($id, $ids) !== false) {
		    unset($ids[array_search($id, $ids)]);
		}
	    }
	}

	// Deletes the possibly unselected attestations.
        foreach ($ids as $id) {
	    if ($attestation = $this->attestations()->where('id', $id)->first()) {
		//$attestation->delete();
	    }
	}
    }
}
