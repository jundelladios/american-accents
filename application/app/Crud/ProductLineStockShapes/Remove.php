<?php
/**
 * @package AA_Project
 * 
 * PRODUCT LINES Color INSERT
 * 
 */

namespace Api\Crud\ProductLineStockShapes;

use Api\Hasher;

use Api\Models\ProductLineStockShapeModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

class Remove {

    use ControllerTraits;

    public function remove( $request ) {

        try {

            $remove = ProductLineStockShapeModel::find( Hasher::decode( $request['id'] ) );

            if( $remove ) {
               
                $remove->delete();
                
            }

            return "Removed";

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}