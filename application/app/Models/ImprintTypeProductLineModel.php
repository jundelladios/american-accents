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

use Api\Models\ImprintTypesModel;

class ImprintTypeProductLineModel extends Model {

    protected $table = "imprint_type_product_line";

    protected $fillable = [
        'imprint_type_id', 'productline_id', 'min_prod_days', 'imprint_charge', 'image', 'priority'
    ];

    protected $hidden = ['imprint_type_id', 'productline_id'];

    protected $appends = ['hid', 'formatted_value', 'sortref'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function imprinttype() {

        return $this->hasOne(ImprintTypesModel::class, 'id', 'imprint_type_id');

    }

    public function getFormattedValueAttribute() {

        return aa_formatted_money( $this->imprint_charge, false, true );

    }

    public function getSortrefAttribute() {
        $charge = $this->imprint_charge;
        if( !$charge ) { $charge = "empty-charge"; }
        $charge .= "|";
        $prod = $this->min_prod_days;
        if( !$prod ) { $prod = "empty-prod"; }
        return $charge.$prod;
    }

}