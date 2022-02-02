<table class="wp-list-table widefat fixed striped pages">
    <thead>
        <tr>
            <th>ID</th>
            <th>REF ID</th>
            <th><strong>Name</strong></th>
            <th><strong>slug</strong></th>
            <!-- <th><strong>Color</strong></th> -->
            <th><strong>Size</strong></th>
            <th><strong>Description</strong></th>
            <th :class="`sorted asc`">
                <a href="javascript:void(0)" @click.stop="sorting">
                    <span>Priority</span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th></th>
        </tr>
    </thead>
    <tbody v-if="!products.loading && products.data">
        <tr v-for="(product, index) in products.data" :key="`product-list-${index}`">
            <td>{{product.id}}</td>
            <td>{{product.hid}}</td>
            <td>{{product.product_name}}</td>
            <td>{{product.product_slug}}</td>
            <!-- <td>
                <div v-if="product.product_color_hex && product.product_color_details" class="d-flex align-items-center">
                    <span class="mr-1 color-module-v2" :style="`background: ${product.product_color_hex};`">
                        <span></span>
                    </span> 
                    <span v-if="product.product_color_details">{{product.product_color_details}}</span>
                </div>
                <span v-else>N/A</span>
            </td> -->
            <td>
                <div v-if="product.product_size_details">
                    <span v-if="product.product_size_details">{{product.product_size_details}}</span>
                </div>
                <span v-else>N/A</span>
            </td>
            <td>{{product.product_description}}</td>
            <td>{{product.priority}}</td>
            <td>
                <div v-if="!inactive">
                    <a href="javascript:void(0)" @click.prevent="formInputs(true, 
                        {
                            index,
                            ...product, 
                            specification_id: product.spechandler ? product.spechandler.hid : null,
                            specs_json: product.specification,
                            spec_copy: product.specification
                        }, `_id=${product.hid}`, true)" class="button mb-1">Edit</a>
                    <a :href="`<?php menu_page_url( $_GET['page'] ); ?>&productId=${product.hid}`" class="button mb-1">Print Methods</a>
                    <a class="button mb-1" @click.stop="moveProductIndex=index" href="javascript:void(0)">Move</a>
                    <!-- <a class="button mb-1" href="javascript: void(0);" @click.stop="pcolors.input.product_id=product.hid">Set Colors</a> -->
                    <a class="button mb-1" href="#">Export</a>
                    <button @click="updateStatus(product.hid, index, 0)" class="button mb-1">{{ langs.delete }}</button>
                </div>
                <div v-else>
                    <button @click="updateStatus(product.hid, index, 1)" class="button">{{ langs.restore }}</button>
                    <button @click="forceDeleteProduct(product.hid, index)" class="button">{{ langs.forceDelete }}</button>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<p v-if="!filter.category || !filter.subcategory" class="text-center" style="text-align:center;">Please select category and subcategory.</p>

<div v-else>

    <div v-if="!categories.loading && !subcategories.loading">

        <p v-if="products.loading" class="text-center" style="text-align:center;">Loading</p>

        <p v-if="!products.loading && !products.data.length" class="text-center" style="text-align:center;">There is no product available.</p>

    </div>

</div>


<div v-if="pagination.metas && pagination.metas.total > 0">
<vue-paginate 
v-model="pagination.page"
:page-count="getPaginationCount"
:page-range="10"
:click-handler="paginateProducts"
:container-class="'pagination'"
:page-class="'page-item'"
:first-last-button="false"
/>    
</div>

<p class="d-block"><strong>Note:</strong> you can only move product in subcategories if there is no product combo on it.</p>


<div v-if="moveProductIndex!=null && getMovableCategories.length" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content">
            <a href="javascript:void(0)" @click.prevent="moveProductIndex=null" class="aa-close">&times;</a>
            <h1 v-if="getPendingRemoveProduct" class="wp-heading-inline">
                <span>Move {{ getPendingRemoveProduct.product_name }}.</span>
            </h1>
            <small class="d-block">Select subcategory where this product belongs to.</small>

            <div class="mt-3 mb-3">
                <ul>
                    <li v-for="(cat, index) in getMovableCategories" class="cursor-pointer" :key="`cat-lists-${index}`">
                        <div class="p-2" style="background: #dedede;" @click.stop="toggleSubcat(index)">{{ cat.cat_name }}</div>
                        <div :class="`subcat-move-${index}`" style="display: none;">
                            <ul :class="`mt-2 mb-3 ml-3`" v-if="cat.subcategories.length">
                                <li v-for="(sub, index2) in cat.subcategories" :key="`subcat-lists-${index2}`">
                                    <a href="javascript:void(0)" @click.stop="moveProduct(cat.hid, sub.hid)">- {{ sub.sub_name }}</a>
                                </li>
                            </ul>
                            <span class="d-block mt-2 mb-3 ml-3" v-else>Please add subcategory on this category.</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>