<?php
/**
 * @package AA_Project
 * 
 * PRODUCT LINES INSERT
 * 
 */

namespace Api\Crud\ProductLines;

use Api\Hasher;

use Api\Models\ProductLinesModel;

use Api\Models\CouponsModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Import {

    use ControllerTraits;

    private $required = ['subcategory', 'printmethod', 'second_side', 'wrap', 'multicolor', 'per_thousand', 'per_item', 'setup_charge'];

    private $optfields = [
        'image', 
        'banner_img', 
        'price_tagline', 
        'compliances',
        'pnotes2', 
        'pnotes', 
        'features_pivot', 
        'second_side', 
        'wrap', 
        'multicolor', 
        'per_thousand', 
        'per_item', 
        'setup_charge'
    ];

    public function import( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required
            ));

            $colfields = rest_requests( $request->get_params(), array_merge( 
                $this->optfields
            ));

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

             $colfields['product_subcategory_id'] = $productline['subcategoryID'];

             $colfields['print_method_id'] = $productline['pmethodid'];

            $imported = ProductLinesModel::firstOrNew(array(
                'product_subcategory_id' => $productline['subcategoryID'],
                'print_method_id' => $productline['pmethodid'],
            ));


            if( isset( $request['coupon'] ) && !empty( $request['coupon'] ) ) {

                $coupon = CouponsModel::select('id')->where('code', $request['coupon'])->first();

                $colfields['coupon_code_id'] = $coupon->id;

            }

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