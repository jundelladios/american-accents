<?php

/**
 * Collection Stock Shape Model
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Hasher;

class CollectionPremiumBackgroundsModel extends Model {

    protected $table = "collection_premium_backgrounds";

    protected $fillable = [
        'title', 'collection', 'priority', 'active'
    ];

    public $timestamps = false;

    protected $appends = ['hid', 'collections'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function scopeActive( $query ) {

        return $query->where('active', 1);

    }

    public function scopeInactive( $query ) {

        return $query->where('active', 0);
        
    }

    public function getCollectionsAttribute() {

        return aa_json_array( $this->collection );

    }

}