<?php
/**
 * @package AA_Project
 * 
 * UPDATE imprint type
 * 
 */

namespace Api\Crud\ImprintTypes;

use Api\Hasher;

use Api\Models\ImprintTypesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['title', 'id'];
    
    private $statuses = ['priority', 'active'];

    private $allowednulls = ['body'];

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

            $update = ImprintTypesModel::find( $data['id'] );

            if( !$update ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }
            
            return $this->updateOrPostHelper( $update, $data, ImprintTypesModel::query(), $this->allowednulls );

        } catch( \Exception $e ) {

            if( $e->getCode() == 23000 ) {

                return rest_response( Constants::IMPRINT_TYPE_EXIST, 422 );

            }

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}