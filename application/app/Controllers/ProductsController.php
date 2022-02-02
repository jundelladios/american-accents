<?php
/**
 * @package AA_Project
 * 
 * Products Controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\Products\Insert;

use Api\Crud\Products\Filters;

use Api\Crud\Products\Retrieve;

use Api\Crud\Products\Update;

use Api\Crud\Products\Remove;

class ProductsController {

    public function get( Request $request ) {

        return ( new Retrieve )->get( $request );

    }

    public function getFilters( Request $request ) {

        return ( new Filters )->get( $request->get_params() );

    }

    public function store( Request $request ) {
        
        return ( new Insert )->store( $request );
        
    }

    public function update( Request $request ) {
        
        return ( new Update )->update( $request );

    }

    public function move( Request $request ) {

        return ( new Update )->moveProduct( $request );

    }

    public function remove( Request $request ) {

        return ( new Remove )->remove( $request );

    }

}