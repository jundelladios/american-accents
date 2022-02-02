<div class="mt-4 mb-5">

    <!-- formula LxWxH -->
    
    <div class="mb-2 row mr-0">
        <div class="col-md-6">
            <label class="d-block mb-2" for="cweight">Case Weight*</label>
            <input 
            type="number" 
            v-model="inputs.case_weight" 
            id="cweight"
            v-validate="'required'"
            data-vv-as="case weight"
            name="case_weight" />
            <span class="v-error">{{errors.first('case_weight')}}</span>
        </div>
        <div class="col-md-6">
            <label class="d-block mb-2" for="cdweight">Dim Weight**</label>
            <input 
            type="number" 
            v-model="inputs.case_dim_weight" 
            id="cdweight"
            v-validate="'required'"
            data-vv-as="case dim weight"
            name="case_dim_weight" />
            <span class="v-error">{{errors.first('case_dim_weight')}}</span>
        </div>
    </div>

    <div class="mb-2 row mr-0">
        <div class="col-md-6">
            <label class="d-block mb-2" for="cquantity">Case Quantity+</label>
            <input 
            type="number" 
            v-model="inputs.case_quantity" 
            id="cquantity"
            v-validate="'required'"
            data-vv-as="case quantity"
            name="case_quantity" />
            <span class="v-error">{{errors.first('case_quantity')}}</span>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12"><h4>Case Dim ( L x W x H )</h4></div>
        <div class="col-md-4">
            <label class="d-block mb-2" for="clength">Length</label>
            <input 
            class="full-width"
            type="number" 
            v-model="inputs.case_length" 
            id="clength"
            v-validate="'required'"
            data-vv-as="case length"
            name="case_length" />
            <span class="v-error">{{errors.first('case_length')}}</span>
        </div>
        <div class="col-md-4">
            <label class="d-block mb-2" for="cwidth">Width</label>
            <input 
            class="full-width"
            type="number" 
            v-model="inputs.case_width" 
            id="cwidth"
            v-validate="'required'"
            data-vv-as="case width"
            name="case_width" />
            <span class="v-error">{{errors.first('case_width')}}</span>
        </div>
        <div class="col-md-4">
            <label class="d-block mb-2" for="cheight">Height</label>
            <input 
            class="full-width"
            type="number" 
            v-model="inputs.case_height" 
            id="cheight"
            v-validate="'required'"
            data-vv-as="case height"
            name="case_height" />
            <span class="v-error">{{errors.first('case_height')}}</span>
        </div>
    </div>

</div>