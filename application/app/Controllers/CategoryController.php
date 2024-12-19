<?php
/**
 * @package AA_Project
 * 
 * Categories Controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\Categories\Retrieve;

use Api\Crud\Categories\Insert;

use Api\Crud\Categories\Update;

use Api\Crud\Categories\Remove;

class CategoryController {

    public function get( Request $request ) {

        return (new Retrieve)->get( $request );

    }


    public function update( Request $request ) {

        return (new Update)->update( $request );

    }

    public function store( Request $request ) {

        return (new Insert)->store( $request );

    }

    public function remove( Request $request ) {

        return (new Remove)->remove( $request );

    }

}