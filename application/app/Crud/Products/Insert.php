<?php
/**
 * @package AA_Project
 * 
 * PRODUCT STORE
 * 
 */

namespace Api\Crud\Products;

use Api\Hasher;

use Api\Models\ProductsModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    private $required = [
        'product_name',
        'product_slug',
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

    private $postRequired = [
        'product_category_id',
        'product_subcategory_id'
    ];

    public function store( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required, 
                $this->allowedNulls, 
                $this->postRequired
            ));

            $validate = new Validator($data);

            $validate->rule('required', array_merge( 
                $this->required, 
                $this->postRequired 
            ));

            $validate->rule('jsonString', ['specs_json'], '/specification/form.json');

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $data['product_slug'] = (new ProductsModel)->slugHandler( $data['product_slug'] );

            $data['product_category_id'] = Hasher::decode( $data['product_category_id'] );

            $data['product_subcategory_id'] = Hasher::decode( $data['product_subcategory_id'] );

            if( $data['specification_id'] ) {

                $data['specification_id'] = Hasher::decode( $data['specification_id'] );

            }


            $ret = ProductsModel::query();

            $ret->with(['category', 'subcategory']);

            return $this->updateOrPostHelper( new ProductsModel, $data, $ret );

        } catch( \Exception $e ) {

            if( $e->getCode() == 23000 ) {

                return rest_response( Constants::UPDATE_SLUG_ERROR, 422 );

            }
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}