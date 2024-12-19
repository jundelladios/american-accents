<?php
/**
 * @package AA_Project
 * 
 * product stock shapes controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\ProductStockShapes\Retrieve;

use Api\Crud\ProductStockShapes\Insert;

use Api\Crud\ProductStockShapes\Update;

use Api\Crud\ProductStockShapes\Remove;

use Api\Crud\ProductStockShapes\Generatev2;


class ProductStockShapesController {


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

    public function generate( Request $request ) {

        return (new Generatev2)->generate( $request );

    }

}