<?php

// imprint types
register_rest_route( $apiVersion, 'imprint-type', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\ImprintTypeController, 'get' ),
        'permission_callback' => function( $request ) { return true; }
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ImprintTypeController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ImprintTypeController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ImprintTypeController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));


// imprint types product line
register_rest_route( $apiVersion, 'imprint-type-product-line', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\ImprintProductLineController, 'get' ),
        'permission_callback' => function( $request ) { return true; }
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ImprintProductLineController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ImprintProductLineController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ImprintProductLineController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));
