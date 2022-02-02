<?php
/**
 * @package AA_Project
 * 
 * SUBCATEGORIES UPDATE
 * 
 */

namespace Api\Crud\Subcategories;

use Api\Models\ProductSubcategoriesModel;

use Api\Hasher;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['sub_name', 'sub_slug'];

    private $allowedNulls = ['sub_description', 'seo_content', 'categorize_as', 'catalogs', 'sub_name_alt'];

    private $statuses = ['active', 'priority'];

    public function update( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge(
                $this->required,
                $this->allowedNulls,
                $this->statuses,
                ['id']
            ));

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', 'id');

            $validate->rule('jsonString', ['catalogs'], '/subcategorycatalog.json');

            $this->_required( $validate, $data, $this->required);

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $data['id'] =  Hasher::decode( $data['id'] );

            $update = ProductSubcategoriesModel::find( $data['id'] );

            if( !$update ) {
                
                return rest_response( Constants::NOT_FOUND, 404 );

            }

            if( $data['sub_slug'] && $data['sub_slug'] != $update['sub_slug'] ) {

                $data['sub_slug'] = (new ProductSubcategoriesModel)->slugHandler( $data['sub_slug'] );
                
            }

            return $this->updateOrPostHelper( $update, $data, ProductSubcategoriesModel::query(), $this->allowedNulls );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}