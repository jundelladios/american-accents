<div v-if="premiumBgInputs.plineindex!=null" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content md">
            <a href="javascript:void(0)" @click.stop="resetInputpremiumBG(); premiumBgInputs.plineindex=null;" class="aa-close">&times;</a>
            <div class="row">
                <div class="col-md-12">
                    <h1 class="wp-heading-inline mb-3">
                        Premium Backgrounds for<br>
                        {{ subcategory.sub_name }} - <span :style="`color: ${premiumbgpline.printmethod.method_hex}`">{{premiumbgpline.printmethod.method_name2}}</span>
                    </h1>
                </div>
                <div class="col-md-4">
                    <form @submit.prevent="plinePremiumBGSave">
                        <div class="mb-3">
                            <label>Overide Premium Background Title</label>
                            <input type="text" v-model="premiumBgInputs.input.title">
                        </div>
                        <div class="mb-3">
                            <select v-model="premiumBgInputs.input.collection_premium_backgrounds_id" placeholder="Select Collection"
                            v-validate="'required'"
                            data-vv-as="premium background collection"
                            name="collection_premium_backgrounds_id"
                            >
                                <option :value="ccolor.hid" v-for="(ccolor, index) in premiumBgInputs.data" :key="`color-collection-${index}`">{{ ccolor.title }}</option>
                            </select>
                            <small class="d-block">Go to American Accents > Premium Background Collections for the collection.</small>
                            <span class="v-error">{{errors.first('collection_premium_backgrounds_id')}}</span>
                        </div>
                        <div class="mb-3">
                            <input type="number" v-model="premiumBgInputs.input.priority" placeholder="Priority">
                        </div>

                        <div class="mb-3">
                            <button type="submit" v-if="!premiumBgInputs.input.id" class="button button-primary">Save Collection</button>
                            <button type="submit" v-else class="button button-primary">Update Collection</button>
                            <button type="button" @click.stop="resetInputpremiumBG()" class="button" >Cancel</button>
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
                            <tr v-for="(cdata, index) in subcatMethods.data[premiumBgInputs.plineindex].premiumbg">
                                <td>{{ cdata.premiumbg.title }}</td>
                                <td>
                                    <span>{{ cdata.title }}</span>
                                </td>
                                <td>{{ cdata.priority }}</td>
                                <td>
                                    <a href="javascript: void(0)" @click.stop="setEditplinePremiumBG(cdata, index)" class="button">Edit</a>
                                    <a href="javascript: void(0)" @click.stop="plinePremiumBGDelete(index, cdata.hid)" class="button">Remove</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="text-align: center;" v-if="!subcatMethods.data[premiumBgInputs.plineindex].premiumbg.length">There is no premium background collection on this product line.</p>

                </div>
            </div>
        </div>
    </div>
</div>