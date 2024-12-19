<?php

/**
 * RETRIEVE SPECIFICATION TYPES
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AA_Project
 */

namespace Api\Crud\SpecificationTypes;

use Api\Constants;

use Api\Traits\ControllerTraits;

use Api\Hasher;

use Api\Models\SpecificationTypesModel;

use Api\Collection;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $specs = SpecificationTypesModel::query();
    
            $specs->select("*");
    
            if( isset( $request['id'] ) ) {
    
                $specs->where( 'id', Hasher::decode( $request['id'] ) );
    
                if( !$specs->first() ) {
    
                    return rest_response( Constants::NOT_FOUND, 404 );
    
                }
    
            }

            if( isset( $request['search'] ) ) {

                $specs->where( 'title', 'like', '%' . $request['search'] . '%' );
            }

            if( isset( $request['isspec'] ) ) {

                $specs->where( 'isspec', 1 );

            }
    
            return $this->getHelper( $specs, $request );
    
        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );
    
        }

    }

}