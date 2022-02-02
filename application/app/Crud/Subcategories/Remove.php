<?php
/**
 * @package AA_Project
 * 
 * REMOVE PRICING DATA VALUES
 * 
 */

namespace Api\Crud\Subcategories;

use Api\Models\ProductSubcategoriesModel;

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

            $subcategory = ProductSubcategoriesModel::where('id', Hasher::decode($request['id']))->first();

            if( !$subcategory ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            if( $subcategory['hasproducts'] ) {
                
                return rest_response( 'You cannot remove this subcategory, please remove all products under this subcategory first.', 422 );

            }

            $subcategory->delete();

            return true;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}