<table class="wp-list-table widefat fixed striped pages mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>REF ID</th>
            <th><strong>Charge Name</strong></th>
            <th></th>
        </tr>
    </thead>
    <tbody v-if="!charges.loading && charges.data">
        <tr v-for="(charge, index) in charges.data" :key="`category-index-${index}`">
            <td>{{ charge.id }}</td>
            <td>{{ charge.hid }}</td>
            <td><span :class="`icon ${charge.icon} mr-2 icon-dark`"></span>{{ charge.charge_name }}</td>
            <td>
                <div v-if="!inactive">
                    <a href="javascript:void(0)" @click.prevent="formInputs(true, {...charge, index}, `_id=${charge.hid}`)" class="button mb-1">Edit</a>
                    <button @click="updateStatus(charge.hid, index, 0)" class="button mb-1">{{ langs.delete }}</button>
                </div>
                <div v-else>
                    <button @click="updateStatus(charge.hid, index, 1)" class="button mb-1">{{ langs.restore }}</button>
                    <button @click="forceDelete(charge.hid, index)" class="button mb-1">{{ langs.forceDelete }}</button>
                </div>

            </td>
        </tr>
    </tbody>
</table>
<p v-if="charges.loading" style="text-align:center;">Loading...</p>
<p v-if="!charges.loading && !charges.data.length" style="text-align:center;">
    There is no charges available.
</p>

<div v-if="pagination.metas && pagination.metas.total > 0">
<vue-paginate 
v-model="pagination.page"
:page-count="getPaginationCount"
:page-range="10"
:click-handler="paginateCharges"
:container-class="'pagination'"
:page-class="'page-item'"
:first-last-button="false"
/>    
</div>