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

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    private $required = ['product_line_id', 'charge_type_id'];

    private $allowedNulls = [
        'priority',
        'is_additional_spot',
        'per_color',
        'per_piece',
        'per_side',
        'per_thousand',
        'note_value',
        'auto_format',
        'spot_color_value',
        'per_color_value',
        'per_piece_value',
        'per_side_value',
        'per_thousand_value'
    ];

    public function store( $request ) {

        try {

            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required, 
                $this->allowedNulls
            ));

            $data['product_line_id'] = Hasher::decode( $data['product_line_id'] );

            $data['charge_type_id'] = Hasher::decode( $data['charge_type_id'] );

            $validate = new Validator($data);
    
            $this->_required( $validate, $data, array_merge(
                $this->required
            ));
    
            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $ret = PricingDataModel::query();

            $ret->with(['chargetypes']);
    
            return $this->updateOrPostHelper( new PricingDataModel, $data, $ret );

        } catch( \Exception $e ) {

            return $e->getMessage();
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}