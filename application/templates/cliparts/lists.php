<table class="wp-list-table widefat fixed striped pages mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>REF ID</th>
            <th><strong>Clip Art Category</strong></th>
            <th :class="`sorted ${sort}`">
                <a href="javascript:void(0)" @click.prevent="sorting">
                    <span>Priority</span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th></th>
        </tr>
    </thead>
    <tbody v-if="!clipart.loading && clipart.data">
        <tr v-for="(ca, index) in clipart.data" :key="`category-index-${index}`">
            <td>{{ ca.id }}</td>
            <td>{{ ca.hid }}</td>
            <td>{{ ca.clipartcategory }}</td>
            <td>{{ ca.priority }}</td>
            <td>
                <div v-if="!inactive">
                    <a href="javascript:void(0)" @click.prevent="formInputs(true, {...ca, clipartdata: inputJson(ca.clipartdata, 'clipartdata'), index}, `_id=${ca.hid}`)" class="button mb-1">Edit</a>
                    <button @click="updateStatus(ca.hid, index, 0)" class="button mb-1">{{ langs.delete }}</button>
                </div>
                <div v-else>
                    <button @click="updateStatus(ca.hid, index, 1)" class="button mb-1">{{ langs.restore }}</button>
                    <button @click="forceDelete(ca.hid, index)" class="button mb-1">{{ langs.forceDelete }}</button>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<p v-if="clipart.loading" style="text-align:center;">Loading...</p>
<p v-if="!clipart.loading && !clipart.data.length" style="text-align:center;">
    There is no clipart.
</p>

<div v-if="pagination.metas && pagination.metas.total > 0">
<vue-paginate 
v-model="pagination.page"
:page-count="getPaginationCount"
:page-range="10"
:click-handler="paginateClipArts"
:container-class="'pagination'"
:page-class="'page-item'"
:first-last-button="false"
/>    
</div>