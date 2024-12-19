<a href="javascript:void(0)" @click="accordion"  data-accordion-module data-target="#bannerinformations" class="accordion_buttons">Product Banner</a>
<div id="bannerinformations" class="accordion_contents">
    <a 
    style="max-width:200px;"
    href="javascript:void(0)" 
    @click.prevent="chooseProductBanner" 
        class="d-block link-img nr img-form-wrap">
        <img v-if="inputs.banner_img" :src="inputs.banner_img" alt="" class="full-width">
        <img v-else src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" class="full-width">
    </a>
    <a v-if="inputs.banner_img" href="javascript:void(0)" @click.stop="inputs.banner_img=null;" class="mt-2 mb-2"><small>remove</small></a>
    
    <input type="text" 
    v-model="inputs.banner_class" class="full-width mt-3" placeholder="Enter Banner Class (Used for custom styling)" />

    <editor class="mt-3" v-model="inputs.banner_content" />
    
</div>