<?php
/**
 * @package AA_Project
 * 
 * PRINT METHOD UPDATE
 * 
 */

namespace Api\Crud\PrintMethods;

use Api\Hasher;

use Api\Models\PrintMethodsModel;

use Api\Constants;

use Api\Traits\ControllerTraits;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['method_name', 'method_slug', 'method_prefix', 'method_hex'];

    private $allowedNulls = ['method_desc', 'method_desc_short', 'method_name2', 'keyfeatures', 'is_unprinted'];

    private $statuses = ['priority', 'active'];

    public function update( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge(
                $this->required, 
                ['id'], 
                $this->allowedNulls, 
                $this->statuses
            ));


            // Validation
            $validate = new Validator($data);

            $validate->rule('required', 'id');

            $validate->rule('jsonString', ['keyfeatures'], '/printmethods.json');

            $this->_required( $validate, $data, $this->required);

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $data['id'] =  Hasher::decode( $data['id'] );

            $update = PrintMethodsModel::find( $data['id'] );

            if( !$update ) {
                
                return rest_response( Constants::NOT_FOUND, 404 );

            }


            if( $data['method_slug'] && $data['method_slug'] != $update['method_slug'] ) {

                $data['method_slug'] = (new PrintMethodsModel)->slugHandler( $data['method_slug'] );
                
            }

            return $this->updateOrPostHelper( $update, $data, PrintMethodsModel::query(), $this->allowedNulls );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}