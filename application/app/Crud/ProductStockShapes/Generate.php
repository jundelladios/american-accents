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

use Api\Models\CollectionStockShapeModel;

use Api\Models\ProductPrintMethodModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

use Api\Crud\PublicRoutes\Filters;

use Api\Crud\PublicRoutes\Images;

class Generate {

    use ControllerTraits;

    private $required = ['collection_id', 'product_print_method_id'];

    public function generate( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                ['overwrite']
            ));

            $data['collection_id'] = Hasher::decode( $data['collection_id'] );

            $data['product_print_method_id'] = Hasher::decode( $data['product_print_method_id'] );

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', $this->required);

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            // validate if product print method exists
            $product = (new Filters)->getProductName(array(
                'id' => $data['product_print_method_id']
            ));

            if( !$product ) {

                return rest_response( Constants::NOT_FOUND, 404 );
            }

            $getCollection = CollectionStockShapeModel::where('id', $data['collection_id'])->first();

            if( !$getCollection ) {

                return rest_response( 'Stock Shape Collection not Found.', 404 );

            }

            $instance = ProductStockShapesModel::where('product_print_method_id', $data['product_print_method_id']);

            if( isset( $data['overwrite'] ) ) {

                $instance->delete();

            }

            $rets = [];

            foreach( $getCollection['collections'] as $collection ):

                $arr = [];
                $arr['stockimage'] = $collection->image;
                $arr['code'] = $collection->code;
                $arr['stockname'] = $collection->stockname;
                $arr['slug'] = rest_slug_generator( $collection->stockname . '-' . $collection->code );
                $arr['product_print_method_id'] = $data['product_print_method_id'];

                // auto assign starts here
                $filetitle = (new Images)->formattitle($product['product_method_combination_name'] . '_' . $collection->code);

                // auto assign main images
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

                $arr['image'] = json_encode( $imgs );
                // ============ END OF AUTO ASSIGN IMAGE ================


                // auto assign idea
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

                $arr['idea_galleries'] = json_encode( $idea );
                // =========== END OF AUTO ASSIGN IDEA ======================

                $rets[] = $arr;

            endforeach;

            ProductStockShapesModel::insert($rets);

            return true;
            
        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}