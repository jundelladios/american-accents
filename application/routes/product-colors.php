<?php

// Product Colors
register_rest_route( $apiVersion, 'product-colors', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\ProductColorsController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ProductColorsController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ProductColorsController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ProductColorsController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));

register_rest_route( $apiVersion, 'product-colors/generate', array(
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ProductColorsController, 'generate' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));
