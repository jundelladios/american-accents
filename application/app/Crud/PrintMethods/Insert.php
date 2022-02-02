<?php
/**
 * @package AA_Project
 * 
 * PRINT METHOD INSERT
 * 
 */

namespace Api\Crud\PrintMethods;

use Api\Hasher;

use \WP_REST_Request as Request;

use Api\Models\PrintMethodsModel;

use Api\Constants;

use Api\Traits\ControllerTraits;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    private $required = ['method_name', 'method_slug', 'method_prefix', 'method_hex'];

    private $allowedNulls = ['method_desc', 'method_desc_short', 'method_name2', 'keyfeatures', 'is_unprinted'];

    public function store( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required, 
                $this->allowedNulls 
            ));

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', $this->required);

            $validate->rule('jsonString', ['keyfeatures'], '/printmethods.json');

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $data['method_slug'] = (new PrintMethodsModel)->slugHandler( $data['method_slug'] );

            return $this->updateOrPostHelper( new PrintMethodsModel, $data, PrintMethodsModel::query() );

        } catch( \Exception $e ) {

            if( $e->getCode() == 23000 ) {

                return rest_response( Constants::STORE_SLUG_ERROR, 422 );

            }

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}