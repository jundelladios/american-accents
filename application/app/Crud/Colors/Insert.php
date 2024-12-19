<?php
/**
 * @package AA_Project
 * 
 * Insert Colors
 * 
 */

namespace Api\Crud\Colors;

use Api\Hasher;

use Api\Models\ColorsModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    private $required = ['title'];

    private $statuses = ['active', 'priority'];

    private $allowednulls = ['colorjson'];

    public function store( $request ) {

        try {
            
            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                $this->statuses,
                $this->allowednulls
            ));

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', $this->required);

            $validate->rule('jsonString', ['colorjson'], '/colorcollection.json');

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            return $this->updateOrPostHelper( new ColorsModel, $data, ColorsModel::query() );
            

        } catch( \Exception $e ) {

            if( $e->getCode() == 23000 ) {

                return rest_response( Constants::COLOR_TITLE_EXISTS, 422 );
                
            }

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }
}