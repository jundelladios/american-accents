<div v-if="imprint.productline_id" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content md">
            <a href="javascript:void(0)" @click.stop="imprintInputs" class="aa-close">&times;</a>
            
            <h1 class="wp-heading-inline mb-3">
                Imprint Types for <br>{{ subcategory.sub_name }} - <strong :style="`color: ${imprint.pmethod.method_hex};`">{{ imprint.pmethod.method_name2 }}</strong>
                <span v-if="imprint.index!=null">(Editing...)</span>
            </h1>

            <div class="row">

                <div class="col-xl-4">

                    <div v-if="!imprintTypesData.loading">
                        <div v-if="!imprintTypesData.data.length" class="notice notice-warning">
                            <p>Please create imprint type first. Go to American Accents > Imprint Types</p>
                        </div>

                        <form v-else @submit.prevent="saveImprintProductLine">

                            <div class="mb-3">
                                <a href="javascript:void(0)" @click.stop="chooseImprintImage" class="d-block link-img img-form-wrap nr">
                                    <img v-if="imprint.image" :src="imprint.image" alt="" class="full-width nr">
                                    <img v-else src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder-square.png'; ?>" alt="" class="full-width nr">
                                </a>
                                <a v-if="imprint.image" href="javascript:void(0)" @click.stop="imprint.image=null;" class="mt-2 mb-2"><small>remove</small></a>
                                <input type="hidden" v-model="imprint.image" v-validate="'required'" data-vv-as="product image" name="image">
                                <span class="v-error">{{errors.first('image')}}</span>
                            </div>

                            <div v-if="imprint.index==null" class="mb-3">
                                <label for="imprint_id" class="d-block mb-2">Select Imprint Type</label>
                                <select v-model="imprint.imprint_type_id" class="d-block" v-validate="'required'" data-vv-as="imprint type" name="imprint_type_id">
                                    <option v-for="(imp, indeximp) in imprintTypesData.data" :key="`imp-select-${indeximp}`" :value="imp.hid">{{imp.title}}</option>
                                </select>
                                <span class="v-error">{{errors.first('imprint_type_id')}}</span>
                            </div>

                            <div class="mb-3">
                                <label for="imprint_id" class="d-block mb-2">Minimum production days</label>
                                <input type="number" v-model="imprint.min_prod_days" />
                            </div>

                            <div class="mb-3">
                                <label for="imprint_id" class="d-block mb-2">Imprint Die Charge</label>
                                <input type="number" v-model="imprint.imprint_charge" />
                            </div>

                            <div class="mb-3">
                                <label for="imprint_id" class="d-block mb-2">Priority #</label>
                                <input type="number" v-model="imprint.priority" />
                            </div>

                            <div class="mb-3">
                                <label for="imprint_id" class="d-block mb-2">Decimal Point</label>
                                <input type="number" v-model="imprint.decimal_value" />
                            </div>

                            <div class="mb-2">
                                <label>
                                    <input type="checkbox" v-model="imprint.show_currency" :true-value="1" :false-value="0">
                                    Show currency?
                                </label>
                            </div>

                            <div class="mb-3">
                                <button id="btn" type="submit" v-if="imprint.index!=null" class="button button-primary">Save Changes</button>
                                <button id="btn" type="submit" v-else class="button button-primary">Add Imprint</button>
                                <button id="btn" @click.stop="cancelImprintInputs" type="button" class="button">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <div v-else>
                        <p style="text-align: center;">Loading...</p>
                    </div>

                </div>

                <div class="col-xl-8">

                    <div v-if="!imprintProductLineData.loading">
                        <template v-if="imprintorderbypriority.length > 0">
                            <div class="row">

                                <div v-for="(imprintpline, index) in imprintorderbypriority" :key="`imprint-pline-${index}`" class="col-lg-4 mb-4">
                                    <div class="d-block mb-2 bg-cover" :style="`width: 100px; height: 100px;background: url(${imprintpline.image}) center no-repeat;`">
                                    </div>
                                    <div class="d-block">
                                        <strong class="d-block">{{ imprintpline.imprinttype.title }}</strong>
                                        <span class="d-block">Min production days: {{ imprintpline.min_prod_days }}</span>
                                        <span class="d-block">Imprint Charge: {{ moneyFormat(imprintpline.imprint_charge) }}</span>
                                        <span class="d-block">priority #: {{ imprintpline.priority }}</span>
                                    </div>
                                    <div class="d-block mt-2">
                                        <a href="javascript: void(0);" @click.stop="imprintInputs({ 
                                            ...imprintpline, 
                                            index: index, 
                                            id: imprintpline.hid,
                                            productline_id: imprint.productline_id,
                                            pmethod: imprint.pmethod,
                                            imprint_charge: moneyFormat(imprintpline.imprint_charge)
                                            })" class="button mr-1 mb-2">edit</a>
                                        <a href="javascript: void(0);" @click.stop="removeImprintPline(imprintpline.hid, index)" class="button mb-2">remove</a>
                                    </div>
                                </div>

                            </div>
                        </template>
                        <template v-else>
                            <p style="text-align: center;">There is no imprint type on this product line.</p>
                        </template>
                    </div>

                    <div v-else>
                        <p style="text-align: center;">Loading...</p>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>