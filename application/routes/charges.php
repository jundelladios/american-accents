<?php

// Charges Routes
register_rest_route( $apiVersion, 'charges', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\ChargesController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ChargesController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ChargesController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ChargesController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));