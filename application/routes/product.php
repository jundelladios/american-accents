<?php

// Products Routes
register_rest_route( $apiVersion, 'products', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\ProductsController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ProductsController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ProductsController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ProductsController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));

// Material Types
register_rest_route( $apiVersion, 'products/filters', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\ProductsController, 'getFilters' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, 'products/move', array(
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ProductsController, 'move' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));
