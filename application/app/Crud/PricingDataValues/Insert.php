<?php
/**
 * @package AA_Project
 * 
 * INSERT PRICING DATA VALUES
 * 
 */

namespace Api\Crud\PricingDataValues;

use Api\Hasher;

use Api\Models\PricingDataValueModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    private $required = ['quantity'];

    private $allowedNulls = ['value', 'product_print_method_id', 'pricing_data_id', 'asterisk', 'alternative_value', 'unit_value', 'decimal_value', 'show_currency'];

    public function store( $request ) {

        try {

            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required, 
                $this->allowedNulls
            ));
    
            if( isset( $request['product_print_method_id'] ) ) {
    
                $data['product_print_method_id'] = Hasher::decode( $data['product_print_method_id'] );
    
            }
    
            if( isset( $request['pricing_data_id'] ) ) {
    
                $data['pricing_data_id'] = Hasher::decode( $data['pricing_data_id'] );
                
            }
    
            $validate = new Validator($data);
    
            $this->_required( $validate, $data, array_merge(
                $this->required
            ));
    
            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }
    
            $ret =  $this->updateOrPostHelper( new PricingDataValueModel, $data, PricingDataValueModel::query() );

            if( isset( $request['product_print_method_id'] ) ) {

                $ret->priceMinMax = [
                    'min' => PricingDataValueModel::where( 'product_print_method_id', $ret->product_print_method_id )->min( 'value' ),
                    'max' => PricingDataValueModel::where( 'product_print_method_id', $ret->product_print_method_id )->max( 'value' )
                ];

            }

            return $ret;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}