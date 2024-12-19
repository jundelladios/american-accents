<?php
/**
 * @package AA_Project
 * 
 * PRODUCT LINES INSERT
 * 
 */

namespace Api\Crud\ProductLines;

use Api\Hasher;

use Api\Models\ProductLinesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    private $required = ['setup_charge'];

    private $postRequired = ['product_subcategory_id', 'print_method_id'];

    private $statuses = ['active', 'priority', 'second_side', 'wrap', 'bleed', 'multicolor', 'process', 'white_ink', 'hotstamp', 'per_thousand', 'per_item'];

    private $allowedNulls = ['features', 'coupon_code_id', 'features_pivot', 'colors', 'compliances', 'image', 'pnotes', 'seo_content', 'banner_img', 'pnotes2', 'price_tagline', 'show_currency',
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

            $data['product_subcategory_id'] = Hasher::decode( $data['product_subcategory_id'] );

            $data['print_method_id'] = Hasher::decode( $data['print_method_id'] );

            if( $data['coupon_code_id'] ) {

                $data['coupon_code_id'] = Hasher::decode( $data['coupon_code_id'] );

            }

            // Validation
            $validate = new Validator($data);

            $validate->rule('jsonString', ['features'], '/productline/features.json');

            $validate->rule('jsonString', ['features_pivot'], '/productline/features_pivot.json');

            $validate->rule('jsonString', ['compliances'], '/productline/compliances.json');

            $validate->rule('jsonString', ['colors'], '/productline/colors.json');

            $validate->rule('jsonString', ['pnotes'], '/productline/pnotes.json');

            $validate->rule('required', array_merge( $this->required, $this->postRequired));

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $exists = ProductLinesModel::where('product_subcategory_id', $data['product_subcategory_id'])->where('print_method_id', $data['print_method_id'])->first();

            if( $exists ) {

                return rest_response( "This product line already exists, please check your product line.", 422 );

            }

            $ret = ProductLinesModel::query();

            $ret->with(['printmethod', 'couponcode']);

            return $this->updateOrPostHelper( new ProductLinesModel, $data, $ret );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}