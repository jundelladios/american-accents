<?php

/**
 * INSERT SPECIFICATION TYPES
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

class Insert {

    use ControllerTraits;

    private $required = ['title'];

    private $allowedNulls = [
        'customfield', 
        // 'customfieldcombo', 
        'specs'
    ];

    private $statuses = ['priority', 'active', 'isspec'];

    public function store( $request ) {

        try {
            
            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required, 
                $this->allowedNulls,
                $this->statuses
            ));
    
            // Validation
            $validate = new Validator($data);
    
            $validate->rule('required', $this->required);

            $validate->rule('jsonString', ['customfield'], '/specification/form.json');

            // $validate->rule('jsonString', ['customfieldcombo'], '/specification/form.json');

            // $validate->rule('jsonString', ['specs'], '/specification/specification.json');
    
            if( !$validate->validate() ) {
    
                return rest_response( $validate->errors(), 422 );
    
            }
    
            return $this->updateOrPostHelper( new SpecificationTypesModel, $data, SpecificationTypesModel::query() );
            
    
        } catch( \Exception $e ) {
    
            return rest_response( Constants::BAD_REQUEST, 500 );
    
        }    

    }

    public function duplicate( $request ) {

        try {

            $data = rest_requests( $request->get_params(), array_merge( 
                array(
                    'id'
                )
            ));

            $validate = new Validator($data);
    
            $validate->rule('required', ['id']);

            if( !$validate->validate() ) {
    
                return rest_response( $validate->errors(), 422 );
    
            }

            $entry = SpecificationTypesModel::find( Hasher::decode( $data['id'] ) );

            if( !$entry ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            $newEntry = $entry->replicate();

            $newEntry->save();

            $newEntry->title = $newEntry->title . ' (Duplicate ' . $newEntry->id . ')'; 

            $newEntry->save();

            return $newEntry;


        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}