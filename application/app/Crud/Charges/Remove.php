<?php
/**
 * @package AA_Project
 * 
 * PRICING DATA REMOVE
 * 
 */

namespace Api\Crud\Charges;

use Api\Hasher;

use Api\Models\ChargesModel;

use Api\Constants;

class Remove {

    public function remove( $request ) {

        try {

            if( !isset( $request['id'] )) { 

                return rest_response( Constants::BAD_REQUEST, 500 );

            }

            $charge = ChargesModel::where('id', Hasher::decode($request['id']))->first();

            if( !$charge ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            if( $charge['haspricingdata'] ) {
                
                return rest_response( 'There is a product line using this charge type, you cannot remove this.', 422 );

            }

            $charge->delete();

            return true;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}