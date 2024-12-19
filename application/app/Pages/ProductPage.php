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

class ProductPage extends Page {

    private $product = null;

    private $params = null;

    public function set( $param ) {

        $product = (new Filters)->getSingleProduct($param);

        $apiRequest = aa_wp_request_handler( $product );

        $this->product = $apiRequest ? $apiRequest : null;

        $this->params = $param;

    }

    public function render() {

        add_filter('template_redirect', array( $this, 'template' ));

    }


    public function template() {

        if( !$this->product ) {

            return;
        }

        Page::found();

        $this->seo_content();

        $this->assets();

        $singleProduct = $this->product;

        require_once american_accent_plugin_base_dir() . '/templates/page-productv3.php';

        Page::adminEdit("products&productId={$this->product['product']['hid']}&_id={$this->product['hid']}", "Edit Product Combo");

        exit;

    }

    public function seo_content() {
        $seo = json_decode($this->product['seo_content']);
        $seo->title = $this->product['product_method_combination_name'];
        if( $this->product['the_selected_variant'] ) {
            $seo->image = $this->product['the_selected_variant']['imagedata'][0]['image'];
        }
        Page::seo($seo);
    }

    public function assets() {

        $inventoryJs = array(
            'category' => $this->product['cat_slug'],
            'subcategory' => $this->product['sub_slug'],
            'productOrMethod' => $this->product['product_slug']
        );

        if( isset( $this->params['printmethod'] ) ) {
            $inventoryJs['printmethod'] = $this->params['printmethod'];
        }

        if( isset( $this->params['shape'] ) ) {
            $inventoryJs['variation'] = $this->params['shape'];
        }

        if( isset( $this->params['color'] ) ) {
            $inventoryJs['variation'] = $this->params['color'];
        }

        if( isset( $this->params['color-shape'] ) ) {
            $inventoryJs['variation'] = $this->params['color-shape'];
        }


        Assets::inventoryJsVars($inventoryJs);

        add_action( 'wp_enqueue_scripts', function() {

            Assets::inventoryAsset();

            wp_enqueue_script( aa_app_suffix() . 'lodash', american_accent_plugin_base_url() . 'application/assets/libs/lodash.js', array(), null, true );
            
            Assets::vue();

            Assets::vuetooltip();

            Assets::vueslick();

            wp_enqueue_script( aa_app_suffix() . 'custom-vue', american_accent_plugin_base_url() . 'application/assets/libs/custom-vue.js', array(), null, true );
            
            wp_enqueue_style( aa_app_suffix() . 'inventory-product-page', american_accent_plugin_base_url() . 'application/assets/css/productv2.css', array(), null, null );
            
            // VUE START
            wp_enqueue_script( aa_app_suffix() . 'product-vuevars-premiumbg', american_accent_plugin_base_url() . 'application/assets/js/product/premiumbg.js', array(), null, true );
            
            wp_enqueue_script( aa_app_suffix() . 'product-vuevars-stockshape', american_accent_plugin_base_url() . 'application/assets/js/product/stockshape.js', array(), null, true );

            wp_enqueue_script( aa_app_suffix() . 'product-vuevars-compare', american_accent_plugin_base_url() . 'application/assets/js/product/compare.js', array(), null, true );

            wp_enqueue_script( aa_app_suffix() . 'product-vuevars-ideagallery', american_accent_plugin_base_url() . 'application/assets/js/product/ideagallery.js', array(), null, true );

            wp_enqueue_script( aa_app_suffix() . 'product-vuevars-maingallery', american_accent_plugin_base_url() . 'application/assets/js/product/maingallery.js', array(), null, true );

            wp_enqueue_script( aa_app_suffix() . 'product-vuevars-imprintoptions', american_accent_plugin_base_url() . 'application/assets/js/product/imprintoptions.js', array(), null, true );

            wp_enqueue_script( aa_app_suffix() . 'product-vuevars-pricing', american_accent_plugin_base_url() . 'application/assets/js/product/pricing.js', array(), null, true );

            wp_enqueue_script( aa_app_suffix() . 'product-vuevars-template', american_accent_plugin_base_url() . 'application/assets/js/product/template.js', array(), null, true );
            
            wp_enqueue_script( aa_app_suffix() . 'product-vuevars', american_accent_plugin_base_url() . 'application/assets/js/productvuevars.js', array(), null, true );

            wp_enqueue_script( aa_app_suffix() . 'product-vue', american_accent_plugin_base_url() . 'application/assets/js/productvue.js', array(), null, true );

            wp_enqueue_script( aa_app_suffix() . 'product-v2', american_accent_plugin_base_url() . 'application/assets/js/productv2.js', array(), null, true );

            $vdsauth = carbon_get_theme_option( 'aa_admin_settings_vdsauthkey' );
            $vdssupp = carbon_get_theme_option( 'aa_admin_settings_vdssuppid' );
            if(!empty($vdsauth) && !empty($vdssupp)) {
                wp_enqueue_script( aa_app_suffix() . 'sage-vds', 
                'https://vds.sage.net/service/ws.dll/SuppVDSInclude?V=100&AuthKey='.$vdsauth
                , array(), null, true );
            }

        });

    }

}