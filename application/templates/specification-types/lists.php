<table class="wp-list-table widefat fixed striped pages">
    <thead>
        <tr>
            <th>ID</th>
            <th>REF ID</th>
            <th><strong>Title</strong></th>
            <th :class="`sorted ${sort}`">
                <a href="javascript:void(0)" @click.prevent="sorting">
                    <span>Priority</span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th></th>
        </tr>
    </thead>
    <tbody v-if="!loading && spectypes">
        <tr v-for="(spec, index) in spectypes" :key="`category-index-${index}`">
            <td>{{ spec.id }}</td>
            <td>{{ spec.hid }}</td>
            <td>{{ spec.title }}</td>
            <td>{{ spec.priority }}</td>
            <td>
                <div v-if="!inactive">
                    <a href="javascript:void(0)" 
                    @click.prevent="formInputs(true, 
                        {
                            ...spec, 
                            index,
                            customfield: setGroupData([...spec.cfield]),
                            specs: setSpecFieldData([...spec.spc])
                        },
                        `_id=${spec.hid}`)" 
                    class="button mb-1">Edit</a>
                    <a href="javascript:void(0)" class="button mb-1" @click.stop="duplicateEntry(spec.hid)">Duplicate</a>
                    <button @click="updateStatus(spec.hid, index, 0)" class="button mb-1">{{ langs.delete }}</button>
                </div>
                <div v-else>
                    <button @click="updateStatus(spec.hid, index, 1)" class="button mb-1">{{ langs.restore }}</button>
                    <button @click="forceDelete(spec.hid, index)" class="button mb-1">{{ langs.forceDelete }}</button>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<p v-if="loading" style="text-align:center;">Loading...</p>
<p v-if="!loading && !spectypes.length" style="text-align:center;">
    There is no specification types.
</p>

<div v-if="pagination.metas && pagination.metas.total > 0">
<vue-paginate 
v-model="pagination.page"
:page-count="getPaginationCount"
:page-range="10"
:click-handler="paginateSpecs"
:container-class="'pagination'"
:page-class="'page-item'"
:first-last-button="false"
/>    
</div>