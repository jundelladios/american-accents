<?php

/**
 * Product Color + StockShape MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Models\ProductColorsModel;

use Api\Models\ProductStockShapesModel;

use Api\Hasher;

use Api\Crud\JSON\Templates;

class ProductColorAndStockShapeModel extends Model {

    protected $table = "product_color_stockshape";

    protected $fillable = [
        'image', 'priority', 'product_print_method_id', 'templates', 'slug',
        'idea_galleries',
        'product_color_id',
        'product_stockshape_id',
        'vdsid'
    ];

    protected $hidden = ['product_color_id', 'product_stockshape_id'];

    protected $appends = ['hid', 'imagedata', 'ideagallerydata', 'counttemplates', 'templatedata', 'coloridhex', 'stockshapeidhex'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function getColoridhexAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->product_color_id);
    }

    public function getStockshapeidhexAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->product_stockshape_id);
    }

    public function getTemplatesAttribute() {

        return null;
        
    }

    public function getCounttemplatesAttribute() {

        if( !isset( $this->attributes['templates'] ) ) {

            return 0;

        }

        $ret = aa_json_array( $this->attributes['templates'] );

        return count( $ret );

    }

    public function getTemplatedataAttribute() {

        if( !isset( $this->attributes['templates'] ) ) {

            return [];

        }

        return aa_json_array( $this->attributes['templates'] );

    }

    public function getImageAttribute() {

        return null;

    }


    public function getImagedataAttribute() {

        if( !isset( $this->attributes['image'] ) ) {

            return [];

        }

        return aa_json_array( $this->attributes['image'] );

    }

    public function getIdeaGalleriesAttribute() {

        return null;

    }


    public function getIdeagallerydataAttribute() {

        if( !isset( $this->attributes['idea_galleries'] ) ) {

            return [];

        }

        return aa_json_array( $this->attributes['idea_galleries'] );

    }

    public function theshape() {

        return $this->hasOne(ProductStockShapesModel::class, 'id', 'product_stockshape_id')
        ->select(['id', 'stockname', 'code', 'product_print_method_id']);

    }

    public function thecolor() {

        return $this->hasOne(ProductColorsModel::class, 'id', 'product_color_id')
        ->select(['id', 'colorhex', 'colorname', 'iscolorimage', 'colorimageurl', 'pantone', 'product_print_method_id']);

    }

}