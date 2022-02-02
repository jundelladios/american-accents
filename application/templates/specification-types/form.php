<div v-if="form" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content lg">
            <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue});" class="aa-close">&times;</a>
            <h1 class="wp-heading-inline">
                <span v-if="input.index!=null">Edit Specification Type</span>
                <span v-else>New Specification Type</span>
            </h1>

            <form action="/" @submit.prevent="saveSpecs" class="mt-3" autocomplete="off">

                <div class="mb-3">
                    <label class="d-block mb-2" for="title">Enter Title</label>
                    <input type="text" v-model="input.title" v-validate="'required'" id="title" name="title" class="full-width" />
                    <span class="v-error">{{errors.first('title')}}</span>
                </div>

                <div class="mb-3">
                    <label class="d-block mb-2" for="priority">Priority #</label>
                    <input type="text" v-model="input.priority" v-validate="'required'" id="priority" name="priority" />
                    <span class="v-error">{{errors.first('priority')}}</span>
                </div>

                <div class="mb-3">
                    <label class="d-block mb-2" for="isspec"><input type="checkbox" v-model="input.isspec" id="priority" name="isspec" :true-value="1" :false-value="0" /> Specification as Output</label>
                </div>


                <div v-if="!input.isspec">
                
                    <h2 style="color: #2271b1;">Product / Combo Specification</h2>

                    <h3 class="mb-2">Form Builder</h3>

                    <p><strong>Suggestion:</strong> Include prefix on each keys ex: spec_mycustomspecification, where "spec_" is the prefix key.</p>
                    
                    <div class="aa-accordion">
                        <a href="javascript:void(0)" @click.stop="accordion"  data-accordion-module data-target="#productkeys" class="accordion_buttons ">Product Accessible Keys</a>
                        <div id="productkeys" class="active accordion_contents">
                            <div class="mb-2">
                                <code>product_size</code> - key for product size value [field type: number].
                            </div>
                            <div class="mb-2">
                                <code>product_size_details</code> - full details of product size to be displayed in filter, ex: 1oz or beverage [field type: text].
                            </div>
                            <div class="mb-2">
                                <code>product_thickness</code> - key for product thickness value [field type: number].
                            </div>
                            <div class="mb-2">
                                <code>product_tickness_details</code> - full details of product thickness to be displayed in filter, ex: 1pt [field type: text].
                            </div>
                        </div>

                        <a href="javascript:void(0)" @click.stop="accordion"  data-accordion-module data-target="#productcombokeys" class="accordion_buttons ">Product Combo Accessible Keys</a>
                        <div id="productcombokeys" class="accordion_contents">
                            <p>There is no accessible keys in product combo.</p>
                        </div>
                    </div>

                    <p class="mb-3"><small>Note: Empty group name and keys will be ignored. <a href="javascript:void(0)" @click.stop="input.customfield=[];" class="button">Empty Group</a></small></p>
                
                    <draggable 
                    v-model="input.customfield" 
                    class="v-draggable spec"
                    tag="div" 
                    v-bind="vueDragOptions"
                    @start="specdrag = true"
                    @end="specdrag = false"
                    key="spec-drag"
                    >
                        <transition-group type="transition" tag="div" :name="!specdrag ? 'flip-list' : null">

                            <div 
                            v-for="(cf, cfi) in input.customfield"
                            :key="`spec-customfield-${cf.uniqueKey}`"
                            class="mb-5 group p-3 v-drag-item">

                                <div class="mt-3 mb-3 specfield p-3">
                                    <label class="d-block full-width mb-2">Enter Group Field</label>
                                    <input type="text" style="border: 0;" v-model="cf.group" />
                                    <a href="javascript:void(0)" @click.stop="input.customfield.splice(cfi, 1)" class="ml-2">remove</a>
                                    <a href="javascript:void(0)" @click.stop="cf.fields.push({ label: '', default: '', key: '', value: '', uniqueKey: Date.now(), type: 'text', options: '' })" class="ml-2">add field</a>
                                    <div class="mt-2">
                                        <button type="button" @click.stop="moveGroupSpec(cfi,cfi-1)" :disabled="cfi==0" class="btn-move up"><span class="icon icon-arrow-right"></span></button>
                                        <button type="button" @click.stop="moveGroupSpec(cfi,cfi+1)" :disabled="cfi==(input.customfield.length-1)" class="btn-move down"><span class="icon icon-arrow-right"></span></button>
                                    </div>
                                </div>

                                <draggable 
                                v-model="cf.fields" 
                                class="v-draggable"
                                tag="div" 
                                v-bind="vueDragOptions"
                                @start="fielddrag = true"
                                @end="fielddrag = false"
                                :key="`field-drag-${cfi}`"
                                >
                                    <transition-group type="transition" tag="div" class="row" :name="!fielddrag ? 'flip-list' : null" style="min-height: 100px;">
                                        <div 
                                        v-for="(field, fi) in cf.fields"
                                        :key="`field-${field.uniqueKey}`"
                                        class="col-lg-3 mb-4 v-drag-item">
                                            <div class="p-3 field">
                                                <label class="d-block mb-2">Enter label:</label>
                                                <input type="text" v-model="field.label" class="full-width d-block mb-2" />
                                                <label class="d-block mb-2">Enter default value:</label>
                                                <input type="text" v-model="field.default" class="full-width d-block mb-2" />
                                                <label class="d-block mb-2">Enter key:</label>
                                                <input  type="text" v-model="field.key" class="full-width d-block mb-2" />
                                                <label class="d-block mb-2">Select Field Type:</label>
                                                <select class="d-block mb-2 full-width" v-model="field.type">
                                                    <option value="text">Text</option>
                                                    <option value="email">Email</option>
                                                    <option value="number">Number</option>
                                                    <option value="textarea">Textarea</option>
                                                    <option value="select">Select</option>
                                                    <option value="radio">Radio</option>
                                                </select>
                                                <div v-if="field.type=='select' || field.type == 'radio'">
                                                <textarea class="full-width d-block mb-2" v-model="field.options"></textarea>
                                                <small class="full-width d-block mb-2">Option separated by comma ','</small>
                                                </div>
                                                <code v-if="field.key" class="mb-2">{{field.key}}</code>
                                                <a href="javascript:void(0)" @click.stop="input.customfield[cfi].fields.splice(fi, 1)" class="ml-2">remove</a>
                                                <hr>
                                                <div class="mt-2">
                                                    <button type="button" @click.stop="moveFieldSpecProduct(cfi,fi,fi-1)" :disabled="fi==0" class="btn-move left"><span class="icon icon-arrow-right"></span></button>
                                                    <button type="button" @click.stop="moveFieldSpecProduct(cfi,fi+1)" :disabled="fi==(input.customfield[cfi].fields.length-1)" class="btn-move"><span class="icon icon-arrow-right"></span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </transition-group>
                                </draggable>


                            </div>

                        </transition-group>
                    </draggable>

                    <button type="button" class="button button-secondary" @click="newCustomField">Add New Custom Field</button>
                
                </div>

                <div v-else>
                
                    <h3 class="mt-5">Specification Builder</h3>
                    <p class="mb-2">Use bracket for key to capture its value. Ex: Package {package_min} of {package_max}</p>
                    <p class="mb-2"><small>Note: Empty label and filter output will be ignored.</small></p>

                    <div class="mb-5">
                        <label>Retrieve keys from selected specification for reference: </label>
                        <select v-model="input.keyret">
                            <option :value="spec.hid" v-for="(spec, index) in getNonOutputSpec" :key="`spec-dd-index-${index}`">{{spec.title}}</option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <code v-for="(kpec, kpeci) in getSelectedKeysSpec" class="mr-3 d-inline-flex mb-3 pl-2 pr-2" :key="`key-spec-data-${kpeci}`">
                            {{ kpec.key }}
                        </code>
                    </div>

                    <draggable 
                    v-model="input.specs" 
                    class="v-draggable spec"
                    tag="div" 
                    v-bind="vueDragOptions"
                    @start="specdrag = true"
                    @end="specdrag = false"
                    key="specdrag-drag"
                    >
                        <transition-group type="transition" tag="div" :name="!specdrag ? 'flip-list' : null">
                            <div 
                            v-for="(spc, spci) in input.specs"
                            :key="`specs-data-key-${spc.uniqueKey}`"
                            class="mb-3 v-drag-item">
                                <div class="spec-builder p-3" style="background: #f5f5f5;">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label class="d-block mb-2">Enter label:</label>
                                            <input type="text" v-model="input.specs[spci].label" class="mb-2 full-width" placeholder="Enter label" />
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="d-block mb-2">Output:</label>
                                            <label class="d-block mb-2">
                                                <input type="checkbox" v-model="input.specs[spci].isexec" :true-value="true" :false-value="false"> Use PHP code? | Ex: echo {package_min} or echo "{package_min}" for string;
                                            </label>
                                            <div v-if="input.specs[spci].isexec">
                                                <prism-editor class="my-editor nodrag" v-model="input.specs[spci].filter" :highlight="highlighter" line-numbers></prism-editor>
                                            </div>
                                            <div v-else>
                                                <input type="text" v-model="input.specs[spci].filter" class="mb-2 full-width" placeholder="Enter output filter" />
                                                <code v-if="spc.filter" class="d-block mb-2">{{ spc.filter }}</code>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-lg-12">
                                            <button type="button" @click.stop="moveSpecBuilder(spci,spci-1)" :disabled="spci==0" class="btn-move up"><span class="icon icon-arrow-right"></span></button>
                                            <button type="button" @click.stop="moveSpecBuilder(spci,spci+1)" :disabled="spci==(input.specs.length-1)" class="btn-move down"><span class="icon icon-arrow-right"></span></button>
                                            <a href="javascript:void(0)" class="ml-3" @click.stop="input.specs.splice(spci, 1)">remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </transition-group>
                    </draggable>


                    <button type="button" class="button button-secondary" @click="newSpecification">Add New Specification Field</button>

                </div>

                <!-- <hr class="mt-5 mb-5">


                <h2 style="color: #2271b1;">Product Combo Specification</h2>

                <h3 class="mb-2">Form Builder</h3>

                <p class="mb-3"><small>Note: Empty group name and keys will be ignored.</small></p>
                
                <draggable 
                v-model="input.customfieldcombo" 
                class="v-draggable spec"
                tag="div" 
                v-bind="vueDragOptions"
                @start="specdrag = true"
                @end="specdrag = false"
                key="spec-drag"
                >
                    <transition-group type="transition" tag="div" :name="!specdrag ? 'flip-list' : null">

                        <div 
                        v-for="(cf, cfi) in input.customfieldcombo"
                        :key="`spec-customfieldcombo-${cf.uniqueKey}`"
                        class="mb-5 group p-3 v-drag-item">

                            <div class="mt-3 mb-3 specfield p-3">
                                <label class="d-block full-width mb-2">Enter Group Field</label>
                                <input type="text" style="border: 0;" v-model="cf.group" />
                                <a href="javascript:void(0)" @click.stop="input.customfieldcombo.splice(cfi, 1)" class="ml-2">remove</a>
                                <a href="javascript:void(0)" @click.stop="cf.fields.push({ label: '', default: '', key: '', value: '', uniqueKey: Date.now() })" class="ml-2">add field</a>
                                <div class="mt-2">
                                    <button type="button" @click.stop="moveGroupSpecCombo(cfi,cfi-1)" :disabled="cfi==0" class="btn-move up"><span class="icon icon-arrow-right"></span></button>
                                    <button type="button" @click.stop="moveGroupSpecCombo(cfi,cfi+1)" :disabled="cfi==(input.customfield.length-1)" class="btn-move down"><span class="icon icon-arrow-right"></span></button>
                                </div>
                            </div>

                            <draggable 
                            v-model="cf.fields" 
                            class="v-draggable"
                            tag="div" 
                            v-bind="vueDragOptions"
                            @start="fielddrag = true"
                            @end="fielddrag = false"
                            :key="`field-drag-${cfi}`"
                            >
                                <transition-group type="transition" tag="div" class="row" :name="!fielddrag ? 'flip-list' : null" style="min-height: 100px;">
                                    <div 
                                    v-for="(field, fi) in cf.fields"
                                    :key="`field-${field.uniqueKey}`"
                                    class="col-lg-3 mb-4 v-drag-item">
                                        <div class="p-3 field">
                                            <label class="d-block mb-2">Enter label:</label>
                                            <input type="text" v-model="field.label" class="full-width d-block mb-2" />
                                            <label class="d-block mb-2">Enter default value:</label>
                                            <input type="text" v-model="field.default" class="full-width d-block mb-2" />
                                            <label class="d-block mb-2">Enter key:</label>
                                            <input  type="text" v-model="field.key" class="full-width d-block mb-2" />
                                            <code v-if="field.key" class="mb-2">{{field.key}}</code>
                                            <a href="javascript:void(0)" @click.stop="input.customfieldcombo[cfi].fields.splice(fi, 1)" class="ml-2">remove</a>
                                            <hr>
                                            <div class="mt-2">
                                                <button type="button" @click.stop="moveFieldSpecCombo(cfi,fi,fi-1)" :disabled="fi==0" class="btn-move left"><span class="icon icon-arrow-right"></span></button>
                                                <button type="button" @click.stop="moveFieldSpecCombo(cfi,fi+1)" :disabled="fi==(input.customfieldcombo[cfi].fields.length-1)" class="btn-move"><span class="icon icon-arrow-right"></span></button>
                                            </div>
                                        </div>
                                    </div>
                                </transition-group>
                            </draggable>


                        </div>

                    </transition-group>
                </draggable>

                <button type="button" class="button button-secondary" @click="newCustomFieldCombo">Add New Custom Field</button>

                <hr class="mt-5 mb-5">


                <h3 class="mt-5">Specification Builder</h3>
                <p class="mb-2">Use bracket for key to capture its value. Ex: Package {package_min} of {package_max}</p>
                <p class="mb-5"><small>Note: Empty label and filter output will be ignored.</small></p>

                <draggable 
                v-model="input.specs" 
                class="v-draggable spec"
                tag="div" 
                v-bind="vueDragOptions"
                @start="specdrag = true"
                @end="specdrag = false"
                key="specdrag-drag"
                >
                    <transition-group type="transition" tag="div" :name="!specdrag ? 'flip-list' : null">
                        <div 
                        v-for="(spc, spci) in input.specs"
                        :key="`specs-data-key-${spc.uniqueKey}`"
                        class="mb-3 v-drag-item">
                            <div class="spec-builder p-3" style="background: #f5f5f5;">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label class="d-block mb-2">Enter label:</label>
                                        <input type="text" v-model="input.specs[spci].label" class="mb-2 full-width" placeholder="Enter label" />
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="d-block mb-2">Output:</label>
                                        <input type="text" v-model="input.specs[spci].filter" class="mb-2 full-width" placeholder="Enter output filter" />
                                        <code v-if="spc.output" class="d-block mb-2">{{ spc.output }}</code>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12">
                                        <button type="button" @click.stop="moveSpecBuilder(spci,spci-1)" :disabled="spci==0" class="btn-move up"><span class="icon icon-arrow-right"></span></button>
                                        <button type="button" @click.stop="moveSpecBuilder(spci,spci+1)" :disabled="spci==(input.specs.length-1)" class="btn-move down"><span class="icon icon-arrow-right"></span></button>
                                        <a href="javascript:void(0)" class="ml-3" @click.stop="input.specs.splice(spci, 1)">remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition-group>
                </draggable>


                <button type="button" class="button button-secondary" @click="newSpecification">Add New Specification Field</button> -->

                <div class="mt-5 mb-3">
                    <button type="submit" id="btn" v-if="input.index!=null" class="button button-primary">Save Changes</button>
                    <button type="submit" id="btn" v-else class="button button-primary">Save Specification</button>
                </div>

            </form>
        </div>
    </div>
</div>
        