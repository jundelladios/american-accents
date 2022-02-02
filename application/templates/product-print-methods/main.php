<div v-cloak class="wrap" id="productsPrintMethodControllerVue">
    <div v-if="!product.loading">
        <div v-if="product.data">
            <h3 class="wp-heading-inline">
            <a :href="`<?php menu_page_url( $_GET['page'] ); ?>&category=${product.data.category.hid}`">{{ product.data.category.cat_name }}</a> &#8250; 
            <a :href="`<?php menu_page_url( $_GET['page'] ); ?>&category=${product.data.category.hid}&subcategory=${product.data.subcategory.hid}`">{{ product.data.subcategory.sub_name }}</a> &#8250; 
            {{product.data.product_name}}
            </h3>

            <div class="d-flex align-items-center">
                <ul class="subsubsub">
                    <li class="all">
                        <a href="javascript:void(0)" @click.prevent="toggleActive" class="current" aria-current="page">
                            <span v-if="inactive">Active Product Print Method</span>
                            <span v-else>Inactive Product Print Method</span>
                        </a> |
                    </li>
                </ul>
                <?php menu_page_url( aa_app_suffix() ); ?>
                <a href="javascript:void(0)" @click.stop="addNew(); setSpecificationJson();" class="page-title-action mt-2 ml-3">Add New</a>
                <a href="javascript:void(0)" :href="`<?php menu_page_url( aa_app_suffix() . 'subcategories' ); ?>&productLines=${product.data.category.hid}-${product.data.subcategory.hid}`" class="page-title-action mt-2 ml-3">Product Lines</a>
            </div>

            <hr class="wp-header-end">

            <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/lists.php' ); ?>

            <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/form.php' ); ?>
                
            <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/colors/index.php' ); ?>

            <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/stockshapes/index.php' ); ?>

            <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/colors_stockshape/index.php' ); ?>

        </div>
        <div v-else>
            <div class="notice notice-error is-dismissible">
                <p>The page that you are looking for was not found.</p>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/product-print-methods/colors_stockshape/index.js'; ?>"></script>

<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/product-print-methods/colors/index.js'; ?>"></script>

<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/product-print-methods/stockshapes/index.js'; ?>"></script>

<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/product-print-methods/specs.js'; ?>"></script>

<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/product-print-methods/pmethod.js'; ?>"></script>