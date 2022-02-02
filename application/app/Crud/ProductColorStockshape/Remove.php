<?php
/**
 * @package AA_Project
 * 
 * REMOVE PRICING DATA VALUES
 * 
 */

namespace Api\Crud\ProductColorStockshape;

use Api\Hasher;

use Api\Models\ProductColorAndStockShapeModel;

use Api\Constants;

class Remove {

    public function remove( $request ) {

        try {

            if( isset( $request['ids'] ) ) {
                
                ProductColorAndStockShapeModel::whereIn('id', explode(',', $request['ids']))->delete();
                return true;

            }

            if( !isset( $request['id'] )) { 

                return rest_response( Constants::BAD_REQUEST, 500 );

            }

            $pvalue = ProductColorAndStockShapeModel::find( Hasher::decode($request['id']) )->delete();

            return "Removed";

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}