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
    <tbody v-if="!loading && categories">
        <tr v-for="(cat, index) in categories" :key="`category-index-${index}`">
            <td>{{ cat.id }}</td>
            <td>{{ cat.hid }}</td>
            <td>{{ cat.cat_name }}</td>
            <td><a :href="`/product/${cat.cat_slug}`" target="_blank"><?php echo home_url(); ?>/product/{{cat.cat_slug}}</a></td>
            <td>{{ cat.priority }}</td>
            <td>
                <div v-if="!inactive">
                    <a href="javascript:void(0)" @click.prevent="formInputs(true, {...cat, index, seo_content: inputJson(cat.seo_content, 'seo_content')}, `_id=${cat.hid}`)" class="button mb-1">Edit</a>
                    <button @click="updateStatus(cat.hid, index, 0)" class="button mb-1">{{ langs.delete }}</button>
                </div>
                <div v-else>
                    <button @click="updateStatus(cat.hid, index, 1)" class="button mb-1">{{ langs.restore }}</button>
                    <button @click="forceDelete(cat.hid, index)" class="button mb-1">{{ langs.forceDelete }}</button>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<p v-if="loading" style="text-align:center;">Loading...</p>
<p v-if="!loading && !categories.length" style="text-align:center;">
    There is no category.
</p>

<div v-if="pagination.metas && pagination.metas.total > 0">
<vue-paginate 
v-model="pagination.page"
:page-count="getPaginationCount"
:page-range="10"
:click-handler="paginateCategories"
:container-class="'pagination'"
:page-class="'page-item'"
:first-last-button="false"
/>    
</div>