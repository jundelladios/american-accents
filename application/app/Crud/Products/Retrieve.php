<?php
/**
 * @package AA_Project
 * 
 * PRODUCT UPDATE
 * 
 */

namespace Api\Crud\Products;

use Api\Hasher;

use Api\Models\ProductsModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $products = ProductsModel::query();

            $products->select('*');

            $products->with(['category', 'subcategory']);

            if( isset( $request['category_id'] ) ) {

                $products->whereIn( 'product_category_id', $this->ids( $request['category_id'] ) );

            }

            if( isset( $request['subcategory_id'] ) ) {

                $products->whereIn( 'product_subcategory_id', $this->ids( $request['subcategory_id'] ) );

            }

            if( isset( $request['search'] ) ) {

                $products->where( 'product_name', 'like', '%' . $request['search'] . '%' );

            }

            if( isset( $request['hexs'] ) ) {

                $products->whereIn( 'product_color_hex', explode(',', $request['hexs']) );

            }

            if( isset( $request['material_type'] ) ) {

                $products->whereIn( 'material_type', explode(',', $request['material_type'] ) );

            }

            return $this->getHelper( $products, $request );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}