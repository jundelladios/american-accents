<?php

/**
 * UPDATE SPECIFICATION TYPES
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AA_Project
 */

namespace Api\Crud\SpecificationTypes;

use Api\Constants;

use Api\Traits\ControllerTraits;

use Api\Hasher;

use Api\Models\SpecificationTypesModel;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['title'];

    private $allowedNulls = [
        'customfield', 
        // 'customfieldcombo', 
        'specs'
    ];
    
    private $statuses = ['priority', 'active', 'isspec'];

    public function update( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required, 
                $this->allowedNulls, 
                ['id'], 
                $this->statuses 
            ));

            $data['id'] =  Hasher::decode( $data['id'] );

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', 'id');

            $validate->rule('jsonString', ['customfield'], '/specification/form.json');

            // $validate->rule('jsonString', ['customfieldcombo'], '/specification/form.json');

            // $validate->rule('jsonString', ['specs'], '/specification/specification.json');

            $this->_required( $validate, $data, $this->required);


            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $update = SpecificationTypesModel::find( $data['id'] );

            if( !$update ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }
            
            return $this->updateOrPostHelper( $update, $data, SpecificationTypesModel::query(), $this->allowedNulls );

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}