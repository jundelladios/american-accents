<?php

// Categories Routes
register_rest_route( $apiVersion, 'categories', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\CategoryController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\CategoryController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\CategoryController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\CategoryController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));