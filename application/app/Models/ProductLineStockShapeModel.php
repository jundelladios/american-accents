<?php

/**
 * CLIP ARTS MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Models\CollectionStockShapeModel;

use Api\Hasher;

class ProductLineStockShapeModel extends Model {

    protected $table = "product_line_stockshape";

    protected $fillable = [
        'collection_stockshape_id', 'product_line_id', 'priority', 'title'
    ];

    public $timestamps = false;

    protected $appends = ['hid', 'collection_stockshape_id_hash', 'product_line_id_hash'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function getCollectionStockshapeIdHashAttribute() {
        return Hasher::encode($this->collection_stockshape_id);
    }

    public function getProductLineIdHashAttribute() {
        return Hasher::encode($this->product_line_id);
    }

    public function scopeActive( $query ) {

        return $query->where('active', 1);

    }

    public function scopeInactive( $query ) {

        return $query->where('active', 0);
        
    }

    public function stockshapes() {

        return $this->hasOne(CollectionStockShapeModel::class, 'id', 'collection_stockshape_id')->active();

    }

}