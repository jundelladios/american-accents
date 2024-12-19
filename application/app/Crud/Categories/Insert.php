<?php

/**
 * INSERT CATEGORIES
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

class Insert {

    use ControllerTraits;

    private $required = ['cat_name', 'cat_slug'];

    private $allowedNulls = ['notes', 'category_banner', 'category_banner_content', 'seo_content'];

    public function store( $request ) {

        try {
            
            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required, 
                $this->allowedNulls,
                ['template_section', 'bannerlist']
            ));
    
            // Validation
            $validate = new Validator($data);
    
            $validate->rule('required', $this->required);
    
            $validate->rule('jsonString', ['bannerlist'], '/bannerlist.json');

            if( !$validate->validate() ) {
    
                return rest_response( $validate->errors(), 422 );
    
            }

            $data['cat_slug'] = (new ProductCategoriesModel)->slugHandler( $data['cat_slug'] );
    
            return $this->updateOrPostHelper( new ProductCategoriesModel, $data, ProductCategoriesModel::query() );
            
    
        } catch( \Exception $e ) {
    
            if( $e->getCode() == 23000 ) {
    
                return rest_response( Constants::STORE_SLUG_ERROR, 422 );
                
            }
    
            return rest_response( Constants::BAD_REQUEST, 500 );
    
        }    

    }

}