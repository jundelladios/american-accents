<?php
/**
 * @package AA_Project
 * 
 * SUBCATEGORIES INSERT
 * 
 */

namespace Api\Crud\Subcategories;

use Api\Models\ProductSubcategoriesModel;

use Api\Hasher;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    private $required = ['sub_name', 'sub_slug'];

    private $allowedNulls = ['sub_description', 'seo_content', 'categorize_as', 'priority', 'catalogs', 'sub_name_alt'];

    private $postRequired = ['product_category_id'];

    private $statuses = ['active', 'priority'];

    public function store( $request ) {

        try {
            
            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required,
                $this->allowedNulls,
                $this->postRequired,
                $this->statuses,
                ['bannerlist']
            ));

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', array_merge( 
                $this->required, 
                $this->postRequired
            ));

            $validate->rule('jsonString', ['catalogs'], '/subcategorycatalog.json');

            $validate->rule('jsonString', ['bannerlist'], '/bannerlist.json');

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }


            $data['sub_slug'] = (new ProductSubcategoriesModel)->slugHandler( $data['sub_slug'] );

            $data['product_category_id'] = Hasher::decode( $data['product_category_id'] );
            
            return $this->updateOrPostHelper( new ProductSubcategoriesModel, $data, ProductSubcategoriesModel::query() );

        } catch( \Exception $e ) {

            if( $e->getCode() == 23000 ) {

                return rest_response( Constants::STORE_SLUG_ERROR, 422 );

            }

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}