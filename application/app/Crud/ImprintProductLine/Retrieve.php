<?php
/**
 * @package AA_Project
 * 
 * Retrieve Imprint Type
 * 
 */

namespace Api\Crud\ImprintProductLine;

use Api\Hasher;

use Api\Models\ImprintTypeProductLineModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $imprintpline = ImprintTypeProductLineModel::query();

            $imprintpline->select("*");

            if( isset( $request['productline_id'] ) ) {

                $imprintpline->where( 'productline_id', Hasher::decode( $request['productline_id'] ) );

            }

            $imprintpline->with(['imprinttype']);

            return $this->getHelper( $imprintpline, $request, false );

        } catch( \Exception $e ) { return $e->getMessage();
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}