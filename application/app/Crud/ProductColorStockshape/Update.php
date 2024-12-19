<?php
/**
 * @package AA_Project
 * 
 * Retrieve Colors
 * 
 */

namespace Api\Crud\ProductColorStockshape;

use Api\Hasher;

use Api\Models\ProductColorAndStockShapeModel;

use Api\Models\ProductColorsModel;

use Api\Models\ProductStockShapesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['product_stockshape_id', 'product_color_id', 'id'];

    private $nulls = ['templates', 'idea_galleries', 'vdsid', 'vdsproductid', 'image'];

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

            $data['product_stockshape_id'] = Hasher::decode( $data['product_stockshape_id'] );

            $data['product_color_id'] = Hasher::decode( $data['product_color_id'] );

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', $this->required);

            $validate->rule('jsonString', ['idea_galleries'], '/product/productideagalleries.json');

            $validate->rule('jsonString', ['image'], '/product/productimages.json');

            $validate->rule('jsonString', ['templates'], '/product/producttemplates.json');

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $update = ProductColorAndStockShapeModel::find( $data['id'] );

            $color = ProductColorsModel::where('id', $data['product_color_id'])
            ->where('product_print_method_id', $update['product_print_method_id'])
            ->first();

            $shape = ProductStockShapesModel::where('id', $data['product_stockshape_id'])
            ->where('product_print_method_id', $update['product_print_method_id'])
            ->first();

            if( !$color ) {

                return rest_response( 'Invalid Color ID', 422 );

            }

            if( !$shape ) {

                return rest_response( 'Invalid Stock Shape ID', 422 );

            }

            $data['slug'] = rest_slug_generator( $color['colorname'] . '-' . $shape['code']);

            $exists = ProductColorAndStockShapeModel::where('product_stockshape_id', $data['product_stockshape_id'])
            ->where('id', '!=', $data['id'])
            ->where('product_print_method_id', $update['product_print_method_id'])
            ->where('product_color_id', $update['product_color_id'])
            ->where('slug', $data['slug'])
            ->first();

            if( $exists  ) {

                $exists->delete();

            }

            if( !$update ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            return $this->updateOrPostHelper( $update, $data, ProductColorAndStockShapeModel::query(), $this->nulls );
            

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}