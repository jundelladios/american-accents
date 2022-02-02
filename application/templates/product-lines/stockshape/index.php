<div v-if="stockShapesInputs.plineindex!=null" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content md">
            <a href="javascript:void(0)" @click.stop="resetInputStockShape(); stockShapesInputs.plineindex=null;" class="aa-close">&times;</a>
            <div class="row">
                <div class="col-md-12">
                    <h1 class="wp-heading-inline mb-3">
                        Stock Shapes for<br>
                        {{ subcategory.sub_name }} - <span :style="`color: ${stockshapepline.printmethod.method_hex}`">{{stockshapepline.printmethod.method_name2}}</span>
                    </h1>
                </div>
                <div class="col-md-4">
                    <form @submit.prevent="plineStockShapeSave">
                        <div class="mb-3">
                            <label>Overide Stock Shapes Title</label>
                            <input type="text" v-model="stockShapesInputs.input.title">
                        </div>
                        <div class="mb-3">
                            <select v-model="stockShapesInputs.input.collection_stockshape_id" placeholder="Select Collection"
                            v-validate="'required'"
                            data-vv-as="stock shape collection"
                            name="collection_stockshape_id"
                            >
                                <option :value="ccolor.hid" v-for="(ccolor, index) in stockShapesInputs.data" :key="`color-collection-${index}`">{{ ccolor.title }}</option>
                            </select>
                            <small class="d-block">Go to American Accents > Stock Shapes Collections for the collection.</small>
                            <span class="v-error">{{errors.first('collection_stockshape_id')}}</span>
                        </div>
                        <div class="mb-3">
                            <input type="number" v-model="stockShapesInputs.input.priority" placeholder="Priority">
                        </div>

                        <div class="mb-3">
                            <button type="submit" v-if="!stockShapesInputs.input.id" class="button button-primary">Save Collection</button>
                            <button type="submit" v-else class="button button-primary">Update Collection</button>
                            <button type="button" @click.stop="resetInputStockShape()" class="button" >Cancel</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-8">

                    <table class="wp-list-table widefat fixed striped pages mt-3">
                        <thead>
                            <tr>
                                <th>Collection</th>
                                <th>Overide Title</th>
                                <th>Priority</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(cdata, index) in subcatMethods.data[stockShapesInputs.plineindex].stockshapes">
                                <td>{{ cdata.stockshapes.title }}</td>
                                <td>
                                    <span>{{ cdata.title }}</span>
                                </td>
                                <td>{{ cdata.priority }}</td>
                                <td>
                                    <a href="javascript: void(0)" @click.stop="setEditplineStockShape(cdata, index)" class="button">Edit</a>
                                    <a href="javascript: void(0)" @click.stop="plineStockShapeDelete(index, cdata.hid)" class="button">Remove</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="text-align: center;" v-if="!subcatMethods.data[stockShapesInputs.plineindex].stockshapes.length">There is no stock shape collection on this product line.</p>

                </div>
            </div>
        </div>
    </div>
</div>