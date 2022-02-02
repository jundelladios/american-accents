<div class="mt-4 mb-5">

<div class="mb-2 row mr-0">
    <div class="col-md-4">
        <label class="d-block mb-2" for="idetatils">Item Width</label>
        <input type="number" min="0" step="any" v-model="inputs.item_width" id="idetatils" class="full-width" />
    </div>
    <div class="col-md-4">
        <label class="d-block mb-2" for="iheight">Item Height</label>
        <input type="number" min="0" step="any" v-model="inputs.item_height" id="iheight" class="full-width" />
    </div>
    <div class="col-md-4">
        <label class="d-block mb-2" for="depth">Item Depth</label>
        <input type="number" min="0" step="any" v-model="inputs.product_depth" id="depth" class="full-width" />
    </div>
</div>

<hr class="mt-3">

<div class="mt-3">
    <label class="d-block mb-2" for="areasq">Area SQ:</label>
    <input type="number" min="0" step="any" v-model="inputs.area_sq_in" id="areasq" />
</div>

</div>