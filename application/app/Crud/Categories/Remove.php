<?php
/**
 * @package AA_Project
 * 
 * REMOVE PRICING DATA VALUES
 * 
 */

namespace Api\Crud\Categories;

use Api\Models\ProductCategoriesModel;

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

            $categories = ProductCategoriesModel::where('id', Hasher::decode($request['id']))->first();

            if( !$categories ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            if( $categories['hassubcategories'] ) {
                
                return rest_response( 'You cannot remove this category, please remove all subcategories under this category first.', 422 );

            }

            $categories->delete();

            return true;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}