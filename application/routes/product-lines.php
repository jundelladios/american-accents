<?php

// Products Lines Routes
register_rest_route( $apiVersion, 'product-lines', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\ProductLinesController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ProductLinesController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ProductLinesController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ProductLinesController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));


// Import
register_rest_route( $apiVersion, 'product-lines/import', array(
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ProductLinesController, 'import' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
));

// Product Line Colors
register_rest_route( $apiVersion, 'product-line-colors', array(
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ProductLineColorsController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ProductLineColorsController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ProductLineColorsController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));


// Product Line PremiumBG
register_rest_route( $apiVersion, 'product-line/premiumBG', array(
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ProductLinePremiumBGController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ProductLinePremiumBGController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ProductLinePremiumBGController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));

// Product Line Stock Shapes
register_rest_route( $apiVersion, 'product-line/stockshape', array(
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ProductLineStockShapeController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ProductLineStockShapeController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ProductLineStockShapeController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));