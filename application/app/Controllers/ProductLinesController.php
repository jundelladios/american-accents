<?php
/**
 * @package AA_Project
 * 
 * Products Controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\ProductLines\Insert;

use Api\Crud\ProductLines\Retrieve;

use Api\Crud\ProductLines\Update;

use Api\Crud\ProductLines\Remove;

use Api\Crud\ProductLines\Import;

class ProductLinesController {

    public function get( Request $request ) {

        return (new Retrieve)->get( $request );

    }

    public function store( Request $request ) {
        
        return (new Insert)->store( $request );

    }

    public function update( Request $request ) {

        return (new Update)->update( $request );

    }


    public function remove( Request $request ) {

        return (new Remove)->remove( $request );

    }

    public function import( Request $request ) {

        return (new Import)->import( $request );

    }

}