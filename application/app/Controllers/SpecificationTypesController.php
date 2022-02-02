<?php
/**
 * @package AA_Project
 * 
 * imprint type controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\SpecificationTypes\Retrieve;

use Api\Crud\SpecificationTypes\Insert;

use Api\Crud\SpecificationTypes\Update;

use Api\Crud\SpecificationTypes\Remove;

class SpecificationTypesController {


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

    public function duplicate( Request $request ) {

        return (new Insert)->duplicate( $request );

    }

}