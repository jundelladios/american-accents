<?php
/**
 * @package AA_Project
 * 
 * UPDATE PRICING DATA VALUES
 * 
 */

namespace Api\Crud\PricingDataValues;

use Api\Hasher;

use Api\Models\PricingDataValueModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['quantity'];

    private $allowedNulls = ['value', 'alternative_value', 'product_print_method_id', 'unit_value', 'show_currency'];

    public function update( $request ) {

        try {

            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                ['value'],
                ['id', 'asterisk', 'decimal_value'],
                $this->allowedNulls
            ));

            if( $data['product_print_method_id'] ) {

                $data['product_print_method_id'] = Hasher::decode($data['product_print_method_id']);

            }
    
            $validate = new Validator($data);
    
            $this->_required( $validate, $data, array_merge(
                $this->required
            ));

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $data['id'] = Hasher::decode( $data['id'] );


            $update = PricingDataValueModel::find( $data['id'] );


            if( !$update ) {
        
                return rest_response( Constants::NOT_FOUND, 404 );
    
            }
            
            $ret = $this->updateOrPostHelper( $update, $data, PricingDataValueModel::query(), $this->allowedNulls );

            if( isset( $request['product_print_method_id'] ) ) {

                $ret->priceMinMax = [
                    'min' => PricingDataValueModel::where( 'product_print_method_id', $ret->product_print_method_id )->min( 'value' ),
                    'max' => PricingDataValueModel::where( 'product_print_method_id', $ret->product_print_method_id )->max( 'value' )
                ];

            }

            return $ret;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}