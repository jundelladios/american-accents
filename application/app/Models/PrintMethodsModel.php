<?php

/**
 * PRINTING METHODS MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Hasher;

use Api\Models\ProductSubcategoriesModel;

use Api\Models\ProductLinesModel;

use Illuminate\Support\Str;

class PrintMethodsModel extends Model {

    protected $table = "print_methods";

    protected $fillable = [
        'method_name', 'method_slug', 'method_prefix', 'method_desc', 'method_hex', 'priority', 'method_name2', 'keyfeatures', 'is_unprinted'
    ];

    protected $hidden = ['active'];

    protected $appends = ['hid', 'keyfeaturesdata', 'hasproductline'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function getKeyfeaturesdataAttribute() {

        if( $this->keyfeatures ) {

            return json_decode( $this->keyfeatures );

        }

    }

    public function scopeActive( $query ) {

        return $query->where('active', 1);

    }

    public function scopeInactive( $query ) {

        return $query->where('active', 0);
        
    }

    public function subcategorymethod() {

        return $this->belongsTo(ProductSubcategoriesModel::class);

    }

    public function getHasproductlineAttribute() {

        return ProductLinesModel::where('print_method_id', $this->id)->count();

    }

    public function slugHandler( $reference ) {

        $slug = Str::slug( $reference );

        $original = $slug;

        $count = 1;

        while( PrintMethodsModel::where('method_slug', $slug )->exists() ) {

            $slug = $original . '-' . $count++;

        }

        return $slug;

    }
}