<div v-if="form" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content sm">
            <a href="javascript:void(0)" @click.stop="formInputs(false, {...defaultValue})" class="aa-close">&times;</a>
            <form @submit.prevent="saveSubCategoryMethod">

                <h1 class="wp-heading-inline mb-3">
                    <span v-if="inputs.index!=null">Edit Product Line for
                        <span class="d-block">{{ subcategory.sub_name }} -
                            <strong :style="`color:${inputs.printmethod.method_hex};`">
                                {{ inputs.printmethod.method_name2 }}
                            </strong>
                        </span>
                    </span>
                    <span v-else>New Product Line</span>
                </h1>

                <div class="aa-accordion">

                    <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#information" :class="`accordion_buttons`">Information</a>
                    <div id="information" class="active accordion_contents">
                        <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/forms/information.php' ); ?>
                    </div>

                    <!-- <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#key_features" :class="`accordion_buttons`">Key Features</a>
                    <div id="key_features" class="accordion_contents">
                        <?php // require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/forms/key_features.php' ); ?>
                    </div> -->

                    <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#charges" :class="`accordion_buttons`">Settings</a>
                    <div id="charges" class="accordion_contents">
                        <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/forms/charges_per_tag.php' ); ?>
                    </div>

                    <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#features" :class="`accordion_buttons`">Properties</a>
                    <div id="features" class="accordion_contents">
                        <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/forms/features_pivot.php' ); ?>
                    </div>

                    <!-- <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#colors" :class="`accordion_buttons`">Product Line Colors</a>
                    <div id="colors" class="accordion_contents">
                        <?php // require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/forms/colors.php' ); ?>
                    </div> -->

                    <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#notes" :class="`accordion_buttons`">Notes</a>
                    <div id="notes" class="accordion_contents">
                        <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/forms/notes.php' ); ?>
                    </div>

                    <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#compliances" :class="`accordion_buttons`">Compliances</a>
                    <div id="compliances" class="accordion_contents">
                        <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/forms/compliances.php' ); ?>
                    </div>

                    <a href="javascript:void(0)" @click.stop="accordion" data-accordion-module data-target="#seo" :class="`accordion_buttons`">SEO Contents</a>
                    <div id="seo" class="accordion_contents">
                        <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/forms/seo.php' ); ?>
                    </div>

                </div>


                <div class="mt-5 d-block">
                    <button id="btn" type="submit" v-if="inputs.index!=null" class="button button-primary">Save Changes</button>
                    <button id="btn" type="submit" v-else class="button button-primary">Save Product Line</button>
                </div>

            </form>
        </div>
    </div>
</div>