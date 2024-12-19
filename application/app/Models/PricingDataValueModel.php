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
        'pricing_data_id', 'quantity', 'value', 'product_print_method_id', 'asterisk', 'alternative_value', 'unit_value', 'decimal_value', 'show_currency'
    ];

    public $timestamps = false;

    protected $hidden = ['product_print_method_id', 'pricing_data_id'];

    protected $appends = ['hid', 'formatted_value', 'formatted_value_no_currency', 'labeled_value'];

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

    public function getLabeledValueAttribute() {

        $decimal = (int) $this->attributes['decimal_value'];

        $withcurrency = (int) $this->attributes['show_currency'];

        if(!$this->attributes['value']) { return null; }

        if(!$this->attributes['value'] && $this->attributes['alternative_value']) {
            return $this->attributes['alternative_value'];
        }

        return aa_formatted_money(number_format($this->attributes['value']  ?? 0, $decimal), !$withcurrency);

    }
}