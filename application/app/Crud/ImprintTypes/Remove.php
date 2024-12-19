<?php
/**
 * @package AA_Project
 * 
 * REMOVE PRICING DATA VALUES
 * 
 */

namespace Api\Crud\ImprintTypes;

use Api\Models\ImprintTypesModel;

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

            $imprint = ImprintTypesModel::where('id', Hasher::decode($request['id']))->first();

            if( !$imprint ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            if( $imprint['hasproductline'] ) {
                
                return rest_response( 'There is a product line using this imprint type, you cannot remove this.', 422 );

            }

            $imprint->delete();

            return true;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}