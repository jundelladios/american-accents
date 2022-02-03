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

use Api\Models\ProductLinesModel;

use Api\Models\ProductPrintMethodModel;

use Api\Models\ProductSubcategoriesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

use Illuminate\Database\Capsule\Manager as DB;

class Update {

    use ControllerTraits;

    private $required = [
        'product_name', 
        'product_slug',
        'product_category_id',
        'product_subcategory_id',
        'product_description'
    ];

    private $allowedNulls = [
        // 'dim_top',
        // 'dim_height',
        // 'dim_base',
        // 'area',
        // 'item_width',
        // 'item_height',
        // 'class_code',
        'product_size',
        'product_size_details',
        // 'product_color_hex',
        // 'product_color_details',
        'material_type',
        'priority',
        'product_thickness',
        'product_tickness_details',
        // 'product_depth',
        // 'area_sq_in',
        'specification_id',
        'specs_json'
    ];

    private $statuses = ['active', 'priority'];

    public function update( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required, 
                $this->allowedNulls, 
                ['id'], 
                $this->statuses 
            ));

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', 'id');

            $validate->rule('jsonString', ['specs_json'], '/specification/form.json');

            $this->_required( $validate, $data, $this->required);

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $data['id'] =  Hasher::decode( $data['id'] );

            $update = ProductsModel::find( $data['id'] );

            if( !$update ) {
                 
                return rest_response( Constants::NOT_FOUND, 404 );

            }


            if( $data['product_slug'] && $data['product_slug'] != $update['product_slug'] ) {

                $data['product_slug'] = (new ProductsModel)->slugHandler( $data['product_slug'] );
                
            }

            if( $data['specification_id'] ) {

                $data['specification_id'] = Hasher::decode( $data['specification_id'] );

            }

            if( isset( $data['product_category_id'] ) && $data['product_category_id'] ) {

                $data['product_category_id'] = Hasher::decode( $data['product_category_id'] );

            }

            if( isset($data['product_subcategory_id']) && $data['product_subcategory_id'] ) {

                $data['product_subcategory_id'] = Hasher::decode( $data['product_subcategory_id'] );

            }

             $ret = ProductsModel::query();

             $ret->with(['category', 'subcategory']);

             return $this->updateOrPostHelper( $update, $data, $ret, $this->allowedNulls );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }


    public function moveProduct( $request ) {

        try {

            $data = rest_requests( $request->get_params(), [
                'product_category_id',
                'product_subcategory_id',
                'id'
            ]);


            $validate = new Validator($data);


            $validate->rule('required', 'id');

            $validate->rule('required', 'product_category_id');

            $validate->rule('required', 'product_subcategory_id');
            

            if( !$validate->validate() ) {
 
                return rest_response( $validate->errors(), 422 );

            }


            $product = ProductsModel::where('id', Hasher::decode( $data['id'] ))->first();

            if( !$product ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            $productLines = ProductLinesModel::where('product_subcategory_id', Hasher::decode($data['product_subcategory_id']))
            ->select('product_lines.id as plineid', 'print_methods.id as pmethodid', 'print_methods.method_name', 'print_methods.method_name2')
            ->leftJoin('print_methods', 'print_methods.id', 'product_lines.print_method_id')
            ->groupBy('product_lines.id')
            ->get()
            ->toArray();

            $productPrintMethods = ProductPrintMethodModel::where('product_id', Hasher::decode($data['id']))
            ->select('product_print_method.id as productprintmid', 'product_lines.id as plineid', 'print_methods.id as pmethodid', 'print_methods.method_name', 'print_methods.method_name2')
            ->leftJoin('product_lines', 'product_lines.id', 'product_print_method.product_line_id')
            ->leftJoin('print_methods', 'print_methods.id', 'product_lines.print_method_id')
            ->groupBy('product_print_method.id')
            ->get()
            ->toArray();

            
            $missingMethods = [];
            $comboUpdateSetters = [];
            foreach($productPrintMethods as $pmethod) {
                $plineIndex = array_search($pmethod['pmethodid'], array_column($productLines, 'pmethodid'));
                if(is_bool($plineIndex)) {
                    $missingMethods[] = $pmethod['method_name'] . ' ' . $pmethod['method_name2'];
                }

                if(is_int($plineIndex)) {
                    $comboUpdateSetters[] = [
                        'id' => $pmethod['productprintmid'],
                        'product_line_id' => $productLines[$plineIndex]['plineid']
                    ];
                }
            }

            $newSubcategory = ProductSubcategoriesModel::where('id', Hasher::decode($data['product_subcategory_id']))->first();

            if(count($missingMethods)) {
                
                return rest_response( $newSubcategory['sub_name'] . " - missing productlines (please add them before moving product)". ': ' . join(", ", $missingMethods) , 422 );

            }

            DB::unprepared($this->massUpdate(new ProductPrintMethodModel, $comboUpdateSetters, 'id'));

            $product->product_category_id = Hasher::decode( $data['product_category_id'] );

            $product->product_subcategory_id = Hasher::decode( $data['product_subcategory_id'] );

            $product->save();

            return true;


        } catch(\Exception $e ) {

            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }
}