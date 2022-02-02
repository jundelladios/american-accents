<?php
/**
 * @package AA_Project
 * 
 * Print Methods Controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\PrintMethods\Insert;

use Api\Crud\PrintMethods\Retrieve;

use Api\Crud\PrintMethods\Update;

use Api\Crud\PrintMethods\Remove;

class PrintMethodsController {


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
}