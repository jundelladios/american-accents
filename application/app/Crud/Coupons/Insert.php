<?php
/**
 * @package AA_Project
 * 
 * INSERT COUPON
 * 
 */

namespace Api\Crud\Coupons;

use Api\Hasher;

use Api\Models\CouponsModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    private $required = ['code'];

    public function store( $request ) {

        try {
            
            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required
            ));

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', $this->required);

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            return $this->updateOrPostHelper( new CouponsModel, $data, CouponsModel::query() );
            

        } catch( \Exception $e ) {

            if( $e->getCode() == 23000 ) {

                return rest_response( Constants::COUPON_EXISTS, 422 );
                
            }

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}