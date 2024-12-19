<table class="wp-list-table widefat fixed striped pages mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th></th>
            <th>Name</th>
            <!-- <th></th> -->
            <th :class="`sorted ${sort}`">
                <a href="javascript:void(0)" @click.prevent="sorting">
                    <span>Priority</span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th></th>
        </tr>
    </thead>
    <tbody v-if="!subcatMethods.loading && subcatMethods.data.length">
        <tr v-for="(submethods, index) in subcatMethods.data" :key="`product-lines-index-${index}`">
            <td>{{submethods.id}}</td>
            <td>
                <img v-if="submethods.image" :src="submethods.image" alt="" style="width: 50px;">
                <img v-else src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" style="width: 50px;">
            </td>
            <td><strong>{{submethods.printmethod.method_name }} <span :style="`color:${submethods.printmethod.method_hex};`">{{submethods.printmethod.method_name2}}</span></strong></td>
            <!-- <th><a :href="`/product/${tree.data.cat_slug}/${subcategory.sub_slug}/${submethods.printmethod.method_slug}`" target="_blank"><?php // echo home_url(); ?>/product/{{tree.data.cat_slug}}/{{subcategory.sub_slug}}/{{submethods.printmethod.method_slug}}</a></th> -->
            <td><p>{{ submethods.priority }}</p></td>
            <th>
                <div v-if="!inactive">
                    <a href="javascript:void(0)" @click.prevent="formInputs(
                        true, {
                            index,
                            ...submethods, 
                            colors: inputJson(submethods.colors, 'colors'), 
                            features: inputJson(submethods.features, 'features'), 
                            features_pivot: inputJson(submethods.features_pivot, 'features_pivot'), 
                            compliances: inputJson(submethods.compliances, 'compliances'), 
                            id: submethods.hid, 
                            print_method_id: submethods.printmethod.hid,
                            coupon_code_id: submethods.couponcode ? submethods.couponcode.hid : null,
                            pnotes: inputJson(submethods.pnotes, 'pnotes'),
                            seo_content: inputJson(submethods.seo_content, 'seo_content')
                        }, 
                        `_id=${submethods.hid}`); " class="button">Edit</a>
                    <a href="javascript:void(0)" @click.stop="setCharge(submethods)" class="button mb-1">Set Breakdown</a>
                    <a href="javascript:void(0)" @click.stop="colors.plineindex=index" class="button mb-1">Set Colors</a>
                    <a href="javascript:void(0)" @click.stop="imprintInputs({ productline_id: submethods.hid, pmethod: {...submethods.printmethod} })" class="button mb-1">Imprint Types</a>
                    <a href="javascript:void(0)" @click.stop="premiumBgInputs.plineindex=index" class="button mb-1">Premium Backgrounds</a>
                    <a href="javascript:void(0)" @click.stop="stockShapesInputs.plineindex=index" class="button mb-1">Stock Shapes</a>
                    <a href="javascript:void(0)" @click="updateStatus(submethods.hid, index, 0)" class="button mb-1">{{ langs.delete }}</a>
                </div>
                <div v-else>
                    <a href="javascript:void(0)" @click="updateStatus(submethods.hid, index, 1)" class="button mb-1">{{ langs.restore }}</a>
                    <a href="javascript:void(0)" @click="forceDeleteProductLine(submethods.hid, index)" class="button mb-1">{{ langs.forceDelete }}</a>
                </div>
            </th>
        </tr>
    </tbody>
</table>
<p v-if="!subcatMethods.loading && !subcatMethods.data.length" style="text-align:center;">
    There is no product line.
</p>
<p v-if="subcatMethods.loading" style="text-align:center;">Loading...</p>