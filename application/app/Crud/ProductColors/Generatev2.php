<?php
/**
 * @package AA_Project
 * 
 * Generate Colors
 * 
 */

namespace Api\Crud\ProductColors;

use Api\Hasher;

use Api\Models\ProductColorsModel;

use Api\Models\ProductPrintMethodModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

use Api\Crud\PublicRoutes\Filters;

use Api\Crud\PublicRoutes\Images;

class Generatev2 {

    use ControllerTraits;

    private $required = ['colorname', 'product_print_method_id'];

    private $nulls = ['colorhex', 'iscolorimage', 'colorimageurl', 'pantone'];

    private $statuses = ['priority'];

    public function generate( $request ) {

        try {

            $params = $request->get_params();
            
            // allowed requests
            $data = rest_requests( $params, array_merge( 
                $this->required,
                $this->statuses,
                $this->nulls
            ));

            $data['product_print_method_id'] = Hasher::decode( $data['product_print_method_id'] );

            $data['slug'] = rest_slug_generator( $data['colorname'] );

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

            $filetitle = (new Images)->formattitle($product['product_method_combination_name'].'_'.$data['colorname']);
            
            // image
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


            $thepcolor = ProductColorsModel::firstOrNew(array(
                'colorname' => $data['colorname'],
                'product_print_method_id' => $data['product_print_method_id']
            ));

            foreach( $data as $key => $inputs ) {

                $thepcolor[$key] = $inputs;

            }


            $thepcolor->save();

            return $thepcolor;
            

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }


    

}