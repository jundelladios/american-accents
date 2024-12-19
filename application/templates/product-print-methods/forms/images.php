<div class="mb-3 mt-3">

    <div class="row mb-3">

        <div v-for="(img, index) in inputs.images" :key="`img-slides-pcombo-index-${index}`" class="col-md-6 mb-3">

            <a href="javascript:void(0)" @click.stop="selectImageSlides(index)" class="d-block link-img mb-3 img-form-wrap">
                <img v-if="inputs.images[index].image" :src="inputs.images[index].image" alt="" class="full-width">
                <img v-else src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" class="full-width">
            </a>
            <input type="text" placeholder="Enter image title" v-model="inputs.images[index].text" class="full-width mb-2">

            <div>
                <input type="text" placeholder="Download Link URL" v-model="inputs.images[index].downloadLink" class="full-width mb-2">
                <a href="javascript:void(0)" @click.stop="selectDownloadFileImage(index)" class="button mb-2">Select File</a>
            </div>
            
            <label for="per_thousand" class="mb-2"> 
            <input 
            v-model="inputs.images[index].usecurfile" 
            type="checkbox" 
            value="per_thousand"
            :true-value="true"
            :false-value="false">
            Use current photo for the download link url.</label>

            <a href="javascript:void(0)" @click.stop="inputs.images.splice(index, 1)" class="mt-2 mb-2 d-block"><small>remove</small></a>

        </div>
        
    </div>

    <div class="mb-3">
        <a href="javascript:void(0)" @click.stop="inputs.images.push({ text: ``, image: ``, downloadLink: ``, usecurfile: false })" class="button">Add Image</a>
    </div>

</div>