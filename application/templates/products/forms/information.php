<div class="mt-4 mb-5">

<div class="mb-2">
    <label class="d-block mb-2" for="product_name">Product Name</label>
    <input type="text" 
    v-model="inputs.product_name" 
    id="product_name" 
    class="full-width"
    v-validate="'required'"
    name="product_name"
    data-vv-as="product name" />
    <span class="v-error">{{errors.first('product_name')}}</span>
</div>

<div class="mb-2">
    <label class="d-block mb-2" for="">Product Description</label>
    <textarea v-model="inputs.product_description" class="full-width" v-validate="'required'" data-vv-as="product description" name="product_description"></textarea>
    <span class="v-error">{{errors.first('product_description')}}</span>
</div>

<div class="mb-2">
    <label class="d-block mb-2" for="pslug">Product Slug</label>
    <input type="text" 
    v-model="inputs.product_slug" 
    id="product_slug"
    v-validate="'required'"
    name="product_slug"
    data-vv-as="product slug" />
    <span class="v-error">{{errors.first('product_slug')}}</span>
</div>

<div class="mb-2">
    <label class="d-block mb-2" for="priority">Priority #</label>
    <input 
    type="number" 
    v-model="inputs.priority" 
    id="priority"
    v-validate="'required'"
    name="priority"
    data-vv-as="priority" />
    <span class="v-error">{{errors.first('priority')}}</span>
</div>


<div class="mb-2">
    <label class="d-block mb-2" for="mtype">Material Type</label>
    <!-- <input type="text" v-model="inputs.material_type" id="mtype" /> -->
    <div v-if="pfilters.loading">
        <p>Loading...</p>
    </div>
    <div v-else>
        <div v-if="pfilters.data.materials">
            <label v-for="(m, index) in pfilters.data.materials" :key="`mtypes-${index}`" class="d-block mb-3">
                <input type="radio" :value="m.material_type" v-model="inputs.material_type">
                {{m.material_type}}
            </label>
        </div>
        <div class="mt-3">
            <input type="text" placeholder="Enter Material type" v-model="inputs.material_type">
            <small class="d-block">Enter material type if not in radio select.</small>
        </div>
    </div>
</div>

<div v-if="!specification.loading && specification.data.length" class="mt-3 mb-2">
    <label class="d-block mb-2" for="stype">Select Specification Type</label>
    <select v-model="inputs.specification_id" @input="$e => setSpecificationJson($e.target.value)">
        <option v-for="(spc, spci) in specification.data.filter(row => !row.isspec)" :key="`spc-index-${spci}`" :value="spc.hid">{{spc.title}}</option>
    </select>
    <p><strong>Specification Not Working? </strong> refresh the specification type by selecting another specification and re-select the desired spec type.</p>
</div>

</div>