<?php
/**
 * @package AA_Project
 * 
 * Retrieve Colors
 * 
 */

namespace Api\Crud\ProductColors;

use Api\Hasher;

use Api\Models\ProductColorsModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $colors = ProductColorsModel::query();

            $colors->select("*");

            if( isset( $request['product_id'] ) ) {

                $colors->where( 'product_id', Hasher::decode( $request['product_id'] ) );

            }

            if( isset( $request['product_print_method_id'] ) ) {

                $colors->where( 'product_print_method_id', Hasher::decode( $request['product_print_method_id'] ) );

            }

            if( isset( $request['id'] ) ) {

                $colors->where( 'id', Hasher::decode( $request['id'] ) );

            }

            if( isset( $request['slug'] ) ) {

                $colors->where( 'slug', $request['slug'] );

            }

            $colors->orderBy('priority');

            return $this->getHelper( $colors, $request, false );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}