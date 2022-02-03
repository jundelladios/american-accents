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
    var apiRequest = <?php echo json_encode($apiRequest); ?>;
    var subcategoryParam = '<?php echo get_query_var('subcategory'); ?>';
</script>

<div id="main" class="site-main">
    <h1 class="entry-title d-none"><?php echo $apiRequest['cat_name']; ?></h1>

    <div v-cloak id="categoryVueController">
        <!-- vue html template binding client side -->

        <!-- preparation for ssr here -->
    </div>
    
    <div class="v-preloader">
        <?php require_once plugin_dir_path( __FILE__ ) . '/page-categoriesv2/preloader.php' ?>
    </div>
</div>

<a href="javascript:void(0)" class="back_to_top">
    <span class="icon-topscroll icon"></span>
</a>
<?php
get_footer();
