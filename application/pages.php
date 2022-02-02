<?php
/**
 * REST API Configuration
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AA_Project
 */

/**
 * American Accents Admin Menu
 */
function aa_admin_menu_rest() {

    $parent = aa_app_suffix() . 'general';
    $type = "manage_options";

    add_menu_page( 'American Accents', 'American Accents', $type, $parent, 'aa_admin_products_page', 'dashicons-admin-generic');

    add_submenu_page( $parent, 'Products', 'Products', $type, aa_app_suffix() . 'products', 'aa_admin_products_page' );

    add_submenu_page( $parent, 'Categories', 'Categories', $type, aa_app_suffix() . 'categories', 'aa_admin_categories_page' );

    add_submenu_page( $parent, 'Sub-Categories', 'Sub-Categories', $type, aa_app_suffix() . 'subcategories', 'aa_admin_subcategories_page' );

    add_submenu_page( $parent, 'Printing Methods', 'Printing Methods', $type, aa_app_suffix() . 'print-methods', 'aa_admin_print_method_page' );

    add_submenu_page( $parent, 'Coupon Codes', 'Coupon Codes', $type, aa_app_suffix() . 'coupon-codes', 'aa_admin_coupon_code_page' );

    // Charges
    add_submenu_page( $parent, 'Charge Types', 'Charge Types', $type, aa_app_suffix() . 'charge-types', 'aa_admin_charges_types_page' );

    // Cliparts
    add_submenu_page( $parent, 'Clip Arts', 'Clip Arts', $type, aa_app_suffix() . 'clip-arts', 'aa_cliparts' );

    // Imprint Types
    add_submenu_page( $parent, 'Imprint Types', 'Imprint Types', $type, aa_app_suffix() . 'imprint-types', 'aa_imprint_types' );
    
    // Colors
    add_submenu_page( $parent, 'Color Collections', 'Color Collections', $type, aa_app_suffix() . 'color-collections', 'aa_color_collections' );

    // Stock Shapes
    add_submenu_page( $parent, 'Stock Shape Collections', 'Stock Shape Collections', $type, aa_app_suffix() . 'stock-shape-collections', 'aa_stock_shape_collections' );

    // Premium Backgrounds
    add_submenu_page( $parent, 'Premium Background Collections', 'Premium Background Collections', $type, aa_app_suffix() . 'premium-background-collections', 'aa_premium_background_collections' );

    // Migrations
    add_submenu_page( $parent, 'Back-up & Migrations', 'Back-up & Migrations', $type, aa_app_suffix() . 'migrations', 'aa_admin_migrations_page' );

    // Specification Types
    add_submenu_page( $parent, 'Specification Types', 'Specification Types', $type, aa_app_suffix() . 'specification-types', 'aa_admin_specification_types_page' );   
    
    // Import/Export Tool
    add_submenu_page( $parent, 'Import/Export Tool', 'Import/Export Tool', $type, aa_app_suffix() . 'import-export', 'aa_admin_import_export_page' );


    // remove first item
    remove_submenu_page( $parent, $parent);

    // IF ITEM WAS NOT FOUND GO TO /carbon-fields

}
add_action( 'admin_menu', 'aa_admin_menu_rest' );

/**
 * Products Page
 */
function aa_admin_products_page() {
    if( isset( $_GET['productId'] ) ) {
        require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/index.php' );
    } else {
        require_once( american_accent_plugin_base_dir() . 'application/templates/products/products.php' );
    }
}

/**
 * American Accents Categories Page
 */
function aa_admin_categories_page() {

    require_once( american_accent_plugin_base_dir() . 'application/templates/categories/categories.php' );

}

/**
 * American Accents Product Lines Page
 */
function aa_admin_subcategories_page() {
    
    if( isset( $_GET['productLines'] ) ) {

        require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/page.php' );

    } else {

        require_once( american_accent_plugin_base_dir() . 'application/templates/subcategories/subcategories.php' );

    }

}

/**
 * American Accents Print Methods Page
 */
function aa_admin_print_method_page() {

    require_once( american_accent_plugin_base_dir() . 'application/templates/print-methods/print-methods.php' );

}

/**
 * Coupon Code Page
 */
function aa_admin_coupon_code_page() {

    require_once( american_accent_plugin_base_dir() . 'application/templates/coupon-codes/coupons.php' );

}

/**
 * Migrations Page
 */
function aa_admin_migrations_page() {

    require_once( american_accent_plugin_base_dir() . 'application/templates/migrations/migrations.php' );

}

/**
 * Clipart Page
 */
function aa_cliparts() {

    require_once( american_accent_plugin_base_dir() . 'application/templates/cliparts/cliparts.php' );

}

// Charges
function aa_admin_charges_types_page() {

    require_once( american_accent_plugin_base_dir() . 'application/templates/charges/charges.php' );

}

// Colors
function aa_color_collections() {

    require_once( american_accent_plugin_base_dir() . 'application/templates/colors/colors.php' );

}

// Imprint Types
function aa_imprint_types() {

    require_once( american_accent_plugin_base_dir() . 'application/templates/imprint-type/imprint.php' ); 
    
}

/** Stock shape collection page */
function aa_stock_shape_collections() {

    require_once( american_accent_plugin_base_dir() . 'application/templates/collections/stockshapes/index.php' ); 

}

/** premium background collection page */
function aa_premium_background_collections() {
    
    require_once( american_accent_plugin_base_dir() . 'application/templates/collections/premiumbackgrounds/index.php' ); 

}

/** specification types */
function aa_admin_specification_types_page() {

    require_once( american_accent_plugin_base_dir() . 'application/templates/specification-types/index.php' ); 

}


/** import export page */
function aa_admin_import_export_page() {

    require_once( american_accent_plugin_base_dir() . 'application/templates/importexport/index.php' ); 

}