<?php
/**
 * @package AA_Project
 * 
 * PRICING DATA REMOVE
 * 
 */

namespace Api\Crud\SpecificationTypes;

use Api\Hasher;

use Api\Models\SpecificationTypesModel;

use Api\Constants;

class Remove {

    public function remove( $request ) {

        try {

            if( !isset( $request['id'] )) { 

                return rest_response( Constants::BAD_REQUEST, 500 );

            }

            $spectype = SpecificationTypesModel::where('id', Hasher::decode($request['id']))->first();

            if( !$spectype ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            if( $spectype['usingthisspecification'] ) {
                
                return rest_response( 'There is product/product combo using this specification type, you cannot remove this.', 422 );

            }

            $spectype->delete();

            return true;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}