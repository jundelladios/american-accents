<?php

/**
 * COUPONS MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Hasher;

use Api\Models\ProductLinesModel;

class CouponsModel extends Model {

    protected $table = "coupon_codes";

    protected $fillable = [
        'code'
    ];

    protected $hidden = ['active'];

    protected $appends = ['hid', 'haspline'];

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

    public function getHasplineAttribute() {

        return ProductLinesModel::where('coupon_code_id', $this->id)->count();

    }
}