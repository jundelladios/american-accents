<?php
/**
 * @package AA_Project
 * 
 * Wordpress Media Handler
 * 
 */

namespace Api;

class Media {

    private static $sizes = [300,400,600,800,1000,1200,1400,1600,1800,1920];

    private static $defaultImgs = [
        'thumbnail_size_h', 
        'thumbnail_size_w', 
        'medium_size_h', 
        'medium_size_w',
        'large_size_h',
        'large_size_w'
    ];

    public static function init() {

        // remove default wp image
        foreach( self::$defaultImgs as $img ):
            update_option( $img, 0, 0, false );
        endforeach;

    }

    public static function generate() {

        foreach( self::$sizes as $size ):
            add_image_size( 'aa_image_'.$size.'x', $size, $size, false );
        endforeach;

    }


    public static function imageURLCDN($url) {

        if( defined( '_APP_IMG_CDN' ) && _APP_IMG_CDN ) { 

            $cdn = _APP_IMG_CDN;

            return "$cdn/q_lossless+to_webp+ret_img/$url";

        }

        return $url;

    }

    public static function imageproxy($url) {

        if( defined( '_APP_IMG_CDN' ) && _APP_IMG_CDN ) {

            $cdn = _APP_IMG_CDN;

            return "
            $cdn/q_lossless+w_400+to_webp+ret_img/$url 400w,
            $cdn/q_lossless+w_600+to_webp+ret_img/$url 600w,
            $cdn/q_lossless+w_800+to_webp+ret_img/$url 800w,
            $cdn/q_lossless+w_1600+to_webp+ret_img/$url 1600w,
            $cdn/q_lossless+w_1920+to_webp+ret_img/$url 1920w,
            $cdn/q_lossless+w_2050+to_webp+ret_img/$url 2050w
            ";

        }

        return $url;

    }

    public static function getImage($id) {

        $imageproxy = carbon_get_theme_option( 'aa_admin_settings_imgproxy' );

        if( $id && $imageproxy ) {

            $alt = get_post_meta($id, '_wp_attachment_image_alt', TRUE);

            $title = get_the_title($id);

            $url = wp_get_attachment_image_src( $id, 'full' );

            $allowedResizeCdn = ['png', 'jpg', 'jpeg', 'gif'];

            $ext = pathinfo($url[0], PATHINFO_EXTENSION);

            if( !in_array( $ext, $allowedResizeCdn ) ) { return null; }

            $finalsrc = "$imageproxy/to_webp,ret_wait/".$url[0];

            $preloader = "$imageproxy/to_webp,w_200,ret_wait/".$url[0];

            return array(
                'src' => $url[0],
                'preloader' => $preloader,
                'finalsrc' => $finalsrc,
                'hi_res' => $url[0],
                'srcset' => self::imageproxy( $url[0] ),
                'alt' => $alt ? $alt : $title,
                'title' => $title,
                'id' => $id,
                'width' => $url[1],
                'height' => $url[2],
                'attr_size' => null
            );

        }

        return self::getImageWP($id);

    }

    public static function getImageWP($id) {

        if( $id ) {

            $alt = get_post_meta($id, '_wp_attachment_image_alt', TRUE);

            $title = get_the_title($id);

            $url = wp_get_attachment_image_url( $id, 'full' );

            $availableSizes = get_intermediate_image_sizes();

            $ret[] = array(
                'breakpoint' => 2050,
                'url' => $url,
                'size' => 'original'
            );

            $templates = [];

            foreach( $availableSizes as $size ):

                $attachment = wp_get_attachment_image_src( $id, $size );

                if( $attachment[3] && preg_match("/aa_image/i", $size) ) {

                    $ret[] = array(
                        'breakpoint' => $attachment[1],
                        'url' => $attachment[0],
                        'size' => $size
                    );
                    
                }

            endforeach;

            if( $ret ) {

                $srcsets = [];
                usort($ret, function($a,$b) {
                    return $a['breakpoint'] <=> $b['breakpoint'];
                });

                $minBpKey = array_search( min(array_column( $ret, 'breakpoint' )), array_column( $ret, 'breakpoint' ) );
                $maxBpKey = array_search( max(array_column( $ret, 'breakpoint' )), array_column( $ret, 'breakpoint' ) );
                
                $bp = 0;
                $indeximg = 0;
                foreach( $ret as $imgs ):
                    if( isset( self::$sizes[$indeximg] ) ) {
                        $bp = self::$sizes[$indeximg];
                    } else {
                        $bp = $imgs['breakpoint'];
                    }
                    $srcsets[] = $imgs['url'] . ' ' . $bp.'w';
                    $indeximg++;
                endforeach;

                return array(
                    'src' => is_int($minBpKey) ? $ret[$minBpKey]['url'] : null,
                    'finalsrc' => is_int($minBpKey) ? $ret[$minBpKey]['url'] : null,
                    'srcset' => join(", \n", $srcsets),
                    'hi_res' => is_int($maxBpKey) ? $ret[$maxBpKey]['url'] : null,
                    'alt' => $alt,
                    'title' => $title,
                    'id' => $id,
                    'width' => $url[1],
                    'height' => $url[2]
                );

            }

            return null;

        }

        return null;

    }

    public static function getImageByURL($url) {

        if( !$url ) { return null; }

        global $wpdb;

        // $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url )); 

        // $id = $attachment ? $attachment[0] : null; 
        $id = attachment_url_to_postid( esc_url($url) );

        return self::getImage( $id );

    }

    public static function getImageByID($id) {

        if( !$id ) { return null; }

        return self::getImage($id);

    }

    public static function templateToMedia($path, $title) {

        $image_url = $path;

        $upload_dir = wp_upload_dir();

        $image_data = file_get_contents( $image_url );

        $filename = basename( $image_url );

        $filenameNoExt = pathinfo($filename, PATHINFO_FILENAME);

        if ( wp_mkdir_p( $upload_dir['path'] ) ) {
            $file = $upload_dir['path'] . '/' . $filename;
        }
        else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }

        if( file_exists( $file ) ) {

            return self::getImageByID(attachment_url_to_postid(self::pathToURL( $file )));

        }

        file_put_contents( $file, $image_data );

        $wp_filetype = wp_check_filetype( $filename, null );

        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => $filenameNoExt,
            'post_content' => '',
            'post_status' => 'inherit'
        );

        self::generate();

        $attach_id = wp_insert_attachment( $attachment, $file );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        update_post_meta($attach_id, '_wp_attachment_image_alt', $title);
        return self::getImageByID($attach_id);

    }

    public static function urlToPath($ext) {

        return str_replace( 
            wp_get_upload_dir()['baseurl'], 
            wp_get_upload_dir()['basedir'], 
            $ext
        );
    }

    public static function pathToURL($ext) {

        return str_replace( 
            wp_get_upload_dir()['basedir'], 
            wp_get_upload_dir()['baseurl'], 
            $ext
        ); 

    }

    public static function fallback() {

        return american_accent_plugin_base_url() . 'application/assets/img/square-placeholder.png';

    }

}