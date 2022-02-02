<?php

/**
 * Charges INSERT
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AA_Project
 */

namespace Api\Crud\Charges;

use Api\Constants;

use Api\Traits\ControllerTraits;

use Api\Hasher;

use Api\Models\ChargesModel;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    private $required = ['charge_name', 'icon'];

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

            return $this->updateOrPostHelper( new ChargesModel, $data, ChargesModel::query() );
            

        } catch( \Exception $e ) {

            if( $e->getCode() == 23000 ) {

                return rest_response( "Charge Name already exists.", 422 );
                
            }

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}