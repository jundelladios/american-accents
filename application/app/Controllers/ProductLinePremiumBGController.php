<?php
/**
 * @package AA_Project
 * 
 * Print Methods Controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\ProductLinePremiumBG\Insert;

use Api\Crud\ProductLinePremiumBG\Retrieve;

use Api\Crud\ProductLinePremiumBG\Update;

use Api\Crud\ProductLinePremiumBG\Remove;

class ProductLinePremiumBGController {

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