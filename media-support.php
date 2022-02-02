<?php
/**
 * Media Support
 *
 * @package AA_Project
 */

function aa_disable_medium_large_images($sizes) {
	unset($sizes['medium_large']);
    unset($sizes['1536x1536']);
    unset($sizes['2048x2048']);
	return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'aa_disable_medium_large_images');

// disable update media settings
Api\Media::init();


/** ---------------------------------- */
// comment out this code to avoid generate multiple images
// Api\Media::generate();

/** ----------------------------------- */
add_filter( 'big_image_size_threshold', '__return_false' );