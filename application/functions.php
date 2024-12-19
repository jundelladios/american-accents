<?php
/**
 * GET DATA FUNCTIONS
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AA_Project
 */

 use Api\Hasher;

 use Brick\Money\Money;

use Brick\Money\Context\AutoContext;

use Brick\Money\Context\CustomContext;

function aa_formatted_money( $value, $nocurrency = false ) {
    
    if( $value ) {

        $theval = $value;

        if($nocurrency) { return $theval; }

        return carbon_get_theme_option( 'aa_admin_settings_currency' )."$theval";

    }

    return null;

}


 function aa_wp_request_handler( $thing ) {

    if( !isset( $thing->status ) || $thing->status === 200 ) {

        return $thing;

    }

    return null;

}

function get_aa_category( $request ) {
    
    return (new Api\Crud\Categories\Retrieve)->get( $request );

}


function get_aa_filters_data( $request ) {

    return (new Api\Crud\PublicRoutes\Filters)->getFilter( $request );

}

function get_aa_product_lines( $request ) {

    return (new Api\Crud\PublicRoutes\Filters)->getProductLines( $request );

}

function get_aa_products( $request ) {

    return (new Api\Crud\PublicRoutes\Filters)->getProducts( $request );

}

function get_aa_single_product( $request ) {

    return (new Api\Crud\PublicRoutes\Filters)->getSingleProduct( $request );

}

function get_aa_product_sizes( $request ) {

    return (new Api\Crud\PublicRoutes\Filters)->getSizes( $request );

}

function get_aa_hashID( $id ) {

    return Hasher::encode( $id );

}



function aa_json_array( $json ) {

    if( $json ) {

        $ret = json_decode( $json );

        if( is_array( $ret ) ) {

            return $ret;
            
        }

    }

    return [];

}


function aa_json_data( $json ) {

    if( $json ) {

        $ret = json_decode( $json );

        return $ret;

    }

    return null;
    
}


function aa_range_formatter($min, $max) {

    if(!$min || !$max || !isset($min) || !isset($max)) {
        return [
            'min' => 0,
            'max' => 0,
            'formatted_min' => aa_formatted_money(0),
            'formatted_max' => aa_formatted_money(0),
        ];
    }

    return [
        'min' => $min->value,
        'max' => $max->value,
        'formatted_min' => aa_formatted_money(number_format($min->value, $min->decimal_value)),
        'formatted_max' => aa_formatted_money(number_format($max->value, $max->decimal_value))
    ];

}