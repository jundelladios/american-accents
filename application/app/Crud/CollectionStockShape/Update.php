<?php
/**
 * @package AA_Project
 * 
 * Update Colors
 * 
 */

namespace Api\Crud\CollectionStockShape;

use Api\Hasher;

use Api\Models\CollectionStockShapeModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Update {

    use ControllerTraits;


    private $required = ['id', 'title', 'collection'];

    private $statuses = ['active', 'priority'];

    public function update( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                $this->statuses
            ));

            $data['id'] =  Hasher::decode( $data['id'] );

            // Validation
            $validate = new Validator($data);

            $validate->rule('jsonString', ['collection'], '/collections/collectionstockshape.json');

            $this->_required( $validate, $data, $this->required);


            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $update = CollectionStockShapeModel::find( $data['id'] );

            if( !$update ) {

                return rest_response( CollectionStockShapeModel::NOT_FOUND, 404 );

            }
            
            return $this->updateOrPostHelper( $update, $data, CollectionStockShapeModel::query() );

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}