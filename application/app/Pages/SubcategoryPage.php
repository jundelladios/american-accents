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

class SubcategoryPage extends Page {

    private $subcategory = null;
    private $apiRequest = null;

    public function set( $param ) {

        $subcategory = (new Filters)->getProductLines($param);

        $apiRequest = aa_wp_request_handler( $subcategory );

        $this->subcategory = $apiRequest && isset($apiRequest['data'][0]) ? $apiRequest['data'][0] : null;
        
        $this->apiRequest = $apiRequest ? $apiRequest['data'] : null;

    }

    public function getJSONData() {

        return $this->apiRequest;

    }


    public function seo_content() {
        $seo = json_decode($this->subcategory['seo_content']);
        $seo->title = $this->subcategory['sub_name'];
        Page::seo($seo);
    }

    public function inventoryJsVars() {

        Assets::inventoryJsVars(array(
            'category' => $this->subcategory['cat_slug'],
            'subcategory' => $this->subcategory['sub_slug'],
            'productOrMethod' => ""
        ));

    }

    public function render() {  

        add_filter('template_redirect', array( $this, 'template' ));

    }

    public function template() {

        if( !$this->subcategory ) {
            
            Page::notfound();

            require_once american_accent_plugin_base_dir() . '/templates/page-empty-method.php';

            exit;
        }

        // 301 redirect if 1 product line
        if( count($this->apiRequest) <= 1 ) {

            wp_redirect( home_url( 'product/' . $this->subcategory['cat_slug'] ), 301 ); 
            
            exit();

        }


        Page::found();

        $this->seo_content();

        $this->inventoryJsVars();

        $this->assets();

        $apiRequest = $this->apiRequest;

        $plineVar = $this->subcategory;

        require_once american_accent_plugin_base_dir() . '/templates/page-subcategoriesv2.php';

        $categoryID = get_aa_hashID($this->subcategory['categoryID']);

        $subcategoryID = get_aa_hashID($this->subcategory['subcategoryID']);

        Page::adminEdit("subcategories&categoryId={$categoryID}&_id={$subcategoryID}", "Edit Subcategory");

        exit;

    }

    public function assets() {

        add_action( 'wp_enqueue_scripts', function() {

            Assets::inventoryAsset();

            wp_enqueue_script( aa_app_suffix() . 'lodash', american_accent_plugin_base_url() . 'application/assets/libs/lodash.js', array(), null, true );
            
            Assets::vue();

            Assets::vuetooltip();

            Assets::vueslick();

            wp_enqueue_script( aa_app_suffix() . 'custom-vue', american_accent_plugin_base_url() . 'application/assets/libs/custom-vue.js', array(), null, true );

            wp_enqueue_style( aa_app_suffix() . 'inventory-subcategory-page', american_accent_plugin_base_url() . 'application/assets/css/subcategory-page.css', array(), null, null );

            wp_enqueue_script( aa_app_suffix() . 'inventory-vue-infinite-scroll', american_accent_plugin_base_url() . 'application/assets/libs/infinite-scroll/scroll.js', array(), null, true );
            
            wp_enqueue_script( aa_app_suffix() . 'vue-components', american_accent_plugin_base_url() . 'application/assets/js/components.js', array(), null, true );

            // DESKTOP
            wp_enqueue_script( aa_app_suffix() . 'inventory-subcategoryvuevars-desktop', american_accent_plugin_base_url() . 'application/assets/js/subcategory/desktop.js', array(), null, true );
            
            // MOBILE
            wp_enqueue_script( aa_app_suffix() . 'inventory-subcategoryvuevars-mobile', american_accent_plugin_base_url() . 'application/assets/js/subcategory/mobile.js', array(), null, true );
            
            wp_enqueue_script( aa_app_suffix() . 'inventory-subcategoryvuevars', american_accent_plugin_base_url() . 'application/assets/js/subcategoryvuevars.js', array(), null, true );

            wp_enqueue_script( aa_app_suffix() . 'inventory-subcategoryvue', american_accent_plugin_base_url() . 'application/assets/js/subcategoryvue.js', array(), null, true );

        });

    }


    public function render_unprinted() {

        add_filter('template_redirect', array( $this, 'template_unprinted' ));

    }

    // public function template_unprinted() {

    //     if( !$this->subcategory ) {
            
    //         Page::notfound();

    //         require_once american_accent_plugin_base_dir() . '/templates/page-empty-method.php';

    //         exit;
    //     }

    //     $this->seo_content();

    //     $this->inventoryJsVars();

    //     $this->assets_unprinted();

    //     $apiRequest = $this->apiRequest;

    //     $plineVar = $this->subcategory;

    //     require_once american_accent_plugin_base_dir() . '/templates/page-subcategories-unprinted.php';

    //     $categoryID = get_aa_hashID($this->subcategory['categoryID']);

    //     $subcategoryID = get_aa_hashID($this->subcategory['subcategoryID']);

    //     Page::adminEdit("subcategories&categoryId={$categoryID}&_id={$subcategoryID}", "Edit Subcategory");

    //     exit;


    // }

    // public function assets_unprinted() {

    //     add_action( 'wp_enqueue_scripts', function() {

    //         Assets::inventoryAsset();

    //         wp_enqueue_script( aa_app_suffix() . 'lodash', american_accent_plugin_base_url() . 'application/assets/libs/lodash.js', array(), null, true );
            
    //         Assets::vue();

    //         wp_enqueue_style( aa_app_suffix() . 'inventory-category-page', american_accent_plugin_base_url() . 'application/assets/css/category-page.css', array(), null, null );

    //         wp_enqueue_style( aa_app_suffix() . 'inventory-plugin-jquery-ui-slider', american_accent_plugin_base_url() . '/application/assets/libs/jquery-ui/slider.css', array(), null, null );

    //         wp_enqueue_script('jquery-ui-core');

    //         wp_enqueue_script('jquery-ui-slider');

    //         Assets::vuetooltip();

    //         wp_enqueue_script( aa_app_suffix() . 'custom-vue', american_accent_plugin_base_url() . 'application/assets/libs/custom-vue.js', array(), null, true );

    //         wp_enqueue_script( aa_app_suffix() . 'vue-pagination', american_accent_plugin_base_url() . 'application/assets/libs/pagination/vue-paginate.js', array(), null, true );

    //         Assets::vueslick();

    //         wp_enqueue_script( aa_app_suffix() . 'vue-components', american_accent_plugin_base_url() . 'application/assets/js/components.js', array(), null, true );

    //         wp_enqueue_script( aa_app_suffix() . 'inventory-categoryvuevars', american_accent_plugin_base_url() . 'application/assets/js/subcategoryvue_unprinted/subcategoryvuevars.js', array(), null, true );

    //         wp_enqueue_script( aa_app_suffix() . 'inventory-categoryvue', american_accent_plugin_base_url() . 'application/assets/js/subcategoryvue_unprinted/subcategoryvue.js', array(), null, true );

    //     });

    // }

}