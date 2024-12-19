<?php
/**
 * @package AA_Project
 * 
 * Product Category Page
 * 
 */

namespace Api\Pages;

use Api\Pages\Page;

use Api\Assets;

use Api\Crud\PublicRoutes\Filters;

class ProductsPage extends Page {

    public function render() {

        add_filter('template_redirect', array( $this, 'template' ));

    }

    public function template() {

        Page::found();

        $this->seo_content();

        $this->assets();

        require_once american_accent_plugin_base_dir() . '/templates/page-products.php';

        exit;

    }

    public function seo_content() {
        $seo = (object) array(
            'title' => 'Products'
        );
        Page::seo($seo);
    }

    public function assets() {

        Assets::inventoryJsVars([]);

        add_action( 'wp_enqueue_scripts', function() {
            Assets::inventoryAsset();
            Assets::vue();
            wp_enqueue_script( aa_app_suffix() . 'lodash', american_accent_plugin_base_url() . 'application/assets/libs/lodash.js', array(), null, true );
            wp_enqueue_style( aa_app_suffix() . 'inventory-category-page', american_accent_plugin_base_url() . 'application/assets/css/category-page.css', array(), null, null );
            wp_enqueue_style( aa_app_suffix() . 'inventory-plugin-jquery-ui-slider', american_accent_plugin_base_url() . '/application/assets/libs/jquery-ui/slider.css', array(), null, null );
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-slider');
            Assets::vuetooltip();
            wp_enqueue_script( aa_app_suffix() . 'custom-vue', american_accent_plugin_base_url() . 'application/assets/libs/custom-vue.js', array(), null, true );
            wp_enqueue_script( aa_app_suffix() . 'vue-pagination', american_accent_plugin_base_url() . 'application/assets/libs/pagination/vue-paginate.js', array(), null, true );
            Assets::vueslick();
            wp_enqueue_script( aa_app_suffix() . 'vue-components', american_accent_plugin_base_url() . 'application/assets/js/components.js', array(), null, true );
            wp_enqueue_script( aa_app_suffix() . 'search-page', american_accent_plugin_base_url() . 'application/assets/js/allproductsvuevars.js', array(), null, true );
            wp_enqueue_script( aa_app_suffix() . 'search-page-js', american_accent_plugin_base_url() . 'application/assets/js/allproductsvue.js', array(), null, true );
        });

    }

}