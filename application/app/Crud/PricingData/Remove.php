<?php
/**
 * @package AA_Project
 * 
 * PRICING DATA REMOVE
 * 
 */

namespace Api\Crud\PricingData;

use Api\Hasher;

use Api\Models\PricingDataModel;

use Api\Models\PricingDataValueModel;

use Api\Constants;

class Remove {

    public function remove( $request ) {

        try {

            // child first for foreign key constraints.

            $pvalue = PricingDataValueModel::where( 'pricing_data_id', Hasher::decode( $request['id'] ) );

            if( $pvalue->get() ) {

                $pvalue->delete();

            }

            $pdata = PricingDataModel::find( Hasher::decode( $request['id'] ) );
            
            if( $pdata ) {

                $pdata->delete();

            }

            return "Removed";

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}