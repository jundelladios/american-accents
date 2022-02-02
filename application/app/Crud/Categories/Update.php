<?php

/**
 * UPDATE CATEGORIES
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AA_Project
 */

namespace Api\Crud\Categories;

use Api\Constants;

use Api\Traits\ControllerTraits;

use Api\Hasher;

use Api\Models\ProductCategoriesModel;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['cat_name', 'cat_slug'];

    private $allowedNulls = ['notes', 'category_banner', 'category_banner_content', 'seo_content'];
    
    private $statuses = ['priority', 'active', 'template_section'];

    public function update( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required, 
                $this->allowedNulls, 
                ['id'], 
                $this->statuses 
            ));

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', 'id');

            $this->_required( $validate, $data, $this->required);


            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $data['id'] =  Hasher::decode( $data['id'] );

            $update = ProductCategoriesModel::find( $data['id'] );

            if( !$update ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            if( $data['cat_slug'] && $data['cat_slug'] != $update['cat_slug'] ) {

                $data['cat_slug'] = (new ProductCategoriesModel)->slugHandler( $data['cat_slug'] );

            }
            
            return $this->updateOrPostHelper( $update, $data, ProductCategoriesModel::query(), $this->allowedNulls );

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}