<?php
/**
 * @package AA_Project
 * 
 * Insert Colors
 * 
 */

namespace Api\Crud\CollectionPremiumBackground;

use Api\Hasher;

use Api\Models\CollectionPremiumBackgroundsModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    private $required = ['title', 'collection'];

    private $statuses = ['active', 'priority'];

    public function store( $request ) {

        try {
            
            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                $this->statuses
            ));

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', $this->required);

            $validate->rule('jsonString', ['collection'], '/collections/collectionpremiumbackground.json');

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            return $this->updateOrPostHelper( new CollectionPremiumBackgroundsModel, $data, CollectionPremiumBackgroundsModel::query() );
            

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }
}