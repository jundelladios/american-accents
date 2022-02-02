<?php
/**
 * GET DATA FUNCTIONS
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AA_Project
 */

 use Api\Hasher;

 use Brick\Money\Money;

use Brick\Money\Context\AutoContext;

use Brick\Money\Context\CustomContext;

function aa_formatted_money( $value, $nocurrency = false, $isautocontext = false ) {
    
    if( $value ) {

        $formatted = Money::of($value, 'USD', $isautocontext ? new AutoContext() : new CustomContext(3));

        if( !$nocurrency ) {

            return $formatted->formatTo('en_US');

        }

        return str_replace('$', null, $formatted->formatTo('en_US')); 

    }

    return null;

}


 function aa_wp_request_handler( $thing ) {

    if( !isset( $thing->status ) || $thing->status === 200 ) {

        return $thing;

    }

    return null;

}

function get_aa_category( $request ) {
    
    return (new Api\Crud\Categories\Retrieve)->get( $request );

}


function get_aa_filters_data( $request ) {

    return (new Api\Crud\PublicRoutes\Filters)->getFilter( $request );

}

function get_aa_product_lines( $request ) {

    return (new Api\Crud\PublicRoutes\Filters)->getProductLines( $request );

}

function get_aa_products( $request ) {

    return (new Api\Crud\PublicRoutes\Filters)->getProducts( $request );

}

function get_aa_single_product( $request ) {

    return (new Api\Crud\PublicRoutes\Filters)->getSingleProduct( $request );

}

function get_aa_product_sizes( $request ) {

    return (new Api\Crud\PublicRoutes\Filters)->getSizes( $request );

}

function get_aa_hashID( $id ) {

    return Hasher::encode( $id );

}

// SEO Image Speed Boost
function aa_lazyimg( $attrs = [] ) {

    $defaults = [
        'class' => '',
        'src' => '',
        'alt' => ''
    ];

    $attrs = array_merge( $defaults, $attrs );
    
    $srcset = \Api\Media::imageproxy($attrs['src']);

    $imgurl = \Api\Media::imageURLCDN($attrs['src']);

    ob_start();
    $atts = "";
    foreach( $attrs as $key => $val ) {
        if( $key != 'src' && $key != 'class' ) {
            $atts .= $key;
            $atts .= '="' . $val . '" ';
        }
    }

    ?>
    <img 
        class="lazyload lz-blur <?php echo $attrs['class']; ?>" 
        <?php echo $atts; ?>
        <?php if( defined( '_APP_IMG_CDN' ) && _APP_IMG_CDN ): ?>
            src="<?php echo $imgurl; ?>"
            data-srcset="<?php echo $srcset; ?>"
            width="auto"
            height="auto"
            data-sizes="auto"
            alt="<?php echo $attrs['alt'] ?>"
        <?php else: ?>
            src="<?php echo $imgurl; ?>"
        <?php endif; ?>
    />
    <?php
    echo ob_get_clean();
}

// SEO Background Image Speed Boost
function aa_lazyBg( $url, $style="" ) {

    $str = "";

    $image = \Api\Media::imageproxy($url);

    if( defined( '_APP_IMG_CDN' ) && _APP_IMG_CDN ) {

        $str .= 'data-bgset="'.$image.'"';

    } else {

        $str .= 'style="background: url('.$url.');"';

    }

    echo $str;

} 

// image proxy function
function aa_image_proxy( $url ) {

    return \Api\Media::imageproxy( $url );

}



function aa_json_array( $json ) {

    if( $json ) {

        $ret = json_decode( $json );

        if( is_array( $ret ) ) {

            return $ret;
            
        }

    }

    return [];

}


function aa_json_data( $json ) {

    if( $json ) {

        $ret = json_decode( $json );

        return $ret;

    }

    return null;
    
}