<?php
/**
 * @package AA_Project
 * 
 * Retrieve Colors
 * 
 */

namespace Api\Crud\ProductColors;

use Api\Hasher;

use Api\Models\ProductColorsModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['colorhex', 'colorname', 'id', 'image'];

    private $nulls = ['colorhex', 'iscolorimage', 'colorimageurl', 'templates', 'isavailable', 'pantone', 'idea_galleries', 'vdsid'];

    private $statuses = ['priority'];

    public function update( $request ) {

        try {
            
            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                $this->statuses,
                $this->nulls
            ));

            $data['id'] =  Hasher::decode( $data['id'] );

            $data['slug'] = rest_slug_generator( $data['colorname'] );

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', $this->required);

            $validate->rule('jsonString', ['idea_galleries'], '/product/productideagalleries.json');

            $validate->rule('jsonString', ['image'], '/product/productimages.json');

            $validate->rule('jsonString', ['templates'], '/product/producttemplates.json');

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $update = ProductColorsModel::find( $data['id'] );

            $exists = ProductColorsModel::where('slug', $data['slug'])
            ->where('id', '!=', $data['id'])
            ->where('product_print_method_id', $update['product_print_method_id'])
            ->first();

            if( $exists  ) {

                return rest_response( 'Existing Product Color', 422 );

            }

            if( !$update ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            return $this->updateOrPostHelper( $update, $data, ProductColorsModel::query(), $this->nulls );
            

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}