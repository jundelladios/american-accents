<?php
/**
 * @package AA_Project
 * 
 * PRODUCT LINES RETRIEVE
 * 
 */

namespace Api\Crud\ProductLines;

use Api\Hasher;

use Api\Models\ProductLinesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $subcatmethod = ProductLinesModel::query();

            $subcatmethod->select("*");

            if( isset( $request['product_subcategory_id'] ) ) {

                $subcatmethod->where( 'product_subcategory_id', Hasher::decode( $request['product_subcategory_id'] ) );

            }

             $subcatmethod->with(['printmethod', 'couponcode']); 

             if( isset( $request['admin'] ) ) {

                $subcatmethod->with(['plinecolors', 'premiumbg', 'stockshapes']);

             }

            return $this->getHelper( $subcatmethod, $request );

        } catch ( \Exception $e ) { return $e->getMessage();

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}