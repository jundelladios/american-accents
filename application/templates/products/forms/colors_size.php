<div class="mt-4 mb-5">

<!-- <div class="mb-2 row">
    <div class="col-md-6">
        <label class="d-block mb-2" for="color">Product Color</label>
        <colorpicker v-model="inputs.product_color_hex" />
    </div>
    <div class="col-md-6">
        <label class="d-block mb-2" for="colordesc">Color Description</label>
        <textarea v-model="inputs.product_color_details" cols="30" rows="2"></textarea>
    </div>
</div> -->

<div class="mb-2 row">
    <div class="col-md-6">
        <label class="d-block mb-2" for="psize">Product Size</label>
        <input min="0" step="any" type="number" v-model="inputs.product_size" id="psize" />
    </div>
    <div class="col-md-6">
        <label class="d-block mb-2" for="psizedesc">Size Description</label>
        <textarea id="psizedesc" v-model="inputs.product_size_details" cols="30" rows="2"></textarea>
    </div>
</div>

<div class="mb-2 row">
    <div class="col-md-6">
        <label class="d-block mb-2" for="psize">Product Thickness:</label>
        <input min="0" step="any" type="number" v-model="inputs.product_thickness" id="psize" />
    </div>
    <div class="col-md-6">
        <label class="d-block mb-2" for="pthickness">Material Thickness Description:</label>
        <textarea id="pthickness" v-model="inputs.product_tickness_details" cols="30" rows="2"></textarea>
    </div>
</div>

</div>