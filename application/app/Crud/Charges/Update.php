<?php

/**
 * CLIP ARTS UPDATE
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

class Update {

    use ControllerTraits;

    private $required = ['charge_name', 'icon'];

    private $statuses = ['active'];

    public function update( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                ['id'],
                $this->statuses
            ));

            $data['id'] =  Hasher::decode( $data['id'] );

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', 'id');

            $this->_required( $validate, $data, $this->required);


            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $update = ChargesModel::find( $data['id'] );

            if( !$update ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }
            
            return $this->updateOrPostHelper( $update, $data, ChargesModel::query() );

        } catch( \Exception $e ) {


            if( $e->getCode() == 23000 ) {

                return rest_response( "Charge name already exists.", 422 );

            }

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}