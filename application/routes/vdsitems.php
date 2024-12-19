<?php

// vds items
register_rest_route( $apiVersion, 'vdsitems', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\VDSItemsController, 'index' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, 'vdsitems/sync', array(
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\VDSItemsController, 'vdsItemsSynchronize' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));

register_rest_route( $apiVersion, 'vdsitems/connect', array(
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\VDSItemsController, 'connect' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));