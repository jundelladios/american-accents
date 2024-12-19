<?php

/**
 * PRICING DATA MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Hasher;

use Api\Models\PricingDataValueModel;

use Api\Models\ChargesModel;

use Api\Models\ProductLinesModel;

use Api\Collection;

class PricingDataModel extends Model {

    protected $table = "pricing_data";

    protected $fillable = [
        'product_line_id', 'charge_type_id', 'priority', 
        'is_additional_spot',
        'per_color',
        'per_piece',
        'per_side',
        'per_thousand',
        'note_value',
        'auto_format',
        'spot_color_value',
        'per_color_value',
        'per_piece_value',
        'per_side_value',
        'per_thousand_value'
    ];

    public $timestamps = false;

    protected $hidden = ['product_line_id', 'charge_type_id'];

    protected $appends = ['hid', 'pvalues'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function getPvaluesAttribute() {
        
        $values = PricingDataValueModel::where( 'pricing_data_id', $this->id )->orderBy('quantity');

        return Collection::toJson($values->get());

    }

    public function chargetypes() {

        return $this->hasOne(ChargesModel::class, 'id', 'charge_type_id');

    }

    public function productline() {

        return $this->hasOne(ProductLinesModel::class, 'id', 'product_line_id');
    
    }
}