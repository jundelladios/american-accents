<?php

/**
 * CLIP ARTS UPDATE
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AA_Project
 */

namespace Api\Crud\ClipArts;

use Api\Constants;

use Api\Traits\ControllerTraits;

use Api\Hasher;

use Api\Models\ClipArtsModel;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['clipartcategory'];

    private $statuses = ['priority', 'active'];

    public function update( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                ['id', 'clipartdata'],
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

            $update = ClipArtsModel::find( $data['id'] );

            if( !$update ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }
            
            return $this->updateOrPostHelper( $update, $data, ClipArtsModel::query() );

        } catch( \Exception $e ) {


            if( $e->getCode() == 23000 ) {

                return rest_response( "Clip art category already exists.", 422 );

            }

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}