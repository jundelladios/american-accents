<?php
/**
 * @package AA_Project
 * 
 * CLIP ARTS RETRIEVE
 * 
 */

namespace Api\Crud\ClipArts;

use Api\Hasher;

use Api\Models\ClipArtsModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $cliparts = ClipArtsModel::query();

            $cliparts->select("*");

            if( isset( $request['id'] ) ) {

                $cliparts->where( 'id', Hasher::decode( $request['id'] ) );

                if( !$cliparts->first() ) {

                    return rest_response( Constants::NOT_FOUND, 404 );

                }

            }
            
            if( isset( $request['search'] ) ) {

                $cliparts->where( 'clipartcategory', 'like', '%' . $request['search'] . '%' );
            }

            return $this->getHelper( $cliparts, $request );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}