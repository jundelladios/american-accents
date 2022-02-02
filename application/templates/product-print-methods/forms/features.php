<div class="mb-3 mt-3">

    <!-- <div class="row mb-3">

        <div v-for="(feature, index) in inputs.features_options" :key="`feature-index-${index}`" class="col-md-12 mb-3">

            <textarea placeholder="Enter Feature or Option" rows="3" v-model="inputs.features_options[index]" class="full-width"></textarea>
            <a v-if="index!=0" href="javascript:void(0)" @click.stop="inputs.features_options.splice(index, 1)" class="mt-2 mb-2"><small>remove</small></a>

        </div>
        
    </div>

    <div class="mb-3">
        <a href="javascript:void(0)" @click.stop="inputs.features_options.push(null)" class="button">Add Feature/Option</a>
    </div> -->

    <div class="mb-3">
        <editor v-model="inputs.features_options2" />
    </div>

</div>