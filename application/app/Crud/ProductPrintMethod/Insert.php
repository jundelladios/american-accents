<?php
/**
 * @package AA_Project
 * 
 * PRODUCT PRINT METHOD INSERT
 * 
 */

namespace Api\Crud\ProductPrintMethod;

use Api\Models\ProductPrintMethodModel;

use Api\Hasher;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    // private $required = ['feature_img', 'package_count_min', 'package_count_max'];

    private $required = ['feature_img'];

    private $postRequired = ['product_id', 'product_line_id'];

    private $statuses = ['active', 'priority'];

    private $allowedNulls = [
        'description', 
        'features_options', 
        // 'disclaimer', 
        // 'downloads', 
        'templates', 
        'seo_content', 
        'features_options2',
        // 'imprint_width',
        // 'imprint_height',
        // 'imprint_bleed_wrap_width',
        // 'imprint_bleed_wrap_height',
        // 'package_count_as',
        'allow_print_method_prefix',
        // 'imprint_as',
        // 'imprint_bleed_as',
        // 'shape',
        'specs_json',
        'specification_id',
        'specification_output_id',
        'keywords'
    ];

    public function store( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required, 
                $this->allowedNulls,
                $this->statuses,
                $this->postRequired
            ));

            $data['product_id'] = Hasher::decode( $data['product_id'] );

            $data['product_line_id'] = Hasher::decode( $data['product_line_id'] );

            if( $data['specification_id'] ) {

                $data['specification_id'] = Hasher::decode( $data['specification_id'] );

            }

            if( $data['specification_output_id'] ) {

                $data['specification_output_id'] = Hasher::decode( $data['specification_output_id'] );

            }

            // Validation
            $validate = new Validator($data);

            $validate->rule('jsonString', ['feature_img'], '/productcombo/feature_img.json');

            $validate->rule('required', array_merge( $this->required, $this->postRequired));

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $exists = ProductPrintMethodModel::where('product_id', $data['product_id'])->where('product_line_id', $data['product_line_id'])->first();

            if( $exists ) {

                return rest_response( "This combination already exists.", 422 );

            }

            $ret = ProductPrintMethodModel::query();

            $ret->with(['product', 'productline' => function($query) {

                $query->with(['printmethod', 'pricingData', 'couponcode']);

            }]);

            $ret->with(['pricings']);

            return $this->updateOrPostHelper( new ProductPrintMethodModel, $data, $ret );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}