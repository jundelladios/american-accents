<?php
/**
 * @package AA_Project
 * 
 * imprint type controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\ImprintProductLine\Retrieve;

use Api\Crud\ImprintProductLine\Insert;

use Api\Crud\ImprintProductLine\Update;

use Api\Crud\ImprintProductLine\Remove;

class ImprintProductLineController {


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