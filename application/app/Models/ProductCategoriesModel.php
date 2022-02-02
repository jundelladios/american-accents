<?php

/**
 * PRODUCT CATEGORIES MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Models\ProductSubcategoriesModel;

use Api\Models\ProductsModel;

use Api\Hasher;

use Illuminate\Support\Str;

class ProductCategoriesModel extends Model {

    protected $table = "product_categories";

    protected $fillable = [
        'cat_name', 'cat_slug', 'notes', 'priority', 'category_banner', 'category_banner_content', 'seo_content', 'template_section'
    ];

    protected $hidden = ['active'];

    protected $appends = ['hid', 'hassubcategories'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function subcategories() {

        return $this->hasMany(ProductSubcategoriesModel::class, 'product_category_id');

    }

    public function scopeActive( $query ) {

        return $query->where('active', 1);

    }

    public function scopeInactive( $query ) {

        return $query->where('active', 0);

    }

    public function product() {

        return $this->hasMany(ProductsModel::class, 'product_category_id');

    }


    public function getHassubcategoriesAttribute() {

        return ProductSubcategoriesModel::where('product_category_id', $this->id)->count();

    }


    public function slugHandler( $reference ) {

        $slug = Str::slug( $reference );

        $original = $slug;

        $count = 1;

        while( ProductCategoriesModel::where('cat_slug', $slug )->exists() ) {

            $slug = $original . '-' . $count++;

        }

        return $slug;

    }
}