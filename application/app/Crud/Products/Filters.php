<?php
/**
 * @package AA_Project
 * 
 * PRODUCT UPDATE
 * 
 */

namespace Api\Crud\Products;

use Api\Models\ProductsModel;

use Api\Models\PrintMethodsModel;

use Api\Constants;

use Api\Collection;

use Api\Hasher;

class Filters {

    public function get( $request ) {

        try {

            $mtypes = ProductsModel::query();

            $mtypes->leftJoin('product_categories', 'product_categories.id', '=', 'products.product_category_id')
            ->leftJoin('product_subcategories', 'product_subcategories.id', '=', 'products.product_subcategory_id');

            $mtypes->select('material_type');

            $mtypes->whereNotNull('material_type');

            $mtypes->groupByRaw('material_type');

            $mtypes->where('products.active', 1);



            $size = ProductsModel::query();

            $size->select('product_size', 'product_size_details');

            $size->whereNotNull('product_size');

            $size->whereNotNull('product_size_details');

            $size->groupByRaw('product_size, product_size_details');

            $size->where('products.active', 1);


            // $colors = ProductsModel::query();

            // $colors->select('product_color_hex', 'product_color_details');

            // $colors->whereNotNull('product_color_hex');

            // $colors->whereNotNull('product_color_details');

            // $colors->groupByRaw('product_color_hex, product_color_details');


            $methods = PrintMethodsModel::query();

            $methods->select('method_name', 'method_slug');

            $methods->where('is_unprinted', '!=', 1);

            if( isset( $request['category'] ) && !empty( $request['category'] ) ) {

                $mtypes->where('product_categories.id', '=', Hasher::decode( $request['category'] ));

            }


            if( isset( $request['subcategory'] ) && !empty( $request['subcategory'] ) ) {

                $mtypes->where('product_subcategories.id', '=', Hasher::decode( $request['subcategory'] ));

            }



            return [
                'materials' => Collection::toJson($mtypes->get()),
                'size' => Collection::toJson($size->get()),
                //'colors' => Collection::toJson($colors->get()),
                'methods' => Collection::toJson($methods->get())
            ];

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}