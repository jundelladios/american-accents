<?php
/**
 * @package AA_Project
 * 
 * Retrieve Colors
 * 
 */

namespace Api\Crud\ProductStockShapes;

use Api\Hasher;

use Api\Models\ProductStockShapesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $stockshape = ProductStockShapesModel::query();

            $stockshape->select("*");

            if( isset( $request['product_print_method_id'] ) ) {

                $stockshape->where( 'product_print_method_id', Hasher::decode( $request['product_print_method_id'] ) );

            }

            if( isset( $request['id'] ) ) {

                $stockshape->where( 'id', Hasher::decode( $request['id'] ) );

            }

            if( isset( $request['slug'] ) ) {

                $stockshape->where( 'slug', $request['slug'] );

            }

            $stockshape->orderBy('priority');

            return $this->getHelper( $stockshape, $request, false );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}