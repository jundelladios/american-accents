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
get_header();
?>

<script>
 var plineVar = <?php echo json_encode($plineVar); ?>;
</script>

<div id="main" class="site-main">
    <div class="fsubcategorytemplate mb-150">
        
        <div v-cloak id="subcategoryVueController">
            <!-- vue html template binding client side -->

            <!-- preparation for ssr here -->
        </div>

        <div class="v-preloader">

            <?php require_once plugin_dir_path( __FILE__ ) . '/page-subcategories/preloader.php';  ?>

            <div class="frontend-desktop-only">
                <?php require_once plugin_dir_path( __FILE__ ) . '/page-subcategories/preloader-desktop.php';  ?>
            </div>
            <div class="frontend-mobile-only">
                <?php require_once plugin_dir_path( __FILE__ ) . '/page-subcategories/preloader-mobile.php';  ?>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
// json data
var productlinejson = <?php echo json_encode( $apiRequest ); ?>;
</script>
<?php
get_footer();