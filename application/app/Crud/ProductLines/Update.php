<?php
/**
 * @package AA_Project
 * 
 * PRODUCT LINES UPDATE
 * 
 */

namespace Api\Crud\ProductLines;

use Api\Hasher;

use Api\Models\ProductLinesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['setup_charge'];

    private $statuses = ['active', 'priority', 'second_side', 'wrap', 'bleed', 'multicolor', 'process', 'white_ink', 'hotstamp', 'per_thousand', 'per_item'];

    private $allowedNulls = ['features', 'coupon_code_id', 'features_pivot', 'colors', 'compliances', 'image', 'pnotes', 'seo_content', 'banner_img', 'pnotes2', 'premium_backgrounds', 'price_tagline'];

    public function update( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge(
                $this->required, 
                $this->allowedNulls,
                $this->statuses,
                ['id']
            ));

            $data['id'] =  Hasher::decode( $data['id'] );

            if( $data['coupon_code_id'] ) {

                $data['coupon_code_id'] = Hasher::decode( $data['coupon_code_id'] );

            }

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', 'id');

            $validate->rule('jsonString', ['features'], '/productline/features.json');

            $validate->rule('jsonString', ['features_pivot'], '/productline/features_pivot.json');

            $validate->rule('jsonString', ['compliances'], '/productline/compliances.json');

            $validate->rule('jsonString', ['colors'], '/productline/colors.json');

            $validate->rule('jsonString', ['pnotes'], '/productline/pnotes.json');

            $this->_required( $validate, $data, $this->required);

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $update = ProductLinesModel::find( $data['id'] );

            if( !$update ) {
                
                return rest_response( Constants::NOT_FOUND, 404 );

            }

            $ret = ProductLinesModel::query();

            $ret->with(['printmethod', 'couponcode']);

            return $this->updateOrPostHelper( $update, $data, $ret, $this->allowedNulls );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}