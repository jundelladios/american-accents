<?php
/**
 * @package AA_Project
 * 
 * Print Methods Controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\ProductLineColors\Insert;

use Api\Crud\ProductLineColors\Retrieve;

use Api\Crud\ProductLineColors\Update;

use Api\Crud\ProductLineColors\Remove;

class ProductLineColorsController {

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