<?php

/**
 * Charges MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Hasher;

use Api\Models\PricingDataModel;

class ChargesModel extends Model {

    protected $table = "charge_types";

    protected $fillable = [
        'charge_name', 'icon'
    ];

    protected $appends = ['hid', 'haspricingdata'];

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

    public function getHaspricingdataAttribute() {

        return PricingDataModel::where('charge_type_id', $this->id)->count();

    }


}