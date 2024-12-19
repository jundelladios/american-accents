<?php
/**
 * @package AA_Project
 * 
 * REMOVE PRICING DATA VALUES
 * 
 */

namespace Api\Crud\PrintMethods;

use Api\Models\PrintMethodsModel;

use Api\Hasher;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Remove {

    public function remove( $request ) {

        try {

            if( !isset( $request['id'] )) { 

                return rest_response( Constants::BAD_REQUEST, 500 );

            }

            $pmethod = PrintMethodsModel::where('id', Hasher::decode($request['id']))->first();

            if( !$pmethod ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            if( $pmethod['hasproductline'] ) {
                
                return rest_response( 'You cannot remove this print method, there is a product line using this print method.', 422 );

            }

            $pmethod->delete();

            return true;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}