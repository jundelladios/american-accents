<?php

// Sub Categories Routes
register_rest_route( $apiVersion, 'subcategories', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\SubcategoryController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\SubcategoryController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\SubcategoryController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\SubcategoryController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));

// categorize lists
register_rest_route( $apiVersion, 'subcategories/categorize-lists', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\SubcategoryController, 'getCategorize' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
));


register_rest_route( $apiVersion, 'subcategories/catalog-assign', array(
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\SubcategoryController, 'catalogAssign' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));