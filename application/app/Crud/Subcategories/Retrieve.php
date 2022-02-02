<?php
/**
 * @package AA_Project
 * 
 * SUBCATEGORIES INSERT
 * 
 */

namespace Api\Crud\Subcategories;

use Api\Models\ProductSubcategoriesModel;

use Api\Hasher;

use Api\Traits\ControllerTraits;

use Api\Constants;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $subcategories = ProductSubcategoriesModel::query();

            if( isset( $request['product_category_id'] ) ) {

                $request['product_category_id'] = Hasher::decode( $request['product_category_id'] );

                $subcategories->where( 'product_category_id', $request['product_category_id'] );

            }

            if( isset( $request['search'] ) ) {

                $subcategories->where( 'sub_name', 'like', '%' . $request['search'] . '%' );
            }

            return $this->getHelper( $subcategories, $request );
            

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

    public function categorize( $request ) {

        try {

            $categorizeAlias = ProductSubcategoriesModel::query();

            $categorizeAlias->select('categorize_as');

            if( isset( $request['product_category_id'] ) ) {

                $request['product_category_id'] = Hasher::decode( $request['product_category_id'] );

                $categorizeAlias->where( 'product_category_id', $request['product_category_id'] );

            }

            $categorizeAlias->whereNotNull( 'categorize_as' );
            
            $categorizeAlias->groupBy( 'categorize_as' );

            return $categorizeAlias->get();


        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}