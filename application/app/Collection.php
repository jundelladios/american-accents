<?php
/**
 * 
 * CONVERT FROM ELOQUENT TO JSON
 * 
 */

 namespace Api;

 class Collection {

    public static function toJson( $data ) {

        return json_decode( $data->toJson(), true );

    }

 }