<div class="mb-3 mt-3">

    <div class="row mb-3">

        <div v-for="(img, index) in inputs.templates" :key="`img-slides-pcombo-index-${index}`" class="col-md-4 mb-3">

            <a href="javascript:void(0)" @click.stop="selectImageTemplate(index)" class="d-block link-img mb-3">
                <img v-if="inputs.templates[index].image" :src="inputs.templates[index].image" alt="" class="full-width">
                <img v-else src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" class="full-width">
            </a>
            <input type="text" placeholder="Enter image title" v-model="inputs.templates[index].title" class="full-width mb-2">
            <a v-if="index!=0" href="javascript:void(0)" @click.stop="inputs.templates.splice(index, 1)" class="mt-2 mb-2"><small>remove</small></a>

        </div>
        
    </div>

    <div class="mb-3">
        <a href="javascript:void(0)" @click.stop="inputs.templates.push({ title: ``, image: `` })" class="button">Add Template</a>
    </div>

</div>