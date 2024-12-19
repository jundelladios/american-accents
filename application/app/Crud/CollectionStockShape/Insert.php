<?php
/**
 * @package AA_Project
 * 
 * Insert Colors
 * 
 */

namespace Api\Crud\CollectionStockShape;

use Api\Hasher;

use Api\Models\CollectionStockShapeModel;

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

            $validate->rule('jsonString', ['collection'], '/collections/collectionstockshape.json');

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            return $this->updateOrPostHelper( new CollectionStockShapeModel, $data, CollectionStockShapeModel::query() );
            

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }
}