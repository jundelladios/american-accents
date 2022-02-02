<?php

// Coupon Codes Routes
register_rest_route( $apiVersion, 'coupon-codes', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\CouponCodeController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\CouponCodeController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\CouponCodeController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\CouponCodeController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));
