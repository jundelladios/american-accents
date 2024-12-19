<?php
/**
 * @package AA_Project
 * 
 * REMOVE PRICING DATA VALUES
 * 
 */

namespace Api\Crud\PricingDataValues;

use Api\Hasher;

use Api\Models\PricingDataValueModel;

use Api\Constants;

class Remove {

    public function remove( $request ) {

        try {

            $pvalue = PricingDataValueModel::find( Hasher::decode( $request['id'] ) );

            if( !$pvalue ) {
    
                return rest_response( Constants::NOT_FOUND, 404 );
    
            }

            $pvalue->delete();

            if( isset( $request['showMinMax'] ) ) {

                return [
                    'min' => PricingDataValueModel::where( 'product_print_method_id', $pvalue->product_print_method_id )->min( 'value' ),
                    'max' => PricingDataValueModel::where( 'product_print_method_id', $pvalue->product_print_method_id )->max( 'value' )
                ];

            }

            return "Removed";

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}