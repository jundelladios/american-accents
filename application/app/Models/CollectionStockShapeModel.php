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

class CollectionStockShapeModel extends Model {

    protected $table = "collection_stock_shape";

    protected $fillable = [
        'title', 'collection', 'priority', 'active'
    ];

    public $timestamps = false;

    protected $appends = ['hid', 'collections'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function getCollectionsAttribute() {

        return aa_json_array( $this->collection );
    }

    public function scopeActive( $query ) {

        return $query->where('active', 1);

    }

    public function scopeInactive( $query ) {

        return $query->where('active', 0);
        
    }

}