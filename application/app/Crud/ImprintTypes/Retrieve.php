<?php
/**
 * @package AA_Project
 * 
 * Retrieve Imprint Type
 * 
 */

namespace Api\Crud\ImprintTypes;

use Api\Hasher;

use Api\Models\ImprintTypesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $imprint = ImprintTypesModel::query();

            $imprint->select("*");

            if( isset( $request['id'] ) ) {

                $imprint->where( 'id', Hasher::decode( $request['id'] ) );

                if( !$imprint->first() ) {

                    return rest_response( Constants::NOT_FOUND, 404 );

                }

            }
            
            if( isset( $request['search'] ) ) {

                $imprint->where( 'title', 'like', '%' . $request['search'] . '%' );
            }

            return $this->getHelper( $imprint, $request );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}