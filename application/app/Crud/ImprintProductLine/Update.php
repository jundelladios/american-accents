<?php
/**
 * @package AA_Project
 * 
 * UPDATE imprint type
 * 
 */

namespace Api\Crud\ImprintProductLine;

use Api\Hasher;

use Api\Models\ImprintTypeProductLineModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['image', 'id'];
    
    private $statuses = ['priority'];

    private $allowednulls = ['min_prod_days', 'imprint_charge'];

    public function update( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                $this->statuses ,
                $this->allowednulls
            ));

            $data['id'] =  Hasher::decode( $data['id'] );

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', 'id');

            $this->_required( $validate, $data, $this->required);


            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $update = ImprintTypeProductLineModel::find( $data['id'] );

            if( !$update ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            $ret = ImprintTypeProductLineModel::query();

            $ret->with(['imprinttype']);
            
            return $this->updateOrPostHelper( $update, $data, $ret, $this->allowednulls );

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}