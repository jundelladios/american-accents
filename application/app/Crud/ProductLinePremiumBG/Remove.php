<?php
/**
 * @package AA_Project
 * 
 * PRODUCT LINES Color INSERT
 * 
 */

namespace Api\Crud\ProductLinePremiumBG;

use Api\Hasher;

use Api\Models\ProductLinePremiumBgModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

class Remove {

    use ControllerTraits;

    public function remove( $request ) {

        try {

            $remove = ProductLinePremiumBgModel::find( Hasher::decode( $request['id'] ) );

            if( $remove ) {
               
                $remove->delete();
                
            }

            return "Removed";

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}