<?php

register_rest_route( $apiVersion, 'clip-arts', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\ClipArtsController, 'get' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    ),
    array(
        'methods' => 'PUT',
        'callback' => array( new Api\Controllers\ClipArtsController, 'update' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\ClipArtsController, 'store' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\ClipArtsController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));