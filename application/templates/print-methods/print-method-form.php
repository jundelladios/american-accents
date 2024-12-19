<div v-if="form" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content">
            <a href="javascript:void(0)" @click.prevent="formInputs(false, defaultValue)" class="aa-close">&times;</a>
            <h1 class="wp-heading-inline">
                <span v-if="input.index!=null">Edit Method</span>
                <span v-else>New Method</span>
            </h1>
            
            <form action="/" @submit.prevent="saveMethod" class="mt-3" autocomplete="off">
                <div class="mb-3">
                    <label class="d-block mb-2" for="method_name">Method Type</label>
                    <input 
                    type="text" 
                    v-model="input.method_name" 
                    id="method_name" 
                    class="full-width" 
                    v-validate="'required'"
                    name="method_name"
                    data-vv-as="method type"
                    placeholder="American/Accent"
                    />
                    <span class="v-error">{{errors.first('method_name')}}</span>
                </div>
                <div class="mb-3">
                    <label class="d-block mb-2" for="method_name2">Method Name</label>
                    <input 
                    type="text" 
                    v-model="input.method_name2" 
                    id="method_name2" 
                    class="full-width" 
                    v-validate="'required'"
                    name="method_name2"
                    data-vv-as="method name"
                    />
                    <span class="v-error">{{errors.first('method_name2')}}</span>
                </div>
                <div class="mb-3">
                    <label>
                        <input type="checkbox" :true-value="1" :false-value="0" v-model="input.is_unprinted"> consider as unprinted?
                    </label>
                </div>
                <div class="mb-3">
                    <label class="d-block mb-2" for="method_slug">Method Slug (unique)</label>
                    <input type="text" 
                    v-model="input.method_slug" 
                    id="method_slug" 
                    class="d-block"
                    v-validate="'required'"
                    name="method_slug"
                    data-vv-as="print method slug" />
                    <small class="d-block">Notes: Special characters and spacing will be automatically converted to "-"</small>
                    <span class="v-error">{{errors.first('method_slug')}}</span>
                </div>
                <div class="mb-3">
                    <label class="d-block mb-2" for="method_prefix">Prefix</label>
                    <input type="text" 
                    v-model="input.method_prefix" 
                    id="method_prefix"
                    v-validate="'required'"
                    name="method_prefix"
                    data-vv-as="prefix" />
                    <span class="v-error">{{errors.first('method_prefix')}}</span>
                </div>
                <div class="mb-3">
                    <label class="d-block mb-2" for="method_hex">Hex Color</label>
                    <colorpicker 
                    v-model="input.method_hex" 
                    v-validate="'required'"
                    name="method_hex"
                    data-vv-as="hex"></colorpicker>
                    <span class="v-error">{{errors.first('method_hex')}}</span>
                </div>
                <div class="mb-3">
                    <label class="d-block mb-2" for="priority">Priority #</label>
                    <input type="number" v-model="input.priority" id="priority" class="d-block"
                    v-validate="'required'"
                    name="priority"
                    data-vv-as="priority"
                    />
                    <span class="v-error">{{errors.first('priority')}}</span>
                </div>
                <!-- <div class="mb-3">
                    <label class="d-block mb-2" for="short_desc">Short Description</label>
                    <editor v-model="input.method_desc_short" />
                </div> -->
                <div class="mb-3">
                    <label class="d-block mb-2" for="long_desc">Print Method Overview</label>
                    <editor v-model="input.method_desc" />
                </div>

                <div class="mb-3">
                    <div v-for="(feature, index) in input.keyfeatures" :key="`feature-lists-${index}`" class="d-flex mb-3">
                        <div class="mr-3">
                            <input type="text" v-model="feature.image" placeholder="Enter icon name." />
                            <p>Icon preview: <span :class="`icon ${feature.image} icon-dark`"></span></p>
                            <small class="d-block">Icon names found in settings > theme icons.</small>
                        </div>
                        <div style="width: 100%;" class="mr-3">
                            <input type="text" v-model="feature.text" class="full-width">
                        </div>
                        <div>
                            <a v-if="index!=0" title="remove" href="javascript:void(0)" @click.stop="input.keyfeatures.splice(index, 1);">&times;</a>
                        </div>
                    </div>
                    <a href="javascript:void(0)" @click.stop="input.keyfeatures.push({ image: '', text: '' })" class="button mb-3">Add Key Feature</a>
                </div>

                <div class="floating-button-save">
                    <button typpe="submit" id="btn" v-if="input.index!=null" class="button button-primary">Save Changes</button>
                    <button typpe="submit" id="btn" v-else class="button button-primary">Save Category</button>

                    <a href="javascript:void(0)" @click.prevent="formInputs(false, defaultValue)" class="button button-default">Cancel</a>
                </div>
            </form>

        </div>
    </div>
</div>