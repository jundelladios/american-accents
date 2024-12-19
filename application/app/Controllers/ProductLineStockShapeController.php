<?php
/**
 * @package AA_Project
 * 
 * Product Line Stock Shape Controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\ProductLineStockShapes\Insert;

use Api\Crud\ProductLineStockShapes\Retrieve;

use Api\Crud\ProductLineStockShapes\Update;

use Api\Crud\ProductLineStockShapes\Remove;

class ProductLineStockShapeController {

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