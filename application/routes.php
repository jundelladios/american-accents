<?php

/**
 * REST API ROUTES
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AA_Project
 */

global $apiVersion;

function apiAuthorizeCB() {
    
    $devmode = (bool) defined('_APP_DEVMODE') && _APP_DEVMODE;

    if( $devmode ) {

        return true;

    }

    return is_user_logged_in();

}

require_once plugin_dir_path( __FILE__ ) . 'routes/categories.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/subcategories.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/print-methods.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/product.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/productcombo.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/product-lines.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/product-colors.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/product-stockshapes.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/pricing-data.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/public.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/coupons.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/charges.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/migrations.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/imprint-type.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/clip-arts.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/collections.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/specificationtypes.php';

require_once plugin_dir_path( __FILE__ ) . 'routes/product-color-and-stockshape.php';