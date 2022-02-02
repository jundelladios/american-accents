<?php
/**
 * @package AA_Project
 * 
 * Update Colors
 * 
 */

namespace Api\Crud\Colors;

use Api\Hasher;

use Api\Models\ColorsModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['title', 'id'];

    private $statuses = ['active', 'priority'];

    private $allowednulls = ['colorjson'];

    public function update( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                $this->statuses,
                $this->allowednulls
            ));

            $data['id'] =  Hasher::decode( $data['id'] );

            // Validation
            $validate = new Validator($data);

            $validate->rule('jsonString', ['colorjson'], '/colorcollection.json');

            $this->_required( $validate, $data, $this->required);


            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $update = ColorsModel::find( $data['id'] );

            if( !$update ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }
            
            return $this->updateOrPostHelper( $update, $data, ColorsModel::query() );

        } catch( \Exception $e ) {


            if( $e->getCode() == 23000 ) {

                return rest_response( Constants::COLOR_TITLE_EXISTS, 422 );

            }

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}