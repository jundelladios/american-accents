<?php
/**
 * @package AA_Project
 * 
 * Insert Imprint Type
 * 
 */

namespace Api\Crud\ImprintProductLine;

use Api\Hasher;

use Api\Models\ImprintTypeProductLineModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    private $required = ['imprint_type_id', 'productline_id', 'image'];

    private $statuses = ['priority'];

    private $allowednulls = ['min_prod_days', 'imprint_charge', 'show_currency', 'decimal_value'];

    public function store( $request ) {

        try {
            
            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                $this->statuses,
                $this->allowednulls
            ));


            $data['imprint_type_id'] = Hasher::decode( $data['imprint_type_id'] );

            $data['productline_id'] = Hasher::decode( $data['productline_id'] );

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', $this->required);

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $ret = ImprintTypeProductLineModel::query();

            $ret->with(['imprinttype']);

            return $this->updateOrPostHelper( new ImprintTypeProductLineModel, $data, $ret, $this->allowednulls );
            

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }
}