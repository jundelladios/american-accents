<?php
/**
 * @package AA_Project
 * 
 * PRINT METHOD RETRIEVE
 * 
 */

namespace Api\Crud\PrintMethods;

use Api\Hasher;

use Api\Models\PrintMethodsModel;

use Api\Constants;

use Api\Traits\ControllerTraits;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $methods = PrintMethodsModel::query();

            $methods->select("*");

            if( isset( $request['slug'] ) ) {

                $methods->where( 'method_slug', $request['slug'] );

                if( !$methods->first() ) {

                    return rest_response( Constants::NOT_FOUND, 404 );

                }

            }
            
            if( isset( $request['search'] ) ) {

                $methods->where( 'method_name', 'like', '%' . $request['search'] . '%' );
            }
            
            return $this->getHelper( $methods, $request );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}