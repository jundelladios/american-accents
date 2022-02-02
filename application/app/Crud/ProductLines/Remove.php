<?php
/**
 * @package AA_Project
 * 
 * REMOVE PRODUCT LINES
 * 
 */

namespace Api\Crud\ProductLines;

use Api\Models\ProductLinesModel;

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

            $pline = ProductLinesModel::where('id', Hasher::decode($request['id']))->first();

            if( !$pline ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            if( $pline['hasproductcombo'] ) {
                
                return rest_response( 'You cannot remove this productline, there is a product combo using this productline.', 422 );

            }

            $pline->deleteWithRelations();

            return true;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}