<?php

// Printing Methods Routes
register_rest_route( $apiVersion, 'print-methods', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PrintMethodsController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\PrintMethodsController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\PrintMethodsController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\PrintMethodsController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));