<?php

// Specification Types Routes
register_rest_route( $apiVersion, 'specification-types', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\SpecificationTypesController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\SpecificationTypesController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\SpecificationTypesController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\SpecificationTypesController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));

register_rest_route( $apiVersion, 'specification-types/duplicate', array(
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\SpecificationTypesController, 'duplicate' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));