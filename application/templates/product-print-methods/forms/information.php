<div class="mb-3 mt-3">

    <div class="mb-3">

        <div class="row">

            <div class="col-md-12">
                <label for="allowprintmethodprefix">
                    <input type="checkbox" id="allowprintmethodprefix" v-model="inputs.allow_print_method_prefix" :true-value="1" :false-value="0">
                    Allow print method prefix in product name?
                </label>
            </div>

            <div class="col-md-6">
                <p>Product Image</p>
                <a href="javascript:void(0)" @click.stop="chooseFeatureImage" class="d-block link-img nr img-form-wrap">
                    <img v-if="inputs.feature_img.image" :src="inputs.feature_img.image" alt="" class="full-width">
                    <img v-else src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" class="full-width">
                </a>
                <a v-if="inputs.feature_img.image" href="javascript:void(0)" @click.stop="inputs.feature_img.image=null;" class="mt-2 mb-2"><small>remove</small></a>
                <input type="text" class="mt-2 d-block" placeholder="Image Info" v-model="inputs.feature_img.title">
                <input type="number" class="mt-2 d-block" placeholder="Top Position  (%)" v-model="inputs.feature_img.top">
                <input type="hidden" v-model="inputs.feature_img.image" v-validate="'required'" data-vv-as="product image" name="product_img">
                <span class="v-error">{{errors.first('product.product_img')}}</span>
            </div>

            <!--
            <div class="col-md-6">
                <p>Description Image</p>
                <a href="javascript:void(0)" @click.stop="chooseDescriptionImage" class="d-block link-img">
                    <img v-if="inputs.showcase_img.image" :src="inputs.showcase_img.image" alt="" class="full-width">
                    <img v-else src="<?php // echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" class="full-width">
                </a>
                <a v-if="inputs.showcase_img.image" href="javascript:void(0)" @click.stop="inputs.showcase_img.image=null;" class="mt-2 mb-2"><small>remove</small></a>
                <input type="text" class="mt-2 d-block" placeholder="Image Info" v-model="inputs.showcase_img.title">
            </div>
            -->

        </div>
        
    </div>


    <div class="mb-3">
        <label class="d-block mb-2" for="">Select Product Line</label>
        <div v-if="plines.loading">
            <p>Loading...</p>
        </div>
        <div v-else>
            <div v-if="plines.data">
                <select v-model="inputs.product_line_id" v-validate="'required'" name="product_line_id" data-vv-as="product line">
                    <option v-for="(pl, index) in plines.data" :value="pl.id" :key="`pline-${index}`">{{pl.printmethod.method_name}} {{pl.printmethod.method_name2}}</option>
                </select>
                <span class="v-error">{{errors.first('product.product_line_id')}}</span>
            </div>
            <p v-else>Please add product line.</p>
        </div>
    </div>

    <!-- <div class="mb-3">
        <label class="d-block mb-2" for="">Product Description ( description for the pricing )</label>
        <textarea v-model="inputs.min_desc" class="full-width" v-validate="'required'" data-vv-as="product description" name="min_desc"></textarea>
        <span class="v-error">{{errors.first('product.min_desc')}}</span>
    </div> -->

    <!-- <div class="mb-3">
        <label class="d-block mb-2" for="">Price (per piece)</label>
        <input type="number" min="0" step="any" v-model="inputs.min_value" v-validate="'required'" data-vv-as="product minimum price" name="min_value">
        <span class="v-error">{{errors.first('product.min_value')}}</span>
    </div> -->

    <div class="mb-3">
        <label class="d-block mb-2" for="">Description</label>
        <editor v-model="inputs.description" />
    </div>

    <div class="mb-3">
        <label class="d-block mb-2" for="">Priority #</label>
        <input type="number" v-model="inputs.priority" v-validate="'required'" name="priority" />
        <span class="v-error">{{errors.first('product.priority')}}</span>
    </div>

    <!-- <div class="mb-3">
        <label class="d-block mb-2" for="">Disclaimer</label>
        <textarea v-model="inputs.disclaimer" name="" id=""rows="10" class="full-width"></textarea>
    </div> -->

    <div v-if="!specification.loading && specification.data.length" class="mt-3 mb-2">
        <label class="d-block mb-2" for="stype">Select Specification Type</label>
        <select v-model="inputs.specification_id" class="full-width" @input="$e => setSpecificationJson($e.target.value)">
            <option v-for="(spc, spci) in getSpecData" :key="`spc-index-${spci}`" :value="spc.hid">{{spc.title}}</option>
        </select>
        <p><strong>Specification Not Working? </strong> refresh the specification type by selecting another specification and re-select the desired spec type.</p>
    </div>

    <div v-if="!specification.loading && specification.data.length" class="mt-3 mb-2">
        <label class="d-block mb-2" for="stype">Select Specification Output</label>
        <select v-model="inputs.specification_output_id" class="full-width">
            <option v-for="(spc, spci) in getSpecOutputData" :key="`spc-indexoutput-${spci}`" :value="spc.hid">{{spc.title}}</option>
        </select>
    </div>

    <p v-if="!specification.loading && !getSpecOutputData.length" class="mb-2">
		Please add specification output in Specification Types.
	</p>

</div>