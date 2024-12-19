<?php
/**
 * @package AA_Project
 * 
 * REMOVE PRICING DATA VALUES
 * 
 */

namespace Api\Crud\Coupons;

use Api\Models\CouponsModel;

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

            $cuopon = CouponsModel::where('id', Hasher::decode($request['id']))->first();

            if( !$cuopon ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            if( $cuopon['haspline'] ) {

                return rest_response( 'There is product line using this coupon, you cannot remove this.', 422 );

            }

            $cuopon->delete();

            return true;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}