<?php namespace Codalia\Profile\Models;

use Model;

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
        'languages' => ['Codalia\Profile\Models\Language'],
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
        $this->languages()->delete();

        foreach ($languages as $key => $language) {
	    if (!empty($language['alpha_2'])) {
	        $language['ordering'] = $key;
file_put_contents('debog_file.txt', print_r($language, true), FILE_APPEND);
	        $this->languages()->create($language);
	    }
	}
    }
}
