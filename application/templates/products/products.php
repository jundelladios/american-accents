<div v-cloak id="productsControllerVue" class="wrap">
    
<h1 class="wp-heading-inline">
Products</h1>

<a href="javascript:void(0)" v-if="filter.category && filter.subcategory" @click.prevent="formInputs(true, {...defaultValue, spec_copy: []});" class="page-title-action">Add New</a>

<hr class="wp-header-end">

<ul class="subsubsub">
    <li class="all">
        <a href="javascript:void(0)" @click.stop="toggleActive" class="current" aria-current="page">
            <span v-if="inactive">Active Products</span>
            <span v-else>Inactive Products</span>
        </a>
    </li>
    <li v-if="!categories.loading">
        <div v-if="categories.data.length">
            | Category: 
            <select v-model="filter.category" style="width:160px;">
                <option v-for="(category, index) in categories.data" :key="`category-index-${index}`" :value="category.hid">
                    {{ category.cat_name }}
                </option>
            </select>
        </div>
        <div v-else>| <small>Empty category</small></div>
    </li>
    <li v-if="filter.category && !subcategories.loading">
        <div v-if="subcategories.data.length">
            | Subcategory:
            <select v-model="filter.subcategory">
                <option v-for="(subcat, index) in subcategories.data" :key="`subcat-index-${index}`" :value="subcat.hid">
                    {{ subcat.sub_name }}
                </option>
            </select>
        </div>
        <div v-else>| <small>Empty subcategory</small></div>
    </li>

    <li v-if="pfilters.data.materials && pfilters.data.materials.length">
        <div>
            | Material Type:
            <select v-model="materialFilter">
                <option :value="null">All</option>
                <option v-for="(material, index) in pfilters.data.materials" :key="`material-index-${index}`" :value="material.material_type">
                    {{ material.material_type }}
                </option>
            </select>
        </div>
    </li>
    
</ul>

<form action="/" @submit.prevent="pagination.page = 1; fetchProducts();">
    <p class="search-box mb-3">
        <input type="search" id="post-search-input" name="s" v-model="search">
        <input type="submit" id="search-submit" class="button" value="Search Product">
    </p>
</form>

<?php require_once( american_accent_plugin_base_dir() . 'application/templates/products/lists.php' ); ?>

<?php require_once( american_accent_plugin_base_dir() . 'application/templates/products/form.php' ); ?>

<?php require_once( american_accent_plugin_base_dir() . 'application/templates/products/colors.php' ); ?>

<script type="text/javascript">
    <?php if( isset( $_GET['category'] ) && !empty( $_GET['category'] ) ): ?>
        var CATEGORY_ID = '<?php echo $_GET['category']; ?>';
    <?php else: ?>
        var CATEGORY_ID = null;
    <?php endif; ?>

    <?php if( isset( $_GET['subcategory'] ) && !empty( $_GET['subcategory'] ) ): ?>
        var SUBCATEGORY_ID = '<?php echo $_GET['subcategory']; ?>';
    <?php else: ?>
        var SUBCATEGORY_ID = null;
    <?php endif; ?>
</script>

<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/products/specs.js'; ?>"></script>

<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/products/products.js'; ?>"></script>