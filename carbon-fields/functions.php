<?php

/**
 * Carbon Fields
 *
 * @package AA_Project
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    \Carbon_Fields\Carbon_Fields::boot();
}


add_action( 'carbon_fields_register_fields', 'aa_admin_settings_carbon' );
function aa_admin_settings_carbon() {
    Container::make( 'theme_options', 'American Accent Settings' )
    ->set_page_parent( aa_app_suffix() . 'general' )
    ->add_fields( array(
        Field::make( 'text', 'aa_qrcode_domain', 'QR Code DOMAIN' )
        ->set_default_value( get_option('_aa_qrcode_domain', home_url()) ),


        Field::make( 'rich_text', 'aa_admin_settings_compliance', __( 'Compliance Notes Content' ) )
        ->set_default_value( get_option('_aa_admin_settings_compliance', '') ),

        Field::make( 'rich_text', 'aa_admin_settings_premiumbg', __( 'Premium Backgrounds Content' ) )
        ->set_default_value( get_option('_aa_admin_settings_premiumbg', '') ),


        Field::make( 'text', 'aa_admin_settings_vdsauthkey', 'SAGE VDS Auth Key' )->set_width(50)
        ->set_default_value( get_option('_aa_admin_settings_vdsauthkey', '') ),

        Field::make( 'text', 'aa_admin_settings_vdssuppid', 'SAGE VDS Supplier ID' )->set_width(50)
        ->set_default_value( get_option('_aa_admin_settings_vdssuppid', '') ),

        Field::make( 'text', 'aa_admin_settings_vdsapiaccid', 'SAGE API Account ID' )->set_width(50)
        ->set_default_value( get_option('aa_admin_settings_vdsapiaccid', '') ),

        Field::make( 'text', 'aa_admin_settings_vdsapiauthtoken', 'SAGE API Token' )->set_width(50)
        ->set_default_value( get_option('aa_admin_settings_vdsapiauthtoken', '') ),

        Field::make( 'text', 'aa_admin_settings_vdsapisupplierid', 'SAGE API Supplier ID' )->set_width(50)
        ->set_default_value( get_option('aa_admin_settings_vdsapisupplierid', '') ),

        Field::make( 'text', 'aa_admin_settings_vdsapiversion', 'SAGE API Version' )->set_width(50)
        ->set_default_value( get_option('aa_admin_settings_vdsapiversion', '120') ),


        Field::make( 'select', 'aa_admin_settings_currency', 'Select Currency' )
        ->add_options( array(
            '' => 'No Currency',
            '$' => 'USD ($)',
            '€' => 'EURO (€)'
        ) )
        ->set_default_value( get_option('_aa_admin_settings_currency', '') )
        ->set_width(50),

        Field::make( 'text', 'aa_admin_settings_charge_indicator', 'Charges Indicator' )
        ->set_default_value( get_option('_aa_admin_settings_charge_indicator', '(v)') )
        ->set_width(50),

        Field::make( 'text', 'aa_admin_settings_diecharge', 'Die Charge Label' )
        ->set_default_value( get_option('_aa_admin_settings_diecharge', 'Die Charge') )
        ->set_width(50),

        Field::make( 'text', 'aa_admin_settings_cdnproxy', 'CDN Proxy' )
        ->set_default_value( get_option('_aa_admin_settings_cdnproxy', '') )
        ->set_width(50),

        Field::make( 'textarea', 'aa_admin_settings_nolazyloadlists', 'Image Lists without Lazyload (Separated by comma)' )
        ->set_default_value( get_option('_aa_admin_settings_nolazyloadlists', '') ),

        
    ));
}