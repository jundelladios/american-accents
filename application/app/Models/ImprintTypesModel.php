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

use Api\Models\ImprintTypeProductLineModel;

class ImprintTypesModel extends Model {

    protected $table = "imprint_types";

    protected $fillable = [
        'title', 'body', 'priority'
    ];

    protected $appends = ['hid', 'hasproductline'];

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

    public function getHasproductlineAttribute() {

        return ImprintTypeProductLineModel::where('imprint_type_id', $this->id)->count();

    }

}