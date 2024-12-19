<?php
/**
 * @package AA_Project
 * 
 * REMOVE PRICING DATA VALUES
 * 
 */

namespace Api\Crud\ClipArts;

use Api\Models\ClipArtsModel;

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

            $clipart = ClipArtsModel::where('id', Hasher::decode($request['id']))->first();

            if( !$clipart ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            $clipart->delete();

            return true;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}