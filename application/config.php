<?php
/**
 * REST API Configuration
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AA_Project
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Pagination\Paginator as Paginator;

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Swaggest\JsonSchema\Schema;

$apiVersion = "v1";

$capsule = new Capsule;

$capsule->addConnection( array(
    'driver' => 'mysql',
    'host' => _APP_DB_HOST,
    'database' => _APP_DB_NAME,
    'username' => _APP_DB_USER,
    'password' => _APP_DB_PASSWORD,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
));

$capsule->setAsGlobal();

$capsule->bootEloquent();

Paginator::currentPageResolver(function ($pageName = 'page') {
    return (int) ($_GET[$pageName] ?? 1);
});

Paginator::currentPathResolver(function () {
    return null;
});

// GET THE APP SUFFIX
function aa_app_suffix() {
    if( defined('_APP_SUFFIX') ) {
        return _APP_SUFFIX;
    }
    return "american-accents-";
}

// REST API ROUTES
function aa_rest_api_init() {
    require plugin_dir_path( __FILE__ ) . 'routes.php';
}
add_action( 'rest_api_init', 'aa_rest_api_init' );

/**
 * Theme REST API assets
 */
function aa_rest_api_assets() {

    $version = american_accent_plugin_version()['Version'];

    // enqueue only on these functionality.
    if( !isset($_GET['page']) || !preg_match("/^" . aa_app_suffix() . "/", $_GET['page'])) {
        return;
    }

    // ADMIN CSS
    wp_enqueue_style( aa_app_suffix() . 'css', american_accent_plugin_base_url() . '/application/assets/css/admin.css', array(), $version, null );
    
    // TRUMBOWYG CSS
    wp_enqueue_style( aa_app_suffix() . 'trumbowyg-css', american_accent_plugin_base_url() . 'application/assets/libs/trumbowyg/trumbowyg.css', array(), $version, null );

    // slick css
    wp_enqueue_style( 'vue-slick-slider', american_accent_plugin_base_url() . '/application/assets/libs/slick-slider/slick.min.css', array(), $version, null );

    // Axios HTTP REQUEST
    wp_enqueue_script( aa_app_suffix() . 'axios-js', american_accent_plugin_base_url() . 'application/assets/libs/axios/axios.js', array(), $version, false );
    
    // Vue JS
    wp_enqueue_script( aa_app_suffix() . 'vue-js', american_accent_plugin_base_url() . 'application/assets/libs/vue/vue.js', array(), $version, false );

    // vue pagination
    wp_enqueue_script( aa_app_suffix() . 'vue-js-pagination', american_accent_plugin_base_url() . 'application/assets/libs/pagination/vue-paginate.js', array(), $version, false );
    
    // vue slick
    wp_enqueue_script( aa_app_suffix() . 'vue-slick', american_accent_plugin_base_url() . 'application/assets/libs/slick-slider/vue-slick.js', array(), $version, false );

    // Vee Validate
    wp_enqueue_script( aa_app_suffix() . 'vee-validate-js', american_accent_plugin_base_url() . 'application/assets/libs/vee-validate/vee-validate.js', array(), $version, false );
    
    // Trumbowyg Editor Component
    wp_enqueue_script( aa_app_suffix() . 'trumbowyg-js', american_accent_plugin_base_url() . 'application/assets/libs/trumbowyg/trumbowyg.js', array(), $version, false );
    wp_enqueue_script( aa_app_suffix() . 'vue-trumbowyg-js', american_accent_plugin_base_url() . 'application/assets/libs/trumbowyg/vue-trumbowyg.js', array(), $version, false );

    // Color Picker Component
    wp_enqueue_script( aa_app_suffix() . 'vue-color', american_accent_plugin_base_url() . 'application/assets/libs/vue-color/vue-color.min.js', array(), $version, false );
    wp_enqueue_script( aa_app_suffix() . 'colorpicker-component', american_accent_plugin_base_url() . 'application/assets/libs/vue-color/input.component.js', array(), $version, false );

    // circular json
    wp_enqueue_script( aa_app_suffix() . 'circular-json', american_accent_plugin_base_url() . 'application/assets/libs/circular-json/index.js', array(), $version, false );

    // My App
    wp_enqueue_script( aa_app_suffix() . 'js', american_accent_plugin_base_url() . 'application/assets/js/app.js', array(), $version, false );
    wp_add_inline_script( aa_app_suffix() . 'js', aa_application_js_embed() );

    // Init Vue
    wp_enqueue_script( aa_app_suffix() . 'vue-init-js', american_accent_plugin_base_url() . 'application/assets/js/vue-init.js', array(), $version, false );

    // Swal
    wp_enqueue_script( aa_app_suffix() . 'swal-js', american_accent_plugin_base_url() . 'application/assets/libs/swal.js', array(), $version, false );

    // Vue Draggable
    wp_enqueue_script( aa_app_suffix() . 'vue-draggable-sortable-js', american_accent_plugin_base_url() . 'application/assets/libs/vue-draggable/sortable.js', array(), $version, false );

    wp_enqueue_script( aa_app_suffix() . 'vue-draggable-js', american_accent_plugin_base_url() . 'application/assets/libs/vue-draggable/draggable.js', array(), $version, false );

    // QR CODE
    // wp_enqueue_script( aa_app_suffix() . 'qr-code-js', american_accent_plugin_base_url() . 'application/assets/libs/qrcode.js', array(), $version, false );


    // papa parse csv
    wp_enqueue_script( aa_app_suffix() . 'paparse-csv', american_accent_plugin_base_url() . 'application/assets/libs/paparse.js', array(), $version, false );


}
add_action( 'admin_enqueue_scripts', 'aa_rest_api_assets' );

