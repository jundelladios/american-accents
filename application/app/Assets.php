<?php
/**
 * 
 * Page Asset Loader
 * 
 */

 namespace Api;

 class Assets {

    public static function vue() {

        wp_enqueue_script( aa_app_suffix() . 'inventory-vue-js', american_accent_plugin_base_url() . 'application/assets/libs/vue/vue.js', array(), null, true );

        wp_enqueue_script( aa_app_suffix() . 'inventory-mixins-js', american_accent_plugin_base_url() . 'application/assets/js/frontend-mixins.js', array(), null, true );

        wp_enqueue_script( aa_app_suffix() . 'axios-js', american_accent_plugin_base_url() . 'application/assets/libs/axios/axios.js', array(), null, false );

        wp_add_inline_script( aa_app_suffix() . 'inventory-vue-js', self::axiosEmbed());

    }


    public static function axiosEmbed() {

        global $apiVersion;

        ob_start();

        $apibase = defined('_APP_CDN') && _APP_CDN ? _APP_CDN : home_url();

        ?>

        var apiSettings = {
            root: '<?php echo $apibase; ?>/wp-json/<?php echo $apiVersion ?>',
            nonce: '<?php echo wp_create_nonce( 'wp_rest' ); ?>'
        }

        var api = axios.create({
            baseURL: apiSettings.root,
            timeout: 0
        });

        api.interceptors.request.use( function( config ) {
            config.headers['X-WP-Nonce'] = apiSettings.nonce;
            return config;
        }, function( error ) {
            Promise.reject( error );
        });

        jQuery(function ($) {
            $('[data-toggle="tooltip"]').tooltip();
        });

        <?php
        $html = ob_get_clean();
        return $html;

    }
    
    public static function slick() {

        wp_enqueue_style( aa_app_suffix() . 'slick-slider', american_accent_plugin_base_url() . 'application/assets/libs/slick-slider/slick.min.css', array(), null, null );

        wp_enqueue_script( aa_app_suffix() . 'slick-slider', american_accent_plugin_base_url() . 'application/assets/libs/slick-slider/slick.min.js', array(), null, true );
    
    }

    public static function vueslick() {

        wp_enqueue_script( aa_app_suffix() . 'vue-slick-slider', american_accent_plugin_base_url() . 'application/assets/libs/slick-slider/vue-slick.js', array(), null, true );

    }


    public static function vuetooltip() {

        wp_enqueue_script( aa_app_suffix() . 'vtooltip-js', american_accent_plugin_base_url() . 'application/assets/libs/vtooltip.js', array(), null, true );

    }


    public static function inventoryAsset() {

        wp_enqueue_style( aa_app_suffix() . 'inventory-frontend-css', american_accent_plugin_base_url() . 'application/assets/css/frontend.css', array(), null, null );

        wp_enqueue_script( aa_app_suffix() . 'inventory-currency', american_accent_plugin_base_url() . 'application/assets/libs/currency/currency.js', array(), null, true );
        
        wp_enqueue_script( aa_app_suffix() . 'inventory-plugin', american_accent_plugin_base_url() . 'application/assets/js/inventory.js', array(), null, true );

    }


    public static function inventoryJsVars($args = []) {

        global $wp;

        add_action( 'wp_head', function() use ($wp, $args) {
            ?>
            <script type="text/javascript">
                const inventoryJSVars = {
                    inventoryCurrency: '<?php echo carbon_get_theme_option( 'aa_admin_settings_currency' ); ?>',
                    SAGEVDSAUTH: '<?php echo carbon_get_theme_option( 'aa_admin_settings_vdsauthkey' ); ?>',
                    SAGEVDSSUPPID: '<?php echo carbon_get_theme_option( 'aa_admin_settings_vdssuppid' ); ?>',
                    permalink: '<?php echo home_url( $wp->request ); ?>',
                    baseURL: '<?php echo home_url('/'); ?>',
                    pluginURL: '<?php echo american_accent_plugin_base_url(); ?>',
                    fallbackImage: '<?php echo \Api\Media::fallback(); ?>',
                    imageproxycdn: '<?php echo carbon_get_theme_option('aa_admin_settings_cdnproxy');  ?>',
                    baseURLnoSlash: '<?php echo home_url(); ?>',
                    <?php foreach( $args as $key => $value ): ?>
                        <?php echo "'{$key}': '{$value}',"; ?>
                    <?php endforeach; ?>
                }
            </script>
            <?php
        });

    }

 }