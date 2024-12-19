
<div v-if="form" class="aa-popup">

    <div class="aa-content-wrap">
        <div class="aa-content">
            <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="aa-close">&times;</a>
            <h1 class="wp-heading-inline">
                <span v-if="inputs.product_name">Editing {{ inputs.product_name }}</span>
                <span v-else>New Product</span>
                <!-- <span v-else>New Category</span> -->
            </h1>
            <small class="d-block">Please complete the highlighted field.</small>

            <div class="mt-4">

                <form action="/" @submit.prevent="saveProduct" class="mt-3" autocomplete="off">

                    <div class="aa-accordion">

                        <a href="javascript:void(0)" @click="accordion"  data-accordion-module data-target="#information" :class="`accordion_buttons ${!checkInformation ? 'required' : ''}`">Product Information</a>
                        <div id="information" class="active accordion_contents">
                            <?php require_once( american_accent_plugin_base_dir() . 'application/templates/products/forms/information.php' ); ?>
                        </div>

                        <template
                        v-for="(spec, speci) in inputs.specs_json"
                        >
                            <a href="javascript:void(0)" :key="`data-specbutton-${spec.groupid}`" @click="accordion" data-accordion-module :data-target="`#${spec.groupid}-index-${speci}`" class="accordion_buttons">{{ spec.group }}</a>
                            <div :id="`${spec.groupid}-index-${speci}`" :key="`data-speccontent-${spec.groupid}`" class="accordion_contents">
                                <div class="row">
                                    <div
                                    v-for="(field, fieldi) in spec.fields"
                                    :key="`spec-field-${fieldi}`"
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

                        <!-- <a href="javascript:void(0)" @click="accordion" data-accordion-module data-target="#color_and_size" class="accordion_buttons">Size and Thickness</a>
                        <div id="color_and_size" class="accordion_contents">
                            <?php // require_once( american_accent_plugin_base_dir() . 'application/templates/products/forms/colors_size.php' ); ?>
                        </div> -->
                        
                        <!-- <template v-if="!getSelectedSpectype">
                            <template>
                                <a href="javascript:void(0)" @click="accordion" data-accordion-module data-target="#case" :class="`accordion_buttons ${!checkCase ? 'required' : ''}`">Case Details</a>
                                <div id="case" class="accordion_contents">
                                    <?php // require_once( american_accent_plugin_base_dir() . 'application/templates/products/forms/case.php' ); ?>
                                </div>

                                <a href="javascript:void(0)" @click="accordion" data-accordion-module data-target="#dim" class="accordion_buttons">Dim Details</a>
                                <div id="dim" class="accordion_contents">
                                    <?php // require_once( american_accent_plugin_base_dir() . 'application/templates/products/forms/dim.php' ); ?>
                                </div>

                                <a href="javascript:void(0)" @click="accordion" data-accordion-module data-target="#item_details" class="accordion_buttons">Item Size ( W x H X D )</a>
                                <div id="item_details" class="accordion_contents">
                                    <?php // require_once( american_accent_plugin_base_dir() . 'application/templates/products/forms/item.php' ); ?>
                                </div>

                                <a href="javascript:void(0)" @click="accordion" data-accordion-module data-target="#pallet" :class="`accordion_buttons ${!checkPallet ? 'required' : ''}`">Pallet Details</a>
                                <div id="pallet" class="accordion_contents">
                                    <?php // require_once( american_accent_plugin_base_dir() . 'application/templates/products/forms/pallet.php' ); ?>
                                </div>

                                <a href="javascript:void(0)" @click="accordion" data-accordion-module data-target="#others" class="accordion_buttons">Other Details</a>
                                <div id="others" class="accordion_contents">
                                    <?php // require_once( american_accent_plugin_base_dir() . 'application/templates/products/forms/others.php' ); ?>
                                </div>
                            </template>
                        </template>
                        <template v-else>
                            <template
                            v-for="(spec, speci) in inputs.specs_json"
                            >
                                <a href="javascript:void(0)" :key="`data-specbutton-${spec.groupid}`" @click="accordion" data-accordion-module :data-target="`#${spec.groupid}-index-${speci}`" class="accordion_buttons">{{ spec.group }}</a>
                                <div :id="`${spec.groupid}-index-${speci}`" :key="`data-speccontent-${spec.groupid}`" class="accordion_contents">
                                    <div class="row">
                                        <div
                                        v-for="(field, fieldi) in spec.fields"
                                        :key="`spec-field-${fieldi}`"
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
                        </template> -->
                        
                        <?php require_once( american_accent_plugin_base_dir() . 'application/templates/products/forms/banners.php' ); ?>

                    </div>

                    <div class="floating-button-save">
                        <button id="btn" type="submit" class="button button-primary">Save Product</button>
                        <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="button button-default">Cancel</a>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>