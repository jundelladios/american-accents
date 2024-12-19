<?php

/**
 * PRODUCTS SUB-CATEGORIES MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Models\ProductCategoriesModel;

use Api\Models\ProductLinesModel;

use Api\Models\ProductsModel;

use Api\Hasher;

use Illuminate\Support\Str;

class ProductSubcategoriesModel extends Model {

    protected $table = "product_subcategories";

    public $fillable = [
        'product_category_id', 'sub_name', 'sub_slug', 'sub_description', 'priority', 'seo_content', 'categorize_as', 'banner_img', 
        'catalogs', 'sub_name_alt', 'bannerlist'
    ];

    protected $hidden = ['product_category_id', 'active'];

    protected $appends = ['hid', 'hasproducts'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function category() {

        return $this->belongsTo(ProductCategoriesModel::class);

    }

    public function scopeActive( $query ) {

        return $query->where('active', 1);

    }

    public function scopeInactive( $query ) {

        return $query->where('active', 0);

    }

    public function product() {

        return $this->belongsTo(ProductsModel::class);

    }

    public function getCatalogsAttribute($value) {

        $data = json_decode($value);

        if( is_array( $data ) ) {

            return $data;

        }

        return [];
        

    }

    public function getHasproductsAttribute() {

        return ProductsModel::where('product_subcategory_id', $this->id)->count();

    }

    public function slugHandler( $reference ) {

        $slug = Str::slug( $reference );

        $original = $slug;

        $count = 1;

        while( ProductSubcategoriesModel::where('sub_slug', $slug )->exists() ) {

            $slug = $original . '-' . $count++;

        }

        return $slug;

    }


    public function getBannerlistAttribute() {

        if( !isset( $this->attributes['bannerlist'] ) ) {

            return [];

        }

        return aa_json_array( $this->attributes['bannerlist'] );
    }

}