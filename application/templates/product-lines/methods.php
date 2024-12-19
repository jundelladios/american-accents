<div v-cloak id="productLinesController" class="wrap">

    <div v-if="!tree.loading">

        <div v-if="tree.data && subcategory">

            <h3 class="wp-heading-inline"><a :href="`<?php menu_page_url( $_GET['page'] ); ?>&categoryId=${tree.data.hid}`">{{ tree.data.cat_name }}</a> &#8250; {{subcategory.sub_name}} &#8250; Product Lines</h3>

            <div class="d-flex align-items-center" v-if="!methods.loading && methods.data.length">

                <ul class="subsubsub">
                    <li class="all">
                        <a href="javascript:void(0)" @click.prevent="toggleActive" class="current" aria-current="page">
                            <span v-if="inactive">Active Product Line</span>
                            <span v-else>Inactive Product Line</span>
                        </a> |
                    </li>
                </ul>

                <a href="javascript:void(0)" @click.stop="addNew" class="page-title-action mt-2 ml-3">Add New</a>

                <a href="<?php menu_page_url( aa_app_suffix() . 'print-methods' ); ?>" class="page-title-action mt-2 ml-3">Print Method</a>

            </div>

            <hr class="wp-header-end">

            <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/lists.php' ); ?>

            <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/forms.php' ); ?>

            <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/colors/form.php' ); ?>

            <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/imprint.php' ); ?>

            <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/premium_backgrounds/index.php' ); ?>

            <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/stockshape/index.php' ); ?>

        </div>

        <div v-else>

            <div class="notice notice-error is-dismissible">
                <p>The page that you are looking for was not found.</p>
            </div>

        </div>

    </div>

</div>

<div v-cloak id="plinechargesController" class="wrap">

    <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/charges.php' ); ?>

</div>


<script src="<?php echo american_accent_plugin_base_url() . '/application/templates/product-lines/imprint.js'; ?>"></script>

<script src="<?php echo american_accent_plugin_base_url() . '/application/templates/product-lines/stockshape/index.js'; ?>"></script>

<script src="<?php echo american_accent_plugin_base_url() . '/application/templates/product-lines/premium_backgrounds/index.js'; ?>"></script>

<script src="<?php echo american_accent_plugin_base_url() . '/application/templates/product-lines/charges.js'; ?>"></script>

<script src="<?php echo american_accent_plugin_base_url() . '/application/templates/product-lines/methods.js'; ?>"></script>