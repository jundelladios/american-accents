<?php
/**
 * @package AA_Project
 * 
 * REMOVE PRICING DATA VALUES
 * 
 */

namespace Api\Crud\Products;

use Api\Models\ProductsModel;

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

            $product = ProductsModel::where('id', Hasher::decode($request['id']))->first();

            if( !$product ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            if( $product['hascombo'] ) {
                
                return rest_response( 'You cannot remove this product, please remove combination of this product first.', 422 );

            }

            $product->delete();

            return true;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}