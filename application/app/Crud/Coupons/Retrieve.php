<?php

/**
 * RETRIEVE COUPON HANDLER
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AA_Project
 */

namespace Api\Crud\Coupons;

use Api\Constants;

use Api\Traits\ControllerTraits;

use Api\Hasher;

use Api\Models\CouponsModel;

class Retrieve {

    use ControllerTraits;

    // Coupon Codes

    public function get( $request ) {

        try {

            $coupons = CouponsModel::query();

            $coupons->select("*");

            if( isset( $request['id'] ) ) {

                $coupons->where( 'id', Hasher::decode( $request['id'] ) );

                if( !$coupons->first() ) {

                    return rest_response( Constants::NOT_FOUND, 404 );

                }

            }
            
            if( isset( $request['search'] ) ) {

                $coupons->where( 'code', 'like', '%' . $request['search'] . '%' );
            }

            return $this->getHelper( $coupons, $request );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}