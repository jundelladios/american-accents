<?php
/**
 * @package AA_Project
 * 
 * Colors controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\Colors\Retrieve;

use Api\Crud\Colors\Insert;

use Api\Crud\Colors\Update;

use Api\Crud\Colors\Remove;

class ColorsController {


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