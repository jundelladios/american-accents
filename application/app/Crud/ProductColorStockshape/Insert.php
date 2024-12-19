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

use Api\Crud\PublicRoutes\Filters;

use Api\Crud\PublicRoutes\Images;

class Insert {

    use ControllerTraits;

    private $required = ['product_stockshape_id', 'product_print_method_id', 'product_color_id'];

    private $nulls = ['templates', 'idea_galleries', 'image', 'vdsid'];

    private $statuses = ['priority'];

    public function store( $request ) {

        try {

            $params = $request->get_params();
            
            // allowed requests
            $data = rest_requests( $params, array_merge( 
                $this->required,
                $this->statuses,
                $this->nulls
            ));

            $data['product_print_method_id'] = Hasher::decode( $data['product_print_method_id'] );

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

            $color = ProductColorsModel::where('id', $data['product_color_id'])
            ->where('product_print_method_id', $data['product_print_method_id'])
            ->first();

            $shape = ProductStockShapesModel::where('id', $data['product_stockshape_id'])
            ->where('product_print_method_id', $data['product_print_method_id'])
            ->first();

            if( !$color ) {

                return rest_response( 'Invalid Color ID', 422 );

            }

            if( !$shape ) {

                return rest_response( 'Invalid Stock Shape ID', 422 );

            }

            $data['slug'] = rest_slug_generator( $color['colorname'] . '-' . $shape['code']);


            $exists = ProductColorAndStockShapeModel::where('product_print_method_id', $data['product_print_method_id'])->where('slug', $data['slug'])->first();

            if( $exists ) {

                return rest_response( 'Existing Product Color + Shape', 422 );

            }

            $product = (new Filters)->getProductName(array(
                'id' => $data['product_print_method_id']
            ));

            if( !$product ) {

                return rest_response( Constants::NOT_FOUND, 404 );
            }

            $filetitle = (new Images)->formattitle($product['product_method_combination_name'] . '_' . $color['colorname'] . '_' . $shape['code']);

            if( isset( $params['autoassignimg'] ) ) {

                $imageSet = (new Images)->get(array(
                    'options' => array(
                        $filetitle,
                        $filetitle.'_main.*'
                    )
                ));

                $imgs = [];

                foreach( $imageSet as $img ):
                    $imgs[] = array(
                        'image' => $img->meta_file,
                        'title' => get_the_title( $img->post_id )
                    );
                endforeach;

                $data['image'] = json_encode( $imgs );

            }

            if( isset( $params['autoassignidea'] ) ) {

                $ideaSet = (new Images)->get(array(
                    'options' => array(
                        $filetitle . '_ig.*'
                    )
                ));

                $idea = [];

                foreach( $ideaSet as $img ):
                    $idea[] = array(
                        'image' => $img->meta_file,
                        'text' => get_the_title( $img->post_id ),
                        'downloadLink' => '',
                        'usecurfile' => 1
                    );
                endforeach;

                $data['idea_galleries'] = json_encode( $idea );

            }

            return $this->updateOrPostHelper( new ProductColorAndStockShapeModel, $data, ProductColorAndStockShapeModel::query(), $this->nulls );
            

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}