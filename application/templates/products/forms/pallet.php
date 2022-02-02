<div class="mt-4 mb-5">

<div class="mb-2 row mr-0">
    <div class="col-md-6">
        <label class="d-block mb-2" for="pquantity">Cases/Pallet</label>
        <input 
        type="number" 
        v-model="inputs.pallet_quantity" 
        id="pquantity"
        v-validate="'required'"
        data-vv-as="pallet quantity"
        name="pallet_quantity" />
        <span class="v-error">{{errors.first('pallet_quantity')}}</span>
    </div>

    <div class="col-md-6">
        <label class="d-block mb-2" for="pweight">Weight</label>
        <input 
        type="number" 
        v-model="inputs.pallet_weight" 
        id="pweight"
        v-validate="'required'"
        data-vv-as="pallet weight"
        name="pallet_weight" />
        <span class="v-error">{{errors.first('pallet_weight')}}</span>
    </div>
</div>



<div class="row">
    <div class="col-md-12"><h4>Pallet Dim ( L x W x H )</h4></div>
    <div class="col-md-4">
        <label class="d-block mb-2" for="plength">Length</label>
        <input 
        class="full-width"
        type="number" 
        v-model="inputs.pallet_length" 
        id="plength"
        v-validate="'required'"
        data-vv-as="pallet length"
        name="pallet_length" />
        <span class="v-error">{{errors.first('pallet_length')}}</span>
    </div>

    <div class="col-md-4">
        <label class="d-block mb-2" for="pwidth">Width</label>
        <input 
        class="full-width"
        type="number" 
        v-model="inputs.pallet_width" 
        id="pwidth"
        v-validate="'required'"
        data-vv-as="pallet width"
        name="pallet_width" />
        <span class="v-error">{{errors.first('pallet_width')}}</span>
    </div>


    <div class="col-md-4">
        <label class="d-block mb-2" for="pheight">Height</label>
        <input 
        class="full-width"
        type="number" 
        v-model="inputs.pallet_height" 
        id="pheight"
        v-validate="'required'"
        data-vv-as="pallet height"
        name="pallet_height" />
        <span class="v-error">{{errors.first('pallet_height')}}</span>
    </div>

</div>

</div>