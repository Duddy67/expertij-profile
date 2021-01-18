<?php namespace Codalia\Profile\Models;

use Model;
use Codalia\Profile\Models\Language;

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
     * @var array The licence types.
     */
    public $types = [
        'expert', 
	'ceseda'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'languages' => ['Codalia\Profile\Models\Language', 'order' => 'ordering asc', 'delete' => true],
    ];
    public $belongsTo = [
        'profile' => ['Codalia\Profile\Models\Profile'],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [
	// Deletes the attached files once a model is removed.
        'expert_attestations' => ['System\Models\File', 'order' => 'created_at desc', 'delete' => true],
        'ceseda_attestations' => ['System\Models\File', 'order' => 'created_at desc', 'delete' => true]
    ];


    public function saveLanguages($languages)
    {
        $ordering = (new Language)->ordering;

        foreach ($languages as $language) {
	    if (!empty($language['alpha_2'])) {
	        // Searches for an existing language item in the collection.
		$item = $this->languages->where('ordering', $language['ordering'])->first();

		if ($item) {
		    // Sets to null the possibly unchecked attribute.    
		    $language['interpreter'] = (isset($language['interpreter'])) ? $language['interpreter'] : null;
		    $language['translator'] = (isset($language['translator'])) ? $language['translator'] : null;

		    $item->update($language);
		}
		else {
		    $this->languages()->create($language);
		}

		// Removes the newly created or updated languages from the ordering array.
		$key = array_search($language['ordering'], $ordering);
		unset($ordering[$key]);
	    }
	}

	// Deletes the possibly unselected languages.
        foreach ($ordering as $value) {
	    $this->languages()->where('ordering', $value)->delete();
	}
    }
}
