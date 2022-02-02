<?php
/**
 * @package AA_Project
 * 
 * PRICING DATA REMOVE
 * 
 */

namespace Api\Crud\ImprintProductLine;

use Api\Hasher;

use Api\Models\ImprintTypeProductLineModel;

use Api\Constants;

class Remove {

    public function remove( $request ) {

        try {

            if( !isset( $request['id'] ) ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            $remove = ImprintTypeProductLineModel::where( 'id', Hasher::decode( $request['id'] ) );

            $remove->delete();

            return "Removed";

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}