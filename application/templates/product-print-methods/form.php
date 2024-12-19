<div v-if="form" class="aa-popup">
    <div class="aa-content-wrap">
        <div :class="`aa-content ${inputs.hid ? 'md' : 'sm'}`">
            <a href="javascript:void(0)" @click.stop="formInputs(false, {...defaultValue})" class="aa-close">&times;</a>
            

            <div class="row">

                <div :class="`col-lg-12 col-xl-${inputs.hid ? '6' : '12'} mb-4`">

                    <form @submit.prevent="saveProductCombo" data-vv-scope="product">

                        <h1 class="wp-heading-inline mb-3">
                            <span v-if="inputs.index!=null">Edit Product Combo</span>
                            <span v-if="inputs.index!=null" class="d-block">
                                for
                                <strong :style="`color:${inputs.productline.printmethod.method_hex};`">
                                    <span v-if="inputs.allow_print_method_prefix">{{inputs.productline.printmethod.method_prefix}}</span><span>{{inputs.product.product_name}}</span>
                                </strong>
                            </span>
                            <span v-else>New Product Combo</span>
                        </h1>

                        <p>Please complete the highlighted field.</p>

                        <div class="aa-accordion">

                            <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#information" :class="`accordion_buttons ${!inputs.feature_img.image || (!inputs.hid && !inputs.product_line_id) || !inputs.priority ? 'required' : ''}`">Information</a>
                            <div id="information" class="active accordion_contents">
                                <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/forms/information.php' ); ?>
                            </div>


                            <template
                            v-for="(spec, speci) in inputs.specs_json"
                            >
                                <a href="javascript:void(0)" :key="`data-specbutton-${spec.groupid}`" @click="accordion" data-accordion-module :data-target="`#${spec.groupid}-index-${speci}`" class="accordion_buttons">{{ spec.group }}</a>
                                <div :id="`${spec.groupid}-index-${speci}`" :key="`data-speccontent-${spec.groupid}`" class="accordion_contents">
                                    <div class="row">
                                        <div
                                        v-for="(field, fieldi) in spec.fields"
                                        :index="`spec-field-${fieldi}`"
                                        class="col-lg-6 mb-2"
                                        >
                                            <label class="d-block mb-2">{{ field.label }}</label>
                                            <input v-if="!field.type || field.type == 'text'" type="text" :value="field.value" @input="(e) => setSpecJson(field.key, e.target.value, speci, fieldi)" class="full-width mb-2" />
                                            <input v-if="field.type == 'number'" type="number" min="0" step="any" :value="field.value" @input="(e) => setSpecJson(field.key, e.target.value, speci, fieldi)" class="full-width mb-2" />
                                            <input v-if="field.type == 'email'" type="email" :value="field.value" @input="(e) => setSpecJson(field.key, e.target.value, speci, fieldi)" class="full-width mb-2" />
                                            <textarea v-if="field.type == 'textarea'" :value="field.value" @input="(e) => setSpecJson(field.key, e.target.value, speci, fieldi)" class="full-width mb-2"></textarea>
                                            <select v-if="field.type == 'select'" :value="field.value" @input="(e) => setSpecJson(field.key, e.target.value, speci, fieldi)" class="full-width mb-2">
                                                <option v-for="(opt, opti) in field.options.split(',')" :key="`option-select-index-${opti}`" :value="opt">{{opt}}</option>
                                            </select>
                                            <div v-if="field.type == 'radio'">
                                                <label v-for="(opt, opti) in field.options.split(',')" class="full-width mb-2 d-block" :key="`option-radio-index-${opti}`">
                                                    <input type="radio" :value="field.value" @input="(e) => setSpecJson(field.key, e.target.value, speci, fieldi)" :value="opt"> {{opt}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            
                            <!-- <template v-if="getSelectedSpectype">
                                <template
                                v-for="(spec, speci) in inputs.specs_json"
                                >
                                    <a href="javascript:void(0)" :key="`data-specbutton-${spec.groupid}`" @click="accordion" data-accordion-module :data-target="`#${spec.groupid}-index-${speci}`" class="accordion_buttons">{{ spec.group }}</a>
                                    <div :id="`${spec.groupid}-index-${speci}`" :key="`data-speccontent-${spec.groupid}`" class="accordion_contents">
                                        <div class="row">
                                            <div
                                            v-for="(field, fieldi) in spec.fields"
                                            :index="`spec-field-${fieldi}`"
                                            class="col-lg-6 mb-2"
                                            >
                                                <label class="d-block mb-2">{{ field.label }}</label> {{ field.type }}
                                                <input v-if="!field.type || field.type == 'text'" type="text" v-model="field.value" class="full-width mb-2" />
                                                <input v-if="field.type == 'number'" type="number" min="0" step="any" v-model="field.value" class="full-width mb-2" />
                                                <input v-if="field.type == 'email'" type="email" v-model="field.value" class="full-width mb-2" />
                                                <textarea v-if="field.type == 'textarea'" v-model="field.value" class="full-width mb-2"></textarea>
                                                <select v-if="field.type == 'select'" v-model="field.value" class="full-width mb-2">
                                                    <option v-for="(opt, opti) in field.options.split(',')" :key="`option-select-index-${opti}`" :value="opt">{{opt}}</option>
                                                </select>
                                                <div v-if="field.type == 'radio'">
                                                    <label v-for="(opt, opti) in field.options.split(',')" class="full-width mb-2 d-block" :key="`option-radio-index-${opti}`">
                                                        <input type="radio" v-model="field.value" :value="opt"> {{opt}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </template>
                            <template v-else>
                                <template>
                                    <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#specs" :class="`accordion_buttons`">Additional Specifications</a>
                                    <div id="specs" class="accordion_contents">
                                        <?php // require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/forms/specifications.php' ); ?>
                                    </div>
                                </template>
                            </template> -->
                            
                            
                            <!--
                            <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#images" :class="`accordion_buttons`">Idea Gallery</a>
                            <div id="images" class="accordion_contents">
                                <?php // require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/forms/images.php' ); ?>
                            </div> -->

                            <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#features" :class="`accordion_buttons`">Features and Options</a>
                            <div id="features" class="accordion_contents">
                                <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/forms/features.php' ); ?>
                            </div>

                            <!-- <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#downloads" :class="`accordion_buttons`">Product Templates</a>
                            <div id="downloads" class="accordion_contents">
                                <?php // require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/forms/downloads.php' ); ?>
                            </div> -->

                            <!-- <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#templates" :class="`accordion_buttons`">Virtual Sample Template</a>
                            <div id="templates" class="accordion_contents">
                                <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/forms/templates.php' ); ?>
                            </div> -->

                            <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#seo" :class="`accordion_buttons`">SEO Content</a>
                            <div id="seo" class="accordion_contents">
                                <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/forms/seo.php' ); ?>
                            </div>

                        </div>


                        <div class="mt-5 d-block floating-button-save">
                            <button id="btn" type="submit" v-if="inputs.hid" class="button button-primary">Save Changes</button>
                            <button id="btn" type="submit" v-else class="button button-primary">Save Product Combo</button>

                            <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="button button-default">Cancel</a>
                        </div>

                    </form>

                </div>


                <div class="col-lg-12 col-xl-6" v-if="inputs.hid">

                    <h1 class="wp-heading-inline mb-3">
                        Pricing Breakdown
                    </h1>

                    <p v-if="getInputBreakdown.length">Available Quantity Breakdowns based on this Product Line.</p>

                    <p v-else>There is no quantity breakdown on this product line, you can create your own quantity breakdown based on this product quantities.</p>

                    <p style="display: flex; flex-wrap: wrap;"><code v-for="(qb, index) in getInputBreakdown" :key="`qbreakdown-${index}`" class="mr-2 mb-2">{{qb}}</code></p>

                    <div class="mt-5">
                        
                        <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-print-methods/breakdowns.php' ); ?>

                    </div>

                </div>

            </div>


        </div>
    </div>
</div>