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

use Api\Crud\PublicRoutes\Filters;

use Api\Crud\PublicRoutes\Images;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Generate {

    use ControllerTraits;

    private $required = ['product_print_method_id'];

    public function generate( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                ['overwrite']
            ));

            $data['product_print_method_id'] = Hasher::decode( $data['product_print_method_id'] );

            $colors = ProductColorsModel::where('product_print_method_id', $data['product_print_method_id'])->orderBy('priority')->get();

            $shapes = ProductStockShapesModel::where('product_print_method_id', $data['product_print_method_id'])->orderBy('priority')->get();

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
                
                return rest_response( 'Product not Found.', 404 );
            }

            $instance = ProductColorAndStockShapeModel::where('product_print_method_id', $data['product_print_method_id'])->orderBy('priority');

            $instance->delete();

            $priority = 0;

            $rets = [];

            foreach( $colors as $color ):

                foreach( $shapes as $shape ):

                    $arr = [];

                    $priority++;

                    $arr['product_print_method_id'] = $data['product_print_method_id'];
                    $arr['slug'] = rest_slug_generator( $color['colorname'] . '-' . $shape['code']);
                    $arr['product_color_id'] = $color->id;
                    $arr['product_stockshape_id'] = $shape->id;
                    $arr['priority'] = $priority;
                    
                    // auto assign starts here
                    $filetitle = (new Images)->formattitle($product['product_method_combination_name'] . '_' . $color['colorname'] . '_' . $shape['code']);

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

                    //$colorshape->save();

                    $rets[] = $arr;

                endforeach;

            endforeach;

            ProductColorAndStockShapeModel::insert($rets);
            
            return true;

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}