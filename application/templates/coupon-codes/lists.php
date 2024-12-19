<table class="wp-list-table widefat fixed striped pages">
    <thead>
        <tr>
            <th>ID</th>
            <th>REF ID</th>
            <th><strong>Coupon Code</strong></th>
            <th></th>
        </tr>
    </thead>
    <tbody v-if="!loading && coupons">
        <tr v-for="(coupon, index) in coupons" :key="`category-index-${index}`">
            <td>{{ coupon.id }}</td>
            <td>{{ coupon.hid }}</td>
            <td>{{ coupon.code }}</td>
            <td>
                <div v-if="!inactive">
                    <a href="javascript:void(0)" @click.prevent="formInputs(true, {...coupon, index}, `_id=${coupon.hid}`)" class="button mb-1">Edit</a>
                    <button @click="updateStatus(coupon.hid, index, 0)" class="button mb-1">{{ langs.delete }}</button>
                </div>
                <div v-else>
                    <button @click="updateStatus(coupon.hid, index, 1)" class="button mb-1">{{ langs.restore }}</button>
                    <button @click="forceDelete(coupon.hid, index)" class="button mb-1">{{ langs.forceDelete }}</button>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<p v-if="loading" style="text-align:center;">Loading...</p>
<p v-if="!loading && !coupons.length" style="text-align:center;">
    There is no coupon code.
</p>

<div v-if="pagination.metas && pagination.metas.total > 0">
<vue-paginate 
v-model="pagination.page"
:page-count="getPaginationCount"
:page-range="10"
:click-handler="paginateCoupons"
:container-class="'pagination'"
:page-class="'page-item'"
:first-last-button="false"
/>    
</div>