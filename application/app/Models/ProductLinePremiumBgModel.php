<?php

/**
 * CLIP ARTS MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Models\CollectionPremiumBackgroundsModel;

use Api\Hasher;

class ProductLinePremiumBgModel extends Model {

    protected $table = "product_line_premiumbackground";

    protected $fillable = [
        'collection_premium_backgrounds_id', 'product_line_id', 'priority', 'title'
    ];

    public $timestamps = false;

    protected $appends = ['hid', 'collection_premium_backgrounds_id_hash', 'product_line_id_hash'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function getCollectionPremiumBackgroundsIdHashAttribute() {
        return Hasher::encode($this->collection_premium_backgrounds_id);
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

    public function premiumbg() {

        return $this->hasOne(CollectionPremiumBackgroundsModel::class, 'id', 'collection_premium_backgrounds_id')->active();

    }

}