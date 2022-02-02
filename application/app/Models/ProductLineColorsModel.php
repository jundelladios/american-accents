<?php

/**
 * CLIP ARTS MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Models\ColorsModel;

use Api\Hasher;

class ProductLineColorsModel extends Model {

    protected $table = "product_line_colors";

    protected $fillable = [
        'color_collection_id', 'product_line_id', 'priority', 'title'
    ];

    public $timestamps = false;

    protected $appends = ['hid', 'color_collection_id_hash', 'product_line_id_hash'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function getColorCollectionIdHashAttribute() {
        return Hasher::encode($this->color_collection_id);
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

    public function colorcollections() {

        return $this->hasOne(ColorsModel::class, 'id', 'color_collection_id')->active();

    }

}