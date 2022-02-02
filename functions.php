<?php
/**
 * Plugin Name: American Accents Plugin
 * Plugin URI: mailto:jundell@ad-ios.com
 * Description: American Accents Inventory System
 * Version: 1.0
 * Author: Jun Dell
 * Author URI: mailto:jundell@ad-ios.com
 */

// Composer Libs
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

 /**
 * REST API config
 */
if( file_exists( ABSPATH . '/application-config.php' ) ) {
    require_once( ABSPATH . '/application-config.php');
    require plugin_dir_path( __FILE__ ) . 'application/config.php';
}

// Check if config not found.
function aa_config_file_checker() {
    if( !file_exists( ABSPATH . '/application-config.php' ) ):
    ?>
    <div class="notice notice-error">
        <p><?php _e( 'File Not Found! American Accents Plugin requires application-config.php file.', 'american-accents' ); ?></p>
    </div>
    <?php
    endif;
}
add_action( 'admin_notices', 'aa_config_file_checker' );

function american_accent_plugin_base_url() {
    return plugin_dir_url(__FILE__);
}

function american_accent_plugin_base_dir() {
    return plugin_dir_path(__FILE__);
}

function american_accent_plugin_version() {
    return get_plugin_data(__FILE__, array());
}

function american_accents_inventory_support() {
    return true;
}

function aa_inventory_plugin_activate() {

    flush_rewrite_rules();

 }

 // installing this plugin, rewrite and flush rules
register_activation_hook(__FILE__, 'aa_inventory_plugin_activate');

function aa_inventory_plugin_deactivate() {

    flush_rewrite_rules();

}

// uninstalling this plugin, flush rules
register_deactivation_hook(__FILE__, 'aa_inventory_plugin_deactivate');

// remove auto paragraph in wordpress
remove_filter('the_content', 'wpautop');

$apiSeo = new Api\SeoContents();

$dynamicPage = new Api\DynamicPages();

// Shortcodes
require_once plugin_dir_path( __FILE__ ) . '/shortcodes.php';

// Media Support
require_once plugin_dir_path( __FILE__ ) . '/media-support.php';

// Carbon Fields
require_once plugin_dir_path( __FILE__ ) . '/carbon-fields/functions.php';

// Routers
require_once plugin_dir_path( __FILE__ ) . '/router.php';

// Yoast SEO Support
require_once plugin_dir_path( __FILE__ ) . '/yoast-support.php';