<?php
/**
 * @package AA_Project
 * 
 * REMOVE PRICING DATA VALUES
 * 
 */

namespace Api\Crud\ProductColors;

use Api\Hasher;

use Api\Models\ProductColorsModel;

use Api\Constants;

class Remove {

    public function remove( $request ) {

        try {

            if( isset( $request['ids'] ) ) {
                
                ProductColorsModel::whereIn('id', explode(',', $request['ids']))->delete();
                return true;

            }

            if( !isset( $request['id'] )) { 

                return rest_response( Constants::BAD_REQUEST, 500 );

            }

            $pvalue = ProductColorsModel::find( Hasher::decode($request['id']) )->delete();

            return true;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}