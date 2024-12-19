<?php
/**
 * @package AA_Project
 * 
 * UPDATE COUPON
 * 
 */

namespace Api\Crud\Coupons;

use Api\Hasher;

use Api\Models\CouponsModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['code'];
    
    private $statuses = ['priority', 'active'];

    public function update( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                ['id'], 
                $this->statuses 
            ));

            $data['id'] =  Hasher::decode( $data['id'] );

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', 'id');

            $this->_required( $validate, $data, $this->required);


            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $update = CouponsModel::find( $data['id'] );

            if( !$update ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }
            
            return $this->updateOrPostHelper( $update, $data, CouponsModel::query() );

        } catch( \Exception $e ) {


            if( $e->getCode() == 23000 ) {

                return rest_response( Constants::COUPON_EXISTS, 422 );

            }

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}