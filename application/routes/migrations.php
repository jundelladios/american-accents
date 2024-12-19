<?php

// migrations
register_rest_route( $apiVersion, 'database/migrate', array(
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\MigrationController, 'migrate' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));

register_rest_route( $apiVersion, 'database/backup', array(
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\MigrationController, 'backup' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    array(
        'methods' => 'DELETE',
        'callback' => array( new Api\Controllers\MigrationController, 'remove' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));

register_rest_route( $apiVersion, 'database/restore', array(
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\MigrationController, 'restore' ),
        'permission_callback' => 'apiAuthorizeCB'
    )
));