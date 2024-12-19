<?php
/**
 * The page sub category template file
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
get_header();

?>

<script type="text/javascript">
    var productJSON = <?php echo json_encode( $singleProduct ); ?>;
</script>

<div id="main" class="site-main">

    <div class="fproducttemplate mb-150">

        <?php require_once plugin_dir_path( __FILE__ ) . '/productv3/print-header.php';  ?>

        <div v-cloak id="productVueController" class="position-relative"> -->
            <!-- vue html template binding client side -->
            <!-- preparation for ssr here -->
        </div>

        <div class="v-preloader">
            <?php require_once plugin_dir_path( __FILE__ ) . '/productv3/preloader.php';  ?>
        </div>
        
        <?php require_once plugin_dir_path( __FILE__ ) . '/productv3/print-footer.php';  ?>

    </div>

</div>

<a href="javascript:void(0)" class="back_to_top">
    <span class="icon-topscroll icon"></span>
</a>

<?php
get_footer();