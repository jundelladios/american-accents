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

use Api\Crud\JSON\Templates;

class ProductColorsModel extends Model {

    protected $table = "product_colors";

    protected $fillable = [
        'image', 'colorhex', 'colorname', 'priority', 'product_id', 'iscolorimage', 'colorimageurl', 'product_print_method_id', 'templates', 'isavailable', 'pantone', 'slug',
        'idea_galleries', 'vdsid'
    ];

    protected $hidden = ['product_id'];

    protected $appends = ['hid', 'imagedata', 'ideagallerydata', 'counttemplates', 'templatedata'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
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

}