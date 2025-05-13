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

function aa_app_config_isReady() {

    return defined('_APP_DB_NAME') && 
    defined('_APP_DB_USER') && 
    defined('_APP_DB_PASSWORD') && 
    defined('_APP_DB_HOST') && 
    defined('_APP_SUFFIX');
}

 /**
 * REST API config
 */
if( aa_app_config_isReady() ) {
    require plugin_dir_path( __FILE__ ) . 'application/config.php';
}

// Check if config not found.
function aa_config_file_checker() {
    if( !aa_app_config_isReady() ):
    ?>
    <div class="notice notice-error">
        <p><?php _e( 'American Accents requires Inventory Constant Variables (_APP_DB_NAME, _APP_DB_USER, _APP_DB_PASSWORD, _APP_DB_HOST, _APP_SUFFIX).', 'american-accents' ); ?></p>
    </div>
    <?php
    endif;
}
add_action( 'admin_notices', 'aa_config_file_checker' );

// GET THE APP SUFFIX
function aa_app_suffix() {
    if( defined('_APP_SUFFIX') ) {
        return _APP_SUFFIX;
    }
    return "american-accents-";
}

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


function wpdb_image_attachment_details($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
    if(isset($attachment[0])) {
        $img = wp_get_attachment_image_src($attachment[0]);
        if(isset($img[1]) && isset($img[2])) {
            $alt = get_post_meta($attachment[0], '_wp_attachment_image_alt', true);
            return [
                'width' => $img[1],
                'height' => $img[2],
                'alt' => $alt
            ];
        }
        return null;
    } else {
        return null;
    }
}

function aa_generate_placeholder_image( $width = 0, $height = 0 ) {
    if ( ! is_numeric( $width ) ) {
        $width = 0;
    }

    if ( ! is_numeric( $height ) ) {
        $height = 0;
    }

    if ( ! empty( $width ) ) {
        $width = absint( $width );
    }

    if ( ! empty( $height ) ) {
        $height = absint( $height );
    }

    //return "data:image/svg+xml;utf8,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20{$width}%20{$height}'%3E%3C/svg%3E";
    // Generate the SVG
    $svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 {$width} {$height}'></svg>";

    // Convert the SVG to a base64 string
    $svg_base64 = base64_encode( $svg );

    return "data:image/svg+xml;base64,{$svg_base64}";
}

// image proxy function
function aa_image_proxy( $url ) {
  return \Api\Media::imageproxy( $url );
}


add_action( 'wp_head', function() {
    ?>
    <script type="text/javascript">
        var globalJSVars = {
            inventoryCurrency: '<?php echo carbon_get_theme_option( 'aa_admin_settings_currency' ); ?>',
            chargesIndicator: '<?php echo carbon_get_theme_option( 'aa_admin_settings_charge_indicator' ); ?>',
            diechargeLabel: '<?php echo carbon_get_theme_option( 'aa_admin_settings_diecharge' ); ?>'
        }
    </script>
    <?php
});


add_action( 'wp_footer', function() {
    // late load and apply flying script on these assets.
    // these assets can use if there is a user interaction
    ?>
    <script type="text/javascript">
        jQuery(document).ready( function($) {
            const lazyassetslists = [
                {
                    type: 'js',
                    url: '<?php echo american_accent_plugin_base_url() . 'application/assets/libs/printjs/printjs.js'; ?>',
                    id: '<?php echo aa_app_suffix() . 'print-js-js'; ?>'
                },
                {
                    type: 'css',
                    url: '<?php echo american_accent_plugin_base_url() . 'application/assets/libs/printjs/printjs.css'; ?>',
                    id: '<?php echo aa_app_suffix() . 'print-js-css'; ?>'
                },
                {
                    type: 'js',
                    url: '<?php echo american_accent_plugin_base_url() . 'application/assets/libs/jsshare.js'; ?>',
                    id: '<?php echo aa_app_suffix() . 'jsshare-js'; ?>'
                }
            ];

            lazyassetslists.map(row => {
                // if asset exists this will be ignored.
                if(row.type == 'js' && !$(`script#${row.id}-js`).length) {
                    var s = document.createElement("script");
                    s.src = row.url;
                    s.id = row.id;
                    $("body").append(s);
                }

                if(row.type == 'css' && !$(`link#${row.id}-css`).length) {
                    var s = document.createElement("link");
                    s.type='text/css' 
                    s.media='all'
                    s.href = row.url;
                    s.id = row.id;
                    $("head").append(s);
                }
            });
        });
    </script>
    <?php
});


// global enqueuue lib
add_action( 'wp_enqueue_scripts', function() {

    wp_enqueue_script( aa_app_suffix() . 'elevatezoom-js', american_accent_plugin_base_url() . 'application/assets/libs/elevatezoom/elevatezoom.js', array(), null, true );

});


// auto create inventory database backup folder in wp-content/uploads
add_action( 'admin_init', function() {
    $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'aa-inventory-migrations';
    if(!is_dir($uploads_dir)) {
        wp_mkdir_p( $uploads_dir );
    }
});


function aa_is_page_editor() {
  if( !function_exists( 'get_current_screen' ) ) {
    return false;
  }

  $screen = get_current_screen();
  if ( $screen->is_block_editor === true ) {
    return true;
  }
  return false;
}