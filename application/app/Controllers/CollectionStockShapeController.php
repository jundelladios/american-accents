<?php
/**
 * @package AA_Project
 * 
 * Stock Shape collection controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\CollectionStockShape\Retrieve;

use Api\Crud\CollectionStockShape\Insert;

use Api\Crud\CollectionStockShape\Update;

use Api\Crud\CollectionStockShape\Remove;

class CollectionStockShapeController {


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