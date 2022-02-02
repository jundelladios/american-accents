<?php

/**
 * PRICING DATA VALUES MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Hasher;

use Brick\Money\Money;

use Brick\Money\Context\AutoContext;

class PricingDataValueModel extends Model {

    protected $table = "pricing_data_value";

    protected $fillable = [
        'pricing_data_id', 'quantity', 'value', 'product_print_method_id', 'asterisk', 'alternative_value', 'unit_value', 'decimal_value'
    ];

    public $timestamps = false;

    protected $hidden = ['product_print_method_id', 'pricing_data_id'];

    protected $appends = ['hid', 'formatted_value', 'formatted_value_no_currency'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function getFormattedValueAttribute() {

        return aa_formatted_money( $this->value );

    }

    public function getFormattedValueNoCurrencyAttribute() {

        return aa_formatted_money( $this->value, true );

    }
}