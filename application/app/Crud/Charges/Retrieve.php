<?php
/**
 * @package AA_Project
 * 
 * CHARGES RETRIEVE
 * 
 */

namespace Api\Crud\Charges;

use Api\Hasher;

use Api\Models\ChargesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $charges = ChargesModel::query();

            $charges->select("*");

            if( isset( $request['id'] ) ) {

                $charges->where( 'id', Hasher::decode( $request['id'] ) );

                if( !$charges->first() ) {

                    return rest_response( Constants::NOT_FOUND, 404 );

                }

            }

            return $this->getHelper( $charges, $request );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}