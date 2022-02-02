<div class="mb-3">
    <label class="mb-2 d-block">Package Count</label>
    <div class="d-flex align-items-center">
        <input type="number" class="full-width mr-3" v-model="inputs.package_count_min" v-validate="'required'" name="package_count_min" data-vv-as="Package count min.">
        <span class="mr-3">of</span>
        <input type="number" class="full-width" v-model="inputs.package_count_max" v-validate="'required'" name="package_count_max" data-vv-as="Package count max.">
    </div>
    <div>
        <span class="v-error">{{errors.first('product.package_count_min')}}</span>
        <span class="v-error">{{errors.first('product.package_count_max')}}</span>
    </div>
</div>

<div class="mb-3">
    <label class="mb-2 d-block">Package count as:</label>
    <input type="text" v-model="inputs.package_count_as" />
    <small class="d-block">Note: Entering this field will be used as package count.</small>
</div>

<hr class="mb-3">

<div class="mb-3">
    <label class="mb-2 d-block">Imprint Area WxH</label>
    <div class="d-flex align-items-center">
        <input type="number" min="0" step="any" v-model="inputs.imprint_width" placeholder="Enter Width" class="full-width mr-3">
        <span class="mr-3">&times;</span>
        <input type="number" min="0" step="any" v-model="inputs.imprint_height" placeholder="Enter Height" class="full-width">
    </div>
</div>

<div class="mb-3">
    <label class="mb-2 d-block">Imprint Area as:</label>
    <input type="text" v-model="inputs.imprint_as" />
    <small class="d-block">Note: Entering this field will be used as imprint area.</small>
</div>

<hr class="mb-3">


<div class="mb-3">
    <label class="mb-2 d-block">FB Imprint Area WxH</label>
    <div class="d-flex align-items-center">
        <input type="number" min="0" step="any" v-model="inputs.imprint_bleed_wrap_width" placeholder="Enter Width" class="full-width mr-3">
        <span class="mr-3">&times;</span>
        <input type="number" min="0" step="any" v-model="inputs.imprint_bleed_wrap_height" placeholder="Enter Height" class="full-width">
    </div>
</div>

<div class="mb-3">
    <label class="mb-2 d-block">Custom Imprint Text:</label>
    <input type="text" v-model="inputs.imprint_bleed_as" placeholder="Ex: See Template" />
    <small class="d-block">Note: Entering this field will be used as fb imprint area.</small>
</div>

<hr class="mb-3">

<div class="mb-3">
    <label class="mb-2 d-block">Select Shape:</label>
    <select v-model="inputs.shape">
        <option value="">Not Applicable</option>
        <option value="Stock">Stock</option>
        <option value="Custom">Custom</option>
    </select>
</div>