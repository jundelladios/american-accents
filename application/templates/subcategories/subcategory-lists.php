<?php global $pagenow; ?>
<table class="wp-list-table widefat fixed striped pages">
    <thead>
        <tr>
            <th>ID</th>
            <th>REF ID</th>
            <th><strong>Name</strong></th>
            <th></th>
            <th :class="`sorted ${sort}`">
                <a href="javascript:void(0)" @click.prevent="sorting">
                    <span>Priority</span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th></th>
        </tr>
    </thead>
    <tbody v-if="!loadingSubCategory && subcategories.length">
        <tr v-for="(sub, index) in subcategories" :key="`subcategory-index-${index}`">
            <td>{{ sub.id }}</td>
            <td>{{ sub.hid }}</td>
            <td>{{ sub.sub_name }}</td>
            <td><a :href="`/product/${selectedCategory.cat_slug}/${sub.sub_slug}`" target="_blank"><?php echo home_url(); ?>/product/{{selectedCategory.cat_slug}}/{{sub.sub_slug}}</a></td>
            <td>{{ sub.priority }}</td>
            <td>
                <div v-if="!inactive">
                    <a href="javascript:void(0)" @click.prevent="formInputs(true, {index,...sub, seo_content: inputJson(sub.seo_content, 'seo_content')}, `_id=${sub.hid}`)" class="button">Edit</a>
                    <a :href="`<?php menu_page_url( $_GET['page'] ); ?>&productLines=${categoryId}-${sub.hid}`" class="button mb-1">Product Lines</a>
                    <button @click="updateStatus(sub.hid, index, 0)" class="button mb-1">{{ langs.delete }}</button>
                </div>
                <div v-else>
                    <button @click="updateStatus(sub.hid, index, 1)" class="button mb-1">{{ langs.restore }}</button>
                    <button @click="forceDelete(sub.hid, index)" class="button mb-1">{{ langs.forceDelete }}</button>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<p v-if="!loadingSubCategory && !subcategories.length" style="text-align:center;">
    <span v-if="!categoryId">Please select category.</span>
    <span v-else>There is no subcategory on this category.</span>
</p>
<p v-if="loadingSubCategory" style="text-align:center;">Loading...</p>

<div v-if="pagination.metas && pagination.metas.total > 0">
<vue-paginate 
v-model="pagination.page"
:page-count="getPaginationCount"
:page-range="10"
:click-handler="paginateSubCategories"
:container-class="'pagination'"
:page-class="'page-item'"
:first-last-button="false"
/>    
</div>