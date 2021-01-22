<?php namespace Codalia\Profile\Models;

use Model;
use Codalia\Profile\Models\Language;

/**
 * Attestation Model
 */
class Attestation extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'codalia_profile_attestations';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

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
        'languages' => ['Codalia\Profile\Models\Language', 'order' => 'ordering asc', 'delete' => true],
    ];
    public $belongsTo = [
        'licence' => ['Codalia\Profile\Models\Licence'],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
	// Deletes the attached files once a model is removed.
        'file' => ['System\Models\File', 'delete' => true]
    ];
    public $attachMany = [];


    public function saveLanguages($languages)
    {
        $ids = $this->languages->pluck('id')->toArray();

        foreach ($languages as $language) {
	    if (!empty($language['alpha_2'])) {
	        $id = $language['_id'];
	        unset($language['_id']);

	        // Searches for an existing language item in the collection.
		$item = $this->languages->where('id', $id)->first();

		if ($item) {
		    // Sets to null the possibly unchecked attribute.    
		    $language['interpreter'] = (isset($language['interpreter'])) ? $language['interpreter'] : null;
		    $language['translator'] = (isset($language['translator'])) ? $language['translator'] : null;

		    $item->update($language);
		}
		else {
		    $this->languages()->create($language);
		}

		// Removes the newly created or updated languages from the id array.
                if (array_search($id, $ids) !== false) {
		    unset($ids[array_search($id, $ids)]);
		}
	    }
	}

	// Deletes the possibly unselected languages.
        foreach ($ids as $id) {
	    if ($language = $this->languages()->where('id', $id)->first()) {
		//$language->delete();
	    }
	}
    }
}
