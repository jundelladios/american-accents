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

use Api\Models\PricingDataModel;

use Api\Models\ProductLinesModel;

use Api\Models\ChargesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Import {

    use ControllerTraits;

    private $required = ['quantity', 'asterisk', 'decimal_value'];

    private $allowedNulls = ['value', 'alternative_value', 'unit_value'];


    public function productcombo( $request ) {

        try {

            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                $this->allowedNulls
            ));
    
            $validate = new Validator($data);
    
            $this->_required( $validate, $data, array_merge(
                $this->required
            ));

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $productcombo = (new \Api\Crud\PublicRoutes\Filters)->getProductName([
                'combination_name' => $request['product_method_combination_name']
            ]);

            if( !$productcombo ) {

                return rest_response( Constants::NOT_FOUND, 404 );
                
            }


            $data['product_print_method_id'] = $productcombo['id'];

            $imported = PricingDataValueModel::firstOrNew(array(
                'product_print_method_id' => $productcombo['id'],
                'quantity' => $data['quantity']
            ));

            $ret = $this->updateOrPostHelper( $imported, $data, PricingDataValueModel::query(), $this->allowedNulls );

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



    public function productline( $request ) {

        try {

            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                $this->allowedNulls,
                ['subcategory', 'printmethod', 'chargetype']
            ));

            $colfields = $data;

            unset($colfields['subcategory']);

            unset($colfields['printmethod']);

            unset($colfields['chargetype']);

            $validate = new Validator($data);

            $validate->rule('required', array_merge(
                $this->required,
                ['subcategory', 'printmethod', 'chargetype']
            ));

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $productline = (new ProductLinesModel)->getInstance([
                'subcategoryname' => $request['subcategory'],
                'methodname' => $request['printmethod'],
                'fields' => 'print_methods.id as pmethodid'
            ])->first();

            if( !$productline ) { return false; }

            $chargeType = ChargesModel::firstOrNew(array(
                'charge_name' => $request['chargetype']
            ));

            $chargeType->save();


            $pricingdata = PricingDataModel::firstOrNew(array(
                'product_line_id' => $productline['id'],
                'charge_type_id' => $chargeType['id'],
            ));

            $pricingdata->save();

            $colfields['pricing_data_id'] = $pricingdata['id'];

            $imported = PricingDataValueModel::firstOrNew(array(
                'pricing_data_id' => $pricingdata['id'],
                'quantity' => $request['quantity']
            ));

            foreach( $colfields as $key => $inputs ) {

                $imported[$key] = $inputs;

            }

            $imported->save();

            return $imported;


        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}