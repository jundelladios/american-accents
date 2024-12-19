<?php if( carbon_get_theme_option('aa_admin_settings_vdsapiaccid') && carbon_get_theme_option('aa_admin_settings_vdsapiauthtoken') && carbon_get_theme_option('aa_admin_settings_vdsapisupplierid') && carbon_get_theme_option('aa_admin_settings_vdsapiversion') ): ?>

<div v-cloak class="wrap" id="sageBuilderController">

    <div v-if="!product.loading && product.data.product">

        <h3 class="wp-heading-inline">
            <a :href="`?page=american-accents-products&productId=${product.data.product.hid}`">{{ product.data.product.product_name }}</a> &#8250; 
            SAGE Data Builder for <span :style="`color:${product.data.productline.printmethod.method_hex};`">{{product.data.product_method_combination_name}}</span>
        </h3>

        <div class="mt-5" v-if="!vdssage.loading && sageProducts.length">

            <a href="#" 
            @click.prevent="sageproduct.selected=null"
            class="button mr-1"
            :style="[
                sageproduct.selected==null ? { 'background': '#2271b1', 'color': '#ffffff' } : ''
            ]"
            >ALL PRODUCTS</a>

            <a href="#" 
            v-for="(sp, spindex) in sageProducts"
            :key="`sage-product-${spindex}`"
            @click.prevent="() => {
                sageproduct.selected=sp.vdsproductid;
                fetchSageInitProduct(sp.vdsproductid);
            }"
            :style="[
                sageproduct.selected==sp.vdsproductid ? { 'background': '#2271b1', 'color': '#ffffff' } : ''
            ]"
            class="button mr-1">{{sp.vdsid}}</a>

            
            <p>Click any of these products for {{ product.data.product_method_combination_name }} to load and update the individual SAGE product feed.</p>

            <div v-if="sageProducts.length && !sageproduct.selected">
                <form @submit.prevent="getSearchedProductFeed">
                    <input type="text" v-model="sageproduct.reference" placeholder="Enter reference product name" style="width:100%;max-width: 300px;">
                    <p>Search product for reference and press ENTER to fetch the product feed.</p>
                </form>
                <p style="color: green;">Note: You're choosing ALL PRODUCTS, changes may apply to all products mentioned above. 
                <br>
                REFERENCE PRODUCT: <span>{{sageProducts[sageproduct.productAllIndexer].vdsid}} ({{sageProducts[sageproduct.productAllIndexer].vdsproductid}})</span></p>
            </div>

        </div>
        <div style="margin-top: 50px;" v-if="vdssage.loading">Loading Sage Products...</div>

    </div>

    <div v-if="product.loading" class="mt-5 text-center">Loading...</div>

    <div v-if="!sageproduct.loading && sageproduct.data">
        <?php require_once( american_accent_plugin_base_dir() . 'application/templates/sagedatabuilder/form.php' ); ?>
    </div>

    <div v-if="sageproduct.loading">
        Loading Sage Product Feed...
    </div>


</div>


<script type="text/javascript">
    var productIDSage = "<?php echo isset($_GET['sageDataBuilder']) ? $_GET['sageDataBuilder'] : null; ?>";
</script>
<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/sagedatabuilder/index.js'; ?>"></script>


<?php else: ?>

    <div class="notice notice-error">
        <p>You are not allowed to access this page, make sure you entered the all API details in <a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=crb_carbon_fields_container_american_accent_settings.php" target="_blank">AA Settings</a></p>
    </div>

<?php endif; ?>