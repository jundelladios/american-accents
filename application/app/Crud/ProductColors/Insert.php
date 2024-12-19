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

use Api\Models\ProductPrintMethodModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

use Api\Crud\PublicRoutes\Filters;

use Api\Crud\PublicRoutes\Images;

class Insert {

    use ControllerTraits;

    private $required = ['colorname', 'product_print_method_id'];

    private $nulls = ['colorhex', 'iscolorimage', 'colorimageurl', 'templates', 'isavailable', 'pantone', 'idea_galleries', 'image', 'vdsid'];

    private $statuses = ['priority', 'in_stock'];

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

            $exists = ProductColorsModel::where('slug', $data['slug'])
            ->where('product_print_method_id', $data['product_print_method_id'])
            ->first();

            if( $exists ) {

                return rest_response( 'Existing Product Color', 422 );

            }

            $product = (new Filters)->getProductName(array(
                'id' => $data['product_print_method_id']
            ));

            if( !$product ) {

                return rest_response( Constants::NOT_FOUND, 404 );
            }

            $filetitle = (new Images)->formattitle($product['product_method_combination_name'].'_'.$data['colorname']);

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

            return $this->updateOrPostHelper( new ProductColorsModel, $data, ProductColorsModel::query(), $this->nulls );
            

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }


    

}