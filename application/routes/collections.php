<?php

// stock shape
register_rest_route( $apiVersion, 'collections/stock-shape', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\CollectionStockShapeController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\CollectionStockShapeController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\CollectionStockShapeController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\CollectionStockShapeController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));


// colors
register_rest_route( $apiVersion, 'colors', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\ColorsController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ColorsController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ColorsController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ColorsController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));

// premium backgrounds
register_rest_route( $apiVersion, 'collections/premium-backgrounds', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\CollectionPremiumBackgroundController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\CollectionPremiumBackgroundController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\CollectionPremiumBackgroundController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\CollectionPremiumBackgroundController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));



