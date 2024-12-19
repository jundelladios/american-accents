<table class="wp-list-table widefat fixed striped pages">
    <thead>
        <tr>
            <th>ID</th>
            <th>REF ID</th>
            <th><strong>Imprint Type</strong></th>
            <th></th>
        </tr>
    </thead>
    <tbody v-if="!loading && imprints">
        <tr v-for="(imprint, index) in imprints" :key="`category-index-${index}`">
            <td>{{ imprint.id }}</td>
            <td>{{ imprint.hid }}</td>
            <td>{{ imprint.title }}</td>
            <td>
                <div v-if="!inactive">
                    <a href="javascript:void(0)" @click.prevent="formInputs(true, {...imprint, index}, `_id=${imprint.hid}`)" class="button mb-1">Edit</a>
                    <button @click="updateStatus(imprint.hid, index, 0)" class="button mb-1">{{ langs.delete }}</button>
                </div>
                <div v-else>
                    <button @click="updateStatus(imprint.hid, index, 1)" class="button mb-1">{{ langs.restore }}</button>
                    <button @click="forceDelete(imprint.hid, index)" class="button mb-1">{{ langs.forceDelete }}</button>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<p v-if="loading" style="text-align:center;">Loading...</p>
<p v-if="!loading && !imprints.length" style="text-align:center;">
    There is no imprint type.
</p>

<div v-if="pagination.metas && pagination.metas.total > 0">
<vue-paginate 
v-model="pagination.page"
:page-count="getPaginationCount"
:page-range="10"
:click-handler="paginateImprintTypes"
:container-class="'pagination'"
:page-class="'page-item'"
:first-last-button="false"
/>    
</div>