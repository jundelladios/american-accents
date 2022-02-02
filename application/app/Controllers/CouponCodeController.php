<?php
/**
 * @package AA_Project
 * 
 * Categories Controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Models\CouponsModel;

use Api\Crud\Coupons\Retrieve;

use Api\Crud\Coupons\Insert;

use Api\Crud\Coupons\Update;

use Api\Crud\Coupons\Remove;

class CouponCodeController {

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

        return ( new Remove )->remove( $request );

    }

}