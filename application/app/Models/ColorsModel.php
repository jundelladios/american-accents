<?php

/**
 * CLIP ARTS MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Hasher;

class ColorsModel extends Model {

    protected $table = "collection_colors";

    protected $fillable = [
        'title', 'colorjson', 'priority'
    ];

    public $timestamps = false;

    protected $appends = ['hid', 'collections'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function getCollectionsAttribute() {

        return aa_json_array( $this->colorjson );

    }

    public function scopeActive( $query ) {

        return $query->where('active', 1);

    }

    public function scopeInactive( $query ) {

        return $query->where('active', 0);
        
    }

}