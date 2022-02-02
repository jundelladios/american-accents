<?php

// Product Color and StockShape
register_rest_route( $apiVersion, 'product_colors_stockshape', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\ProductColorStockshapeController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ProductColorStockshapeController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ProductColorStockshapeController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ProductColorStockshapeController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));

register_rest_route( $apiVersion, 'product_colors_stockshape/generate', array(
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ProductColorStockshapeController, 'generate' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));
