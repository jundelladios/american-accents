<?php

// Product Print Method Combination Routes
register_rest_route( $apiVersion, 'products-combo', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\ProductPrintMethodController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ProductPrintMethodController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ProductPrintMethodController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ProductPrintMethodController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));