<?php
/**
 * @package AA_Project
 * 
 * PRODUCT PRINT METHOD RETRIEVE
 * 
 */

namespace Api\Crud\ProductPrintMethod;

use Api\Models\ProductPrintMethodModel;

use Api\Hasher;

use Api\Traits\ControllerTraits;

use Api\Constants;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $combo = ProductPrintMethodModel::query();

            $combo->select("*");

            if( isset( $request['product_id'] ) ) {

                $combo->where( 'product_id', Hasher::decode( $request['product_id'] ) );

            }

            $combo->with(['product', 'productline' => function($query) {

                $query->with(['printmethod', 'pricingData', 'couponcode']);

            }]);

            $combo->with(['pricings']);

            return $this->getHelper( $combo, $request );

        } catch ( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}