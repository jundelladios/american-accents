<?php
/**
 * @package AA_Project
 * 
 * Insert Imprint Type
 * 
 */

namespace Api\Crud\ImprintTypes;

use Api\Hasher;

use Api\Models\ImprintTypesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    private $required = ['title'];

    private $statuses = ['active', 'priority'];

    private $allowednulls = ['body'];

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

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            return $this->updateOrPostHelper( new ImprintTypesModel, $data, ImprintTypesModel::query(), $this->allowednulls );
            

        } catch( \Exception $e ) {

            if( $e->getCode() == 23000 ) {

                return rest_response( Constants::IMPRINT_TYPE_EXIST, 422 );
                
            }

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }
}