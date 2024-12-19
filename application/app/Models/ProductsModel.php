<?php

/**
 * PRODUCTS MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;
use Api\Hasher;

use Api\Models\ProductSubcategoriesModel;
use Api\Models\ProductCategoriesModel;
use Api\Models\ProductColorsModel;
use Api\Models\ProductPrintMethodModel;
use Api\Models\SpecificationTypesModel;
use Illuminate\Support\Str;

class ProductsModel extends Model {

    protected $table = "products";

    protected $fillable = [
        'product_category_id',
        'product_subcategory_id',
        'product_name',
        'product_description',
        'case_quantity',
        'case_weight',
        'case_dim_weight',
        'case_length',
        'case_width',
        'case_height',
        'dim_top',
        'dim_height',
        'dim_base',
        'area',
        'item_width',
        'item_height',
        'pallet_quantity',
        'pallet_length',
        'pallet_width',
        'pallet_height',
        'pallet_weight',
        'class_code',
        'priority',
        'product_size',
        'product_size_details',
        'product_slug',
        'product_color_hex',
        'product_color_details',
        'product_thickness',
        'product_tickness_details',
        'product_depth',
        'area_sq_in',
        'specification_id',
        'specs_json',


        'banner_img',
        'banner_content',
        'banner_class'
    ];

    protected $hidden = [
        'product_category_id', 'product_subcategory_id'
    ];

    protected $appends = ['hid', 'hascombo', 'spechandler', 'specification'];

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

    public function category() {

        return $this->hasOne(ProductCategoriesModel::class, 'id', 'product_category_id');

    }

    public function subcategory() {

        return $this->hasOne(ProductSubcategoriesModel::class, 'id', 'product_subcategory_id');

    }

    public function product_colors() {

        return $this->hasMany(ProductColorsModel::class, 'product_id', 'id')->orderBy('priority');

    }
    
    public function getHascomboAttribute() {

        return ProductPrintMethodModel::where('product_id', $this->id)->count();

    }

    public function getSpechandlerAttribute() {

        if( $this->specification_id ) {

            return SpecificationTypesModel::where('id', $this->specification_id)->first();

        }

        return null;

    }

    public function getSpecificationAttribute() {

        return aa_json_array( $this->specs_json );
        
    }


    public function slugHandler( $reference ) {

        $slug = Str::slug( $reference );

        $original = $slug;

        $count = 1;

        while( ProductsModel::where('product_slug', $slug )->exists() ) {

            $slug = $original . '-' . $count++;

        }

        return $slug;

    }
}