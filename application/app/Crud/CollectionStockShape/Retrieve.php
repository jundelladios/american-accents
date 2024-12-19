<?php
/**
 * @package AA_Project
 * 
 * Retrieve Colors
 * 
 */

namespace Api\Crud\CollectionStockShape;

use Api\Hasher;

use Api\Models\CollectionStockShapeModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $stockcollection = CollectionStockShapeModel::query();

            $stockcollection->select("*");

            if( isset( $request['id'] ) ) {

                $stockcollection->where( 'id', Hasher::decode( $request['id'] ) );

                if( !$stockcollection->first() ) {

                    return rest_response( Constants::NOT_FOUND, 404 );

                }

            }
            
            if( isset( $request['search'] ) ) {

                $stockcollection->where( 'title', 'like', '%' . $request['search'] . '%' );
            }

            return $this->getHelper( $stockcollection, $request );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}