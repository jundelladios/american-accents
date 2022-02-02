<?php
/**
 * @package AA_Project
 * 
 * PRODUCT LINES Color INSERT
 * 
 */

namespace Api\Crud\ProductLineColors;

use Api\Hasher;

use Api\Models\ProductLineColorsModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

class Remove {

    use ControllerTraits;

    public function remove( $request ) {

        try {

            $remove = ProductLineColorsModel::find( Hasher::decode( $request['id'] ) );

            if( $remove ) {
               
                $remove->delete();
                
            }

            return "Removed";

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}