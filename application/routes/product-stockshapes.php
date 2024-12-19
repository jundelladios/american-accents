<?php

// Product Colors
register_rest_route( $apiVersion, 'product-stockshapes', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\ProductStockShapesController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ProductStockShapesController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ProductStockShapesController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ProductStockShapesController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));

register_rest_route( $apiVersion, 'product-stockshapes/generate', array(
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ProductStockShapesController, 'generate' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));
