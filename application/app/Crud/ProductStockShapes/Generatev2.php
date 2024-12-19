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

class Generatev2 {

    use ControllerTraits;

    private $required = ['code', 'product_print_method_id'];

    private $nulls = ['stockname'];

    public function generate( $request ) {

        try {

            $params = $request->get_params();
            
            // allowed requests
            $data = rest_requests( $params, array_merge( 
                $this->required,
                $this->nulls
            ));

            $data['product_print_method_id'] = Hasher::decode( $data['product_print_method_id'] );

            $nameslugext = $data['stockname'] ? '-'.$data['stockname'] : '';

            $data['slug'] = rest_slug_generator( $data['code'] . $nameslugext );

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', $this->required);

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $product = (new Filters)->getProductName(array(
                'id' => $data['product_print_method_id']
            ));

            if( !$product ) {

                return rest_response( Constants::NOT_FOUND, 404 );
            }

            $filetitle = (new Images)->formattitle($product['product_method_combination_name'] . '_' . $data['code']);

            // image set
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

            
            // idea gallery
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


            $thepshape = ProductStockShapesModel::firstOrNew(array(
                'stockname' => $data['stockname'],
                'code' => $data['code'],
                'product_print_method_id' => $data['product_print_method_id']
            ));

            foreach( $data as $key => $inputs ) {

                $thepshape[$key] = $inputs;

            }


            $thepshape->save();

            return $thepshape;
            

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}