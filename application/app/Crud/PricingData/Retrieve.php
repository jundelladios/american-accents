<?php
/**
 * @package AA_Project
 * 
 * RETRIEVE PRICING DATA
 * 
 */

namespace Api\Crud\PricingData;

use Api\Hasher;

use Api\Models\PricingDataModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

use Illuminate\Database\Capsule\Manager as DB;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $pdata = PricingDataModel::query();

            if( isset( $request['plinejoin'] ) ) {

                $pdata->select(
                    'pricing_data.*',
                    'product_subcategories.id as subcatid',
                    'product_lines.id as plineid',
                    'product_subcategories.sub_name'
                );

                $pdata->leftJoin('product_lines', '.product_lines.id', 'pricing_data.product_line_id')
                ->leftJoin('product_subcategories', 'product_lines.product_subcategory_id', '=', 'product_subcategories.id');

            }

            if( isset( $request['product_line_id'] ) ) {

                $pdata->where( 'product_line_id', Hasher::decode( $request['product_line_id'] ) );   

            }

            $pdata->with(['chargetypes']);

            if( isset( $request['productline'] ) ) {

                $pdata->with(['productline' => function($q) {
                    $q
                    ->leftJoin('product_subcategories', 'product_lines.product_subcategory_id', '=', 'product_subcategories.id')
                    ->leftJoin('print_methods', 'product_lines.print_method_id', '=', 'print_methods.id')
                    ->select(
                        'product_lines.id',
                        'print_methods.id as pmethodid',
                        'product_subcategories.id as subcategoryID',
                        'product_subcategories.sub_name',
                        'print_methods.method_name',
                        'print_methods.method_name2',
                        DB::raw("CONCAT(print_methods.method_name, IF(print_methods.method_name2 IS NOT NULL, CONCAT(' ', print_methods.method_name2), '')) as pline_method_fullname"),
                        DB::raw("CONCAT(product_subcategories.sub_name, ' - ', print_methods.method_name, ' ', print_methods.method_name2) as plinecombination")
                    );
                }]);

            }

            return $this->getHelper( $pdata, $request, false );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}