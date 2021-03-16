<?php namespace Codalia\Profile\Models;

use Model;
use Input;
use Illuminate\Support\Arr;

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
    protected $guarded = ['id', 'profile_id', 'created_at', 'updated_at'];

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
    public $hasManyThrough = [
        'languages' => [
            'Codalia\Profile\Models\Language',
            'through' => 'Codalia\Profile\Models\Attestation'
        ],
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

    public function saveAttestations($attestations, $licenceKey)
    {
        $ids = $this->attestations->pluck('id')->toArray();

        foreach ($attestations as $key => $attestation) {
	    // Searches for an existing attestation item in the collection.
	    $item = $this->attestations->where('id', (int)$attestation['_id'])->first();
	    // Removes data which is not part of the Attestation model attributes.
	    $input = Arr::except($attestation, ['languages', '_id']);

	    // expiry_date is the only and optional field to set. 
	    $input = (!empty($input['expiry_date'])) ? $input : [];

	    if ($item) {
		$item->update($input);
	    }
	    else {
		$item = $this->attestations()->create($input);
	    }

	    if (Input::hasFile('licences__file_'.$licenceKey.'_'.$key)) {
		$item->file = Input::file('licences__file_'.$licenceKey.'_'.$key);
		$item->save();
	    }

	    $item->saveLanguages($attestation['languages']);

	    // Removes the newly created or updated attestations from the id array.
	    if (($key = array_search($attestation['_id'], $ids)) !== false) {
		unset($ids[$key]);
	    }
	}

	// Deletes the possibly unselected attestations.
        foreach ($ids as $id) {
	    if ($attestation = $this->attestations()->where('id', $id)->first()) {
		$attestation->delete();
	    }
	}
    }
}
