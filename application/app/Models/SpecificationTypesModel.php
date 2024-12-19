<?php

/**
 * Specification Types Model
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Models\ProductsModel;

use Api\Models\ProductPrintMethodModel;

use Api\Hasher;

class SpecificationTypesModel extends Model {

    protected $table = "specification_types";

    protected $fillable = [
        'title', 'customfield', 'priority',
        // 'customfieldcombo', 'specs'
        'specs',
        'isspec'
    ];

    public $timestamps = false;

    protected $appends = ['hid', 'cfield', 'spc', 'usingthisspecification'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }
    
    public function getCfieldAttribute() {

        return aa_json_array( $this->customfield );
    }

    // public function getCfieldcomboAttribute() {

    //     return aa_json_array( $this->customfieldcombo );
    // }

    public function getSpcAttribute() {

        return aa_json_array( $this->specs );
    }

    public function scopeActive( $query ) {

        return $query->where('active', 1);

    }

    public function scopeInactive( $query ) {

        return $query->where('active', 0);
        
    }

    public function getUsingthisspecificationAttribute() {

        $product = ProductsModel::where('specification_id', $this->id)->count();

        $productcombo = ProductPrintMethodModel::where('specification_id', $this->id)->count();

        $productcombooutput = ProductPrintMethodModel::where('specification_output_id', $this->id)->count();

        return (int) $product + (int) $productcombo + (int) $productcombooutput;

    }

}