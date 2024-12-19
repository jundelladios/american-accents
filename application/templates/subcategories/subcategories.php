<div v-cloak id="subcategoriesControllerVue" class="wrap">

    <div v-if="!loadingCategory">

        <div>
            <h1 class="wp-heading-inline">Subcategories</h1>

            <a v-if="categoryId" href="javascript:void(0)" @click.prevent="formInputs(true, {...defaultValue})" class="page-title-action">Add New</a>

            <a href="<?php menu_page_url( aa_app_suffix() . 'categories' ); ?>" class="page-title-action">Categories</a>

            <hr class="wp-header-end">

            <ul class="subsubsub">
                <li class="all">
                    <a href="javascript:void(0)" @click.prevent="toggleActive" class="current" aria-current="page">
                        <span v-if="inactive">Active Subcategories</span>
                        <span v-else>Inactive Subcategories</span>
                    </a>
                </li>
                <li v-if="!loadingCategory && categories.length">
                    | Categories:
                    <select v-model="categoryId" style="width:160px;">
                        <option v-for="(category, index) in categories" :key="`category-index-${index}`" :value="category.hid">
                            {{ category.cat_name }}
                        </option>
                    </select>
                </li>
                <li v-if="categoryId">
                    | <button @click="autoassignCatalog" type="button" class="button ml-1 mb-3" style="margin-top: -5px;">Auto-assign Catalog Pages</button>
                </li>
            </ul>
            
            <form action="/" @submit.prevent="getSubcategories">
                <p class="search-box mb-3">
                    <input type="search" v-model="search" id="post-search-input" name="s" value="">
                    <input type="submit" id="search-submit" class="button" value="Search subcategory">
                </p>
            </form>

            <?php require_once(american_accent_plugin_base_dir() . 'application/templates/subcategories/subcategory-lists.php'); ?>
            
            <?php require_once(american_accent_plugin_base_dir() . 'application/templates/subcategories/subcategory-form.php'); ?>
            
        </div>

    </div>
</div>
<script type="text/javascript">
    <?php if( isset( $_GET['categoryId'] ) && !empty( $_GET['categoryId'] ) ): ?>
        var category_ID = '<?php echo $_GET['categoryId']; ?>';
    <?php else: ?>
        var category_ID = null;
    <?php endif; ?>
</script>
<script src="<?php echo american_accent_plugin_base_url() . '/application/templates/subcategories/subcategories.js'; ?>"></script>