/**
 * 
 * Theme Axios INIT
 * 
 */
function aa_application_js_embed() {
    global $apiVersion;
    ob_start();
    ?>
    var apiSettings = {
        root: '<?php echo esc_url_raw( rest_url()) . $apiVersion; ?>',
        nonce: '<?php echo wp_create_nonce( 'wp_rest' ); ?>'
    }

    var api = axios.create({
        baseURL: apiSettings.root
    });

    api.interceptors.request.use( function( config ) {
        config.headers['X-WP-Nonce'] = apiSettings.nonce;
        return config;
    }, function( error ) {
        Promise.reject( error );
    });

    <?php if( isset( $_GET['_id'] ) && !empty( $_GET['_id'] ) ): ?>
        var the_ID = '<?php echo $_GET['_id']; ?>';
    <?php else: ?>
        var the_ID = null;
    <?php endif; ?>

    var home_url = "<?php echo get_home_url(); ?>";

    <?php
    $html = ob_get_clean();
    return $html;
}
add_action( 'wp_head', 'aa_application_js_embed' );

/**
 * Slug Generator
 */
function rest_slug_generator( $slug ) {
    return preg_replace('/\W+/', '-', strtolower($slug));
}

/**
 * Allowed Request
 */
function rest_requests( $request, $allowed = array() ) {
    return array_intersect_key( $request, array_flip( $allowed ));
}

/**
 * Response JSON
 */
function rest_response( $message = null, $code = 200 ) {
    return new WP_REST_Response([ 'message' => $message, 'code' => $code ], $code);
}

// Pages
require plugin_dir_path( __FILE__ ) . 'pages.php';

// Functions
require plugin_dir_path( __FILE__ ) . 'functions.php';

// VALITRON CUSTOM VALIDATION JSON TYPES
Valitron\Validator::addRule('jsonString', function($field, $value, array $params, array $fields) {

    try {

        $schema = Schema::import(json_decode(
            file_get_contents(american_accent_plugin_base_dir() . 'schema' . $params[0])
        ));

        $schema->in(json_decode($value));

        return true;

    } catch(Exception $e) {

        return false;

    }

}, 'formatted json is invalid.');