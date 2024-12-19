<?php
/**
 * @package AA_Project
 * 
 * Categories Controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\PricingDataValues\Update;

use Api\Crud\PricingDataValues\Insert;

use Api\Crud\PricingDataValues\Remove;

use Api\Crud\PricingDataValues\Import;


class PricingDataValuesController {


    public function store( Request $request ) {
        
        return (new Insert)->store( $request );
        
    }

    public function update( Request $request ) {
        
        return (new Update)->update( $request );

    }


    public function remove( Request $request ) {

        return (new Remove)->remove( $request );

    }


    public function product_combo_price_import( Request $request ) {

        return (new Import)->productcombo( $request );

    }

    public function product_line_price_import( Request $request ) {

        return (new Import)->productline( $request );

    }

}