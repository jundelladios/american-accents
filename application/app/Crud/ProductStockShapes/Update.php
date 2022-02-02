<?php
/**
 * @package AA_Project
 * 
 * Retrieve Colors
 * 
 */

namespace Api\Crud\ProductStockShapes;

use Api\Hasher;

use Api\Models\ProductStockShapesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['code', 'id', 'image'];

    private $nulls = ['templates', 'pantone', 'idea_galleries', 'stockname', 'vdsid'];

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

            $data['slug'] = rest_slug_generator( $data['stockname'] . '-' . $data['code'] );

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', $this->required);

            $validate->rule('jsonString', ['idea_galleries'], '/product/productideagalleries.json');

            $validate->rule('jsonString', ['image'], '/product/productimages.json');

            $validate->rule('jsonString', ['templates'], '/product/producttemplates.json');

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $update = ProductStockShapesModel::find( $data['id'] );

            $exists = ProductStockShapesModel::where('slug', $data['slug'])
            ->where('product_print_method_id', $update['product_print_method_id'])
            ->where('id', '!=', $data['id'])
            ->first();

            if( $exists  ) {

                return rest_response( 'Existing Product Stockshape', 422 );

            }

            if( !$update ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            return $this->updateOrPostHelper( $update, $data, ProductStockShapesModel::query(), $this->nulls );
            

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}