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
        ->set_default_value( home_url() ),
        Field::make( 'rich_text', 'aa_admin_settings_compliance', __( 'Compliance Notes Content' ) ),
        Field::make( 'rich_text', 'aa_admin_settings_premiumbg', __( 'Premium Backgrounds Content' ) ),
        Field::make( 'text', 'aa_admin_settings_vdsauthkey', 'SAGE VDS Auth Key' )->set_width(50),
        Field::make( 'text', 'aa_admin_settings_vdssuppid', 'SAGE VDS Supplier ID' )->set_width(50),
    ));
}