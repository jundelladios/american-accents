<?php

// Pricing Data Routes
register_rest_route( $apiVersion, 'pricing-data', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PricingDataController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\PricingDataController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\PricingDataController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\PricingDataController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));


// Import

register_rest_route( $apiVersion, 'pricing-data/import', array(
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\PricingDataController, 'import' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
));

// Pricing Data Values Routes

register_rest_route( $apiVersion, 'pricing-data/values', array(
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\PricingDataValuesController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\PricingDataValuesController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\PricingDataValuesController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));



register_rest_route( $apiVersion, 'pricing-data/import/combo', array(
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\PricingDataValuesController, 'product_combo_price_import' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));


register_rest_route( $apiVersion, 'pricing-data/import/productline', array(
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\PricingDataValuesController, 'product_line_price_import' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));