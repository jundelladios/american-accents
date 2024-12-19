<?php
/**
 * @package AA_Project
 * 
 * Subcategory Controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\ProductPrintMethod\Insert;

use Api\Crud\ProductPrintMethod\Retrieve;

use Api\Crud\ProductPrintMethod\Update;

use Api\Crud\ProductPrintMethod\Remove;

class ProductPrintMethodController {

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