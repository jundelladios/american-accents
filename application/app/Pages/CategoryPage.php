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

use Api\Crud\Categories\Retrieve;

class CategoryPage extends Page {

    private $category = null;

    public function set( $param ) {

        $category = (new Retrieve)->get($param);

        $apiRequest = aa_wp_request_handler( $category );

        $this->category = $apiRequest ? $apiRequest['data'][0] : null ;
    }

    public function render() {

        add_filter( 'query_vars', array( $this, 'queryVars' ) );

        add_filter('template_redirect', array( $this, 'template' ));

    }


    public function template() {

        if( !$this->category ) {

            return;
            
        }

        Page::found();

        $this->seo_content();

        $this->inventoryJsVars();

        $this->assets();

        $apiRequest = $this->category;

        require_once american_accent_plugin_base_dir() . '/templates/page-categoriesv2.php';

        Page::adminEdit("categories&_id={$this->category['hid']}", "Edit Category");

        exit;

    }

    public function seo_content() {
        $seo = json_decode($this->category['seo_content']);
        $seo->title = $this->category['cat_name'];
        Page::seo($seo);
    }

    public function inventoryJsVars() {

        Assets::inventoryJsVars(array(
            'category' => $this->category['cat_slug']
        ));

    }

    public function assets() {

        add_action( 'wp_enqueue_scripts', function() {

            Assets::inventoryAsset();

            wp_enqueue_script( aa_app_suffix() . 'lodash', american_accent_plugin_base_url() . 'application/assets/libs/lodash.js', array(), null, true );
            
            Assets::vue();

            wp_enqueue_style( aa_app_suffix() . 'inventory-category-page', american_accent_plugin_base_url() . 'application/assets/css/category-page.css', array(), null, null );

            wp_enqueue_style( aa_app_suffix() . 'inventory-plugin-jquery-ui-slider', american_accent_plugin_base_url() . '/application/assets/libs/jquery-ui/slider.css', array(), null, null );

            wp_enqueue_script('jquery-ui-core');

            wp_enqueue_script('jquery-ui-slider');

            Assets::vuetooltip();

            wp_enqueue_script( aa_app_suffix() . 'custom-vue', american_accent_plugin_base_url() . 'application/assets/libs/custom-vue.js', array(), null, true );

            wp_enqueue_script( aa_app_suffix() . 'vue-pagination', american_accent_plugin_base_url() . 'application/assets/libs/pagination/vue-paginate.js', array(), null, true );

            Assets::vueslick();

            wp_enqueue_script( aa_app_suffix() . 'vue-components', american_accent_plugin_base_url() . 'application/assets/js/components.js', array(), null, true );

            wp_enqueue_script( aa_app_suffix() . 'inventory-categoryvuevars', american_accent_plugin_base_url() . 'application/assets/js/categoryvuevars.js', array(), null, true );

            wp_enqueue_script( aa_app_suffix() . 'inventory-categoryvue', american_accent_plugin_base_url() . 'application/assets/js/categoryvue.js', array(), null, true );

        });

    }

    public function queryVars($vars) {
        $vars[] = 'subcategory';
        return $vars;
    }

}