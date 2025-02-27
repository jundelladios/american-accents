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

class Generatev2 {

    use ControllerTraits;

    private $required = ['product_print_method_id', 'product_color_id', 'product_stockshape_id'];

    public function generate( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required
            ));

            $data['product_print_method_id'] = Hasher::decode( $data['product_print_method_id'] );

            $data['product_color_id'] = Hasher::decode( $data['product_color_id'] );

            $data['product_stockshape_id'] = Hasher::decode( $data['product_stockshape_id'] );

            $thecolor = ProductColorsModel::where('product_print_method_id', $data['product_print_method_id'])
            ->where('id', $data['product_color_id'])
            ->first();


            $thestockshape = ProductStockShapesModel::where('product_print_method_id', $data['product_print_method_id'])
            ->where('id', $data['product_stockshape_id'])
            ->first();
            

            $product = (new Filters)->getProductName(array(
                'id' => $data['product_print_method_id']
            ));


            if( $thecolor && $thestockshape && $product ) {

                $data['slug'] = rest_slug_generator( $thecolor['colorname'] . '-' . $thestockshape['code']);

                // auto assign starts here
                $filetitle = (new Images)->formattitle($product['product_method_combination_name'] . '_' . $thecolor['colorname'] . '_' . $thestockshape['code']);

                // auto assign main images
                $imageSet = (new Images)->get(array(
                    'options' => array(
                        $filetitle,
                        $filetitle.'_main.*'
                    )
                ), "[(.jpg)(.jpeg)(.gif)(.png)(.html)]$");

                $imgs = [];

                foreach( $imageSet as $img ):
                    $mimetype = wp_check_filetype($img->meta_file)['type'];
                    $imgpush = array(
                        'image' => $img->meta_file,
                        'title' => get_the_title( $img->post_id ),
                    );
                    if(!str_contains( $mimetype, "image" )) {
                        $imgpush['type'] = $mimetype;
                    }
                    $imgs[] = $imgpush;
                endforeach;

                $data['image'] = json_encode( $imgs );
                // ============ END OF AUTO ASSIGN IMAGE ================


                // auto assign idea
                $ideaSet = (new Images)->get(array(
                    'options' => array(
                        $filetitle . '_ig.*'
                    )
                ), "[(.jpg)(.jpeg)(.gif)(.png)(.html)]$");

                $idea = [];

                foreach( $ideaSet as $img ):
                    $mimetype = wp_check_filetype($img->meta_file)['type'];
                    $ideapush = array(
                        'image' => $img->meta_file,
                        'text' => get_the_title( $img->post_id ),
                        'downloadLink' => '',
                        'usecurfile' => 1,
                    );
                    if(!str_contains( $mimetype, "image" )) {
                        $ideapush['type'] = $mimetype;
                        $ideapush['usecurfile'] = 0;
                    }
                    $idea[] = $ideapush;
                endforeach;

                $data['idea_galleries'] = json_encode( $idea );
                // =========== END OF AUTO ASSIGN IDEA ======================



                // auto assign template
                $templateSet = (new Images)->get(array(
                    'options' => array(
                        $filetitle . '_template.*'
                    )
                ), '[(.pdf)]$');

                $templates = [];
                
                foreach( $templateSet as $tmpdata ):
                    $imgurl = wp_get_attachment_image_url( $tmpdata->post_id, 'full' );
                    $templates[] = array(
                        'link' => $tmpdata->meta_file,
                        'preview' => $imgurl,
                        'title' => get_the_title( $tmpdata->post_id )
                    );
                endforeach;

                $data['templates'] = json_encode( $templates );
                // =========== END OF AUTO ASSIGN TEMPLATES ======================


                $thecolorstockshape = ProductColorAndStockShapeModel::firstOrNew(array(
                    'product_color_id' => $data['product_color_id'],
                    'product_stockshape_id' => $data['product_stockshape_id'],
                    'product_print_method_id' => $data['product_print_method_id']
                ));

                foreach( $data as $key => $inputs ) {

                    $thecolorstockshape[$key] = $inputs;
    
                }
    
    
                $thecolorstockshape->save();

                return $thecolorstockshape;

            }

            return false;

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }

}