<?php
/**
 * @package AA_Project
 * 
 * REMOVE PRICING DATA VALUES
 * 
 */

namespace Api\Crud\CollectionPremiumBackground;

use Api\Models\CollectionPremiumBackgroundsModel;

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

            $entry = CollectionPremiumBackgroundsModel::where('id', Hasher::decode($request['id']))->first();

            if( !$entry ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            $entry->delete();

            return true;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}