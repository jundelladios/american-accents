<table class="wp-list-table widefat fixed striped pages">
    <thead>
        <tr>
            <th>ID</th>
            <th>REF ID</th>
            <th><strong>[Prefix] Name</strong></th>
            <th><strong>slug</strong></th>
            <th><strong>Hex</strong></th>
            <th :class="`sorted ${sort}`">
                <a href="javascript:void(0)" @click.prevent="sorting">
                    <span>Priority</span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th></th>
        </tr>
    </thead>
    <tbody v-if="!loading && methods">
        <tr v-for="(method, index) in methods" :key="`method-index-${index}`">
            <td>{{ method.id }}</td>
            <td>{{ method.hid }}</td>
            <td><span v-if="method.method_prefix">[{{ method.method_prefix}}]</span> {{ method.method_name }} {{ method.method_name2 }}</td>
            <td>{{ method.method_slug }}</td>
            <td><span class="aa color-module" :style="`background:${method.method_hex};`"><span>{{ method.method_hex }}</span></span</td>
            <td>{{ method.priority }}</td>
            <td>
                <div v-if="!inactive">
                    <a href="javascript:void(0)" @click.prevent="formInputs(true, {...method, keyfeatures: setDefault(method.keyfeatures, [{ image: '', text: '' }], true), index}, `_id=${method.hid}`)" class="button mb-1">Edit</a>
                    <button @click="updateStatus(method.hid, index, 0)" class="button mb-1">{{ langs.delete }}</button>
                </div>
                <div v-else>
                    <button @click="updateStatus(method.hid, index, 1)" class="button mb-1">{{ langs.restore }}</button>
                    <button @click="forceDelete(method.hid, index)" class="button mb-1">{{ langs.forceDelete }}</button>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<p v-if="loading" style="text-align:center;">Loading...</p>
<p v-if="!loading && !methods.length" style="text-align:center;">
    There is no printing methods.
</p>

<div v-if="pagination.metas && pagination.metas.total > 0">
<vue-paginate 
v-model="pagination.page"
:page-count="getPaginationCount"
:page-range="10"
:click-handler="paginatePrintMethods"
:container-class="'pagination'"
:page-class="'page-item'"
:first-last-button="false"
/>    
</div>