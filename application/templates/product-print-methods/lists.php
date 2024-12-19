<table class="wp-list-table widefat fixed striped pages mt-3">
    <thead>
        <tr>
            <th></th>
            <th>Name with Prefix</th>
            <th></th>
            <th>Print Method</th>
            <th>Price Range</th>
            <th :class="`sorted ${sort}`">
                <a href="javascript:void(0)" @click.prevent="sorting">
                    <span>Priority</span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th></th>
        </tr>
    </thead>
    <tbody v-if="!combos.loading && combos.data">
        <tr v-for="(combo, index) in combos.data" :key="`combo-data-${index}`">
            <td><img :src="resToJson(combo.feature_img).image" alt="" style="max-width: 50px;"></td>
            <td>
                <strong>
                    <span v-if="combo.allow_print_method_prefix">{{combo.productline.printmethod.method_prefix}}</span><span>{{combo.product.product_name}}</span>
                </strong>
            </td>
            <td><a :href="`/product/${product.data.category.cat_slug}/${product.data.subcategory.sub_slug}/${combo.product.product_slug}/${combo.productline.printmethod.method_slug}`" target="_blank"><?php echo home_url(); ?>/product/{{product.data.category.cat_slug}}/{{product.data.subcategory.sub_slug}}/{{combo.product.product_slug}}/{{combo.productline.printmethod.method_slug}}</a></td>
            <td><strong>{{combo.productline.printmethod.method_name}} <span :style="`color:${combo.productline.printmethod.method_hex};`">{{combo.productline.printmethod.method_name2}}</span></strong></td>
            <td>
                <div v-if="combo.price.min && combo.price.max">
                    <span v-if="combo.price.min === combo.price.max">${{combo.price.min}}</span>
                    <span v-else>${{combo.price.min}} - ${{combo.price.max}}</span>
                </div>
                <div v-else>N/A</div>
            </td>
            <td>{{combo.priority}}</td>
            <td>
                <div v-if="!inactive">
                    <a href="javascript:void(0)" class="button" @click="$resSetter(combo)">Edit</a>

                        <a href="javascript:void(0)" @click.stop="getColorByProductId(combo.hid)" class="button mb-1">Set Colors</a>
                        <a href="javascript:void(0)" @click.stop="getStockShapesByProductId(combo.hid)"  class="button mb-1">Set Stock Shapes</a>
                        <a href="javascript:void(0)" @click.stop="getColorStockShapesByProductId(combo.hid)"  class="button mb-1">Set Colors + Stock Shapes</a>
                        <a :href="`?page=american-accents-products&sageDataBuilder=${combo.hid}`" target="_blank" class="button mb-1">SAGE Data Builder</a>

                    <a href="javascript:void(0)" class="button mb-1" @click="updateStatus(combo.hid, index, 0)">{{ langs.delete }}</a>
                </div>
                <div v-else>
                    <a href="javascript:void(0)" @click="updateStatus(combo.hid, index, 1)" class="button mb-1">{{ langs.restore }}</a>
                    <a href="javascript:void(0)" @click="forceDelete(combo.hid, index)" class="button mb-1">{{ langs.forceDelete }}</a>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<p v-if="combos.loading" style="text-align:center;">Loading...</p>
<p v-if="!combos.loading && !combos.data.length" style="text-align:center;">Product Combo not available.</p>