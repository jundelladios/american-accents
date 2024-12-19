<?php
/**
 * @package AA_Project
 * 
 * Hash ID to hide auto increment ID's.
 * 
 */

namespace Api;

use Hashids\Hashids;

use Api\Constants;

class Hasher {

    private static $hashids;

    private function __construct() { }

    private static function initialize() {

        if( !self::$hashids ) {

            self::$hashids = new Hashids( Constants::SALT, Constants::LENGTH, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890' );

        }

    }

    public static function encode( $args ) {

        self::initialize();

        return self::$hashids->encode( $args );

    }

    public static function decode( $hash ) {

        if( is_int( $hash ) ) {

            return $hash;

        }

        try {

            self::initialize();

            if( isset( self::$hashids->decode( $hash )[0] ) ) {

                return self::$hashids->decode( $hash )[0];

            }

            return null;

        } catch( \Exception $e ) {

            return null;

        }

    }

}