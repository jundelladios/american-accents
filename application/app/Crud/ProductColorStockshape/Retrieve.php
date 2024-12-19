<?php
/**
 * @package AA_Project
 * 
 * Retrieve Colors
 * 
 */

namespace Api\Crud\ProductColorStockshape;

use Api\Hasher;

use Api\Models\ProductColorAndStockShapeModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $colorstockshape = ProductColorAndStockShapeModel::query();

            $colorstockshape->join('product_colors', 'product_colors.id', '=', 'product_color_stockshape.product_color_id');

            $colorstockshape->join('product_stockshapes', 'product_stockshapes.id', '=', 'product_color_stockshape.product_stockshape_id');

            $colorstockshape->select("product_color_stockshape.*");

            if( isset( $request['product_print_method_id'] ) ) {

                $colorstockshape->where( 'product_color_stockshape.product_print_method_id', Hasher::decode( $request['product_print_method_id'] ) );

            }

            if( isset( $request['id'] ) ) {

                $colorstockshape->where( 'id', Hasher::decode( $request['id'] ) );

            }

            if( isset( $request['slug'] ) ) {

                $colorstockshape->where( 'slug', $request['slug'] );

            }

            if(isset( $request['query'])) {
                $query = $request['query'];
                $colorstockshape->where(function($qs) use($query) {
                    $qs->where('product_colors.colorhex', 'LIKE', "%$query%")
                    ->orWhere('product_colors.colorname', 'LIKE', "%$query%")
                    ->orWhere('product_colors.pantone', 'LIKE', "%$query%")


                    ->orWhere('product_stockshapes.stockname', 'LIKE', "%$query%")
                    ->orWhere('product_stockshapes.code', 'LIKE', "%$query%")

                    ->orWhere('product_color_stockshape.vdsid', 'LIKE', "%$query%")
                    ->orWhere('product_color_stockshape.vdsproductid', 'LIKE', "%$query%");
                });
            }

            $colorstockshape->with(['theshape']);

            $colorstockshape->with(['thecolor']);

            return $this->getHelper( $colorstockshape, $request, false );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}