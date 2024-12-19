<?php
/**
 * @package AA_Project
 * 
 * PRICING DATA INSERT
 * 
 */

namespace Api\Crud\PricingData;

use Api\Hasher;

use Api\Models\PricingDataModel;

use Api\Models\ProductLinesModel;

use Api\Models\ChargesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Import {

    use ControllerTraits;

    private $required = ['subcategory', 'printmethod', 'priority', 'is_additional_spot', 'per_color', 'per_piece', 'per_side', 'per_thousand', 'auto_format', 'chargetype'];

    private $allowedNulls = [
        'pricing_data_sub',
        'note_value'
    ];

    public function import( $request ) {

        try {

            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required, 
                $this->allowedNulls
            ));

            $colfields = $data;

            unset($colfields['subcategory']);

            unset($colfields['printmethod']);

            unset($colfields['chargetype']);

            // Validation
             $validate = new Validator($data);

             $validate->rule('required', $this->required);
 
             if( !$validate->validate() ) {
 
                 return rest_response( $validate->errors(), 422 );
 
             }

            $productline = (new ProductLinesModel)->getInstance([
                'subcategoryname' => $request['subcategory'],
                'methodname' => $request['printmethod'],
                'fields' => 'print_methods.id as pmethodid'
            ])->first();

            if( !$productline ) { return false; }

            $colfields['product_line_id'] = $productline['id'];
            

            $chargeType = ChargesModel::firstOrNew(array(
                'charge_name' => $request['chargetype']
            ));

            $chargeType->save();

            $colfields['charge_type_id'] = $chargeType['id'];

            $imported = PricingDataModel::firstOrNew(array(
                'product_line_id' => $productline['id'],
                'charge_type_id' => $chargeType['id'],
            ));

            foreach( $colfields as $key => $inputs ) {

                $imported[$key] = $inputs;

            }

            $imported->save();

            return $imported;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}