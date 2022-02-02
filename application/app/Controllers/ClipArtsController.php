<?php
/**
 * @package AA_Project
 * 
 * CLIP ARTS REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\ClipArts\Retrieve;

use Api\Crud\ClipArts\Insert;

use Api\Crud\ClipArts\Update;

use Api\Crud\ClipArts\Remove;

class ClipArtsController {


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