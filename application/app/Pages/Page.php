<?php
/**
 * @package AA_Project
 * 
 * Product Category Page
 * 
 */

namespace Api\Pages;

use Api\SeoContents;

class Page {

    public static function found() {

        global $wp_query;

        if ($wp_query->is_404) {

            $wp_query->is_404 = false;

        }
        
        header("HTTP/1.1 200 OK");

    }

    public static function notfound() {

        global $wp_query;

        if (!$wp_query->is_404) {

            $wp_query->is_404 = true;

        }
        
        header("HTTP/1.1 404 NOT_FOUND");

    }

    public static function seo( $seo ) {

        global $wp, $apiSeo;

        $apiSeo->set([
            'title' => $seo->title . " - " . get_bloginfo('name'),
            'description' => $apiSeo->_handle( $seo, 'description' ),
            'image' => $apiSeo->_handle( $seo, 'image' ),
            'url' => home_url( $wp->request )
        ]);

    }

    public static function adminLink() {

        return admin_url( 'admin.php?page=' . aa_app_suffix() );
    }



    public static function adminEdit( $adminLink, $text ) {

        if( !empty( $adminLink) && !empty( $text) && (current_user_can('editor') || current_user_can('administrator')) ):

            $link = self::adminLink().$adminLink;

            require_once american_accent_plugin_base_dir() . '/templates/page-editor.php';
    
        endif;

    }

}