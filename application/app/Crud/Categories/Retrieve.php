<?php

/**
 * RETRIEVE CATEGORIES
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

use Api\Collection;

class Retrieve {

    use ControllerTraits;

    public function get( $request ) {

        try {

            $categories = ProductCategoriesModel::query();
    
            $categories->select("*");
    
            if( isset( $request['slug'] ) ) {
    
                $categories->where( 'cat_slug', $request['slug'] );
    
                if( !$categories->first() ) {
    
                    return rest_response( Constants::NOT_FOUND, 404 );
    
                }
    
            }
    
            if( isset( $request['id'] ) ) {
    
                $categories->where( 'id', Hasher::decode( $request['id'] ) );
    
                if( !$categories->first() ) {
    
                    return rest_response( Constants::NOT_FOUND, 404 );
    
                }
    
            }

            if( isset( $request['strictID'] ) ) {

                $categories->where( 'id', Hasher::decode( $request['strictID'] ) );

                $categories->active();
    
                if( !$categories->first() ) {
    
                    return rest_response( Constants::NOT_FOUND, 404 );
    
                }

            }
    
            if( isset( $request['tree'] ) ) {
    
                return Collection::toJson($categories->with(['subcategories' => function( $query ) use ( $request ) {
    
                    if( isset( $request['subcatId'] ) ) {
    
                        $query->where( 'id', Hasher::decode( $request['subcatId'] ) );
    
                    }

                    if( isset( $request['orderByTree'] ) ) {

                        $query->orderByRaw( $request['orderByTree'] );

                    }
                    
                    $query->active();
    
                }])->get());
    
            }


            if( isset( $request['template_section'] ) ) {

                $categories->where('template_section', (int) $request['template_section']);

            }
            
            if( isset( $request['search'] ) ) {
    
                $categories->where( 'cat_name', 'like', '%' . $request['search'] . '%' );
            }
    
            return $this->getHelper( $categories, $request );
    
        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );
    
        }

    }

}