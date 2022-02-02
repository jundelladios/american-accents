<?php
/**
 * YOAST SEO SUPPORT
 *
 * @package AA_Project
 */

add_filter('wpseo_title','aa_inventory_seo_title',10,1);

add_filter('wpseo_opengraph_title', 'aa_inventory_seo_title', 10, 1);

function aa_inventory_seo_title( $title ) {

    global $apiSeo;

    if( $apiSeo->getTitle() ) {

        return $apiSeo->getTitle();

    }

    return $title;

}

add_filter( 'wpseo_metadesc', 'aa_inventory_seo_description', 10, 1 );

add_filter( 'wpseo_opengraph_desc', 'aa_inventory_seo_description', 10, 1 );

function aa_inventory_seo_description( $description ) {

    global $apiSeo;

    if( $apiSeo->getDescription() ) {

        return $apiSeo->getDescription();

    }

    return $description;

}


add_filter( 'wpseo_add_opengraph_images', 'aa_inventory_og_image' );

function aa_inventory_og_image( $img ) {

    global $apiSeo;

    if( $apiSeo->getImage() ) {

        $img->add_image(array(
            'url' => $apiSeo->getImage(),
            'width' => 600,
            'height' => 314
        ));

    }
};

add_filter( 'wpseo_opengraph_url', 'aa_inventory_og_url');

function aa_inventory_og_url( $url ) {

    global $apiSeo;

    if( $apiSeo->getURL() ) {

        return $apiSeo->getURL();

    }

    return $url;

}
