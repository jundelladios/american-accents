<?php
/**
 * @package AA_Project
 * 
 * imprint type controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\ImprintTypes\Retrieve;

use Api\Crud\ImprintTypes\Insert;

use Api\Crud\ImprintTypes\Update;

use Api\Crud\ImprintTypes\Remove;

class ImprintTypeController {


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