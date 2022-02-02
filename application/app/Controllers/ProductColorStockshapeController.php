<?php
/**
 * @package AA_Project
 * 
 * Colors controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\ProductColorStockshape\Retrieve;

use Api\Crud\ProductColorStockshape\Insert;

use Api\Crud\ProductColorStockshape\Update;

use Api\Crud\ProductColorStockshape\Remove;

use Api\Crud\ProductColorStockshape\Generatev2;


class ProductColorStockshapeController {


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