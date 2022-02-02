<div v-if="colors.plineindex!=null" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content md">
            <a href="javascript:void(0)" @click.stop="colors.plineindex=null;colors.color_collection_id=null;colors.index=null;" class="aa-close">&times;</a>
            <div class="row">
                <div class="col-md-12">
                    <h1 class="wp-heading-inline mb-3">
                        Colors for<br>
                        {{ subcategory.sub_name }} - <span :style="`color: ${colorspline.method_hex}`">{{colorspline.method_name2}}</span>
                    </h1>
                </div>
                <div class="col-md-4">
                    <form @submit.prevent="plineColorsSave">
                        <div class="mb-3">
                            <label>Overide Color Collection Title</label>
                            <input type="text" v-model="colors.input.title">
                        </div>
                        <div class="mb-3">
                            <select v-model="colors.input.color_collection_id" placeholder="Select Collection"
                            v-validate="'required'"
                            data-vv-as="color collection"
                            name="color_collection_id"
                            >
                                <option :value="ccolor.hid" v-for="(ccolor, index) in colors.data" :key="`color-collection-${index}`">{{ ccolor.title }}</option>
                            </select>
                            <small class="d-block">Go to American Accents > Color Collections for the colors.</small>
                            <span class="v-error">{{errors.first('color_collection_id')}}</span>
                        </div>
                        <div class="mb-3">
                            <input type="number" v-model="colors.input.priority" placeholder="Priority">
                        </div>

                        <div class="mb-3">
                            <button type="submit" v-if="!colors.input.id" class="button button-primary">Save Color Collection</button>
                            <button type="submit" v-else class="button button-primary">Update Color Collection</button>
                            <button type="button" @click.stop="resetInputplinecolor" class="button" >Cancel</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-8">

                    <table class="wp-list-table widefat fixed striped pages mt-3">
                        <thead>
                            <tr>
                                <th>Color Collection</th>
                                <th>Overide Title</th>
                                <th>Priority</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(cdata, index) in subcatMethods.data[colors.plineindex].plinecolors">
                                <td>{{ cdata.colorcollections.title }}</td>
                                <td>
                                    <span>{{ cdata.title }}</span>
                                </td>
                                <td>{{ cdata.priority }}</td>
                                <td>
                                    <a href="javascript: void(0)" @click.stop="setEditplineColor(cdata, index)" class="button">Edit</a>
                                    <a href="javascript: void(0)" @click.stop="plineColorsDelete(index, cdata.hid)" class="button">Remove</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="text-align: center;" v-if="!subcatMethods.data[colors.plineindex].plinecolors.length">There is no color collection on this product line.</p>

                </div>
            </div>
        </div>
    </div>
</div>