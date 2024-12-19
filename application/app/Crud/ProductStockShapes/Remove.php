<?php
/**
 * @package AA_Project
 * 
 * REMOVE PRICING DATA VALUES
 * 
 */

namespace Api\Crud\ProductStockShapes;

use Api\Hasher;

use Api\Models\ProductStockShapesModel;

use Api\Constants;

class Remove {

    public function remove( $request ) {

        try {

            if( isset( $request['ids'] ) ) {
                
                ProductStockShapesModel::whereIn('id', explode(',', $request['ids']))->delete();
                return true;

            }

            if( !isset( $request['id'] )) { 

                return rest_response( Constants::BAD_REQUEST, 500 );

            }

            $pvalue = ProductStockShapesModel::find( Hasher::decode($request['id']) )->delete();

            return "Removed";

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}