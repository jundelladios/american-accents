<?php
/**
 * The page category template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AA_PROJECT
 */
get_header(); ?>
<div class="container" style="margin-top: 50px;">
    <div class="row align-items-center" style="min-height:50vh;">
        <!-- <div class="col-md-4">
            <img src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/notfound.png'; ?>" class="lazyload lz-blur">
        </div> -->
        <div class="col-md-8">
            <h4 class="font-weight-bold">This subcategory doesn't have product line.</h4>
            <p>Please get back soon.</p>
        </div>
    </div>
</div>
<?php
get_footer();