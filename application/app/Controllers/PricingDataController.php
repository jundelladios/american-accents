<?php
/**
 * @package AA_Project
 * 
 * Categories Controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\PricingData\Update;

use Api\Crud\PricingData\Insert;

use Api\Crud\PricingData\Remove;

use Api\Crud\PricingData\Retrieve;

use Api\Crud\PricingData\Import;


class PricingDataController {

    public function get( Request $request ) {

        return (new Retrieve)->get( $request );

    }

    public function store( Request $request ) {

        return (new Insert)->store( $request );
        
    }

    public function remove( Request $request ) {

        return (new Remove)->remove( $request );

    }


    public function update( Request $request ) {

        return (new Update)->update( $request );

    }


    public function import( Request $request ) {

        return (new Import)->import( $request );

    }

}