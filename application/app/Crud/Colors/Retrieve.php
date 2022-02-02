<?php
/**
 * @package AA_Project
 * 
 * Retrieve Colors
 * 
 */

namespace Api\Crud\Colors;

use Api\Hasher;

use Api\Models\ColorsModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $colors = ColorsModel::query();

            $colors->select("*");

            if( isset( $request['id'] ) ) {

                $colors->where( 'id', Hasher::decode( $request['id'] ) );

                if( !$colors->first() ) {

                    return rest_response( Constants::NOT_FOUND, 404 );

                }

            }
            
            if( isset( $request['search'] ) ) {

                $colors->where( 'title', 'like', '%' . $request['search'] . '%' );
            }

            return $this->getHelper( $colors, $request );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}