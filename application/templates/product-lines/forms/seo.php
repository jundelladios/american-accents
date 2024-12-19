<div class="mb-3">
    <textarea rows="3" v-model="inputs.seo_content.description" placeholder="Meta Description" class="full-width"></textarea>
</div>
<div class="mb-3">
    <label>Seo Banner Image (600x314)</label>
    <a href="javascript:void(0)" @click.stop="seoImage" class="d-block link-img">
        <img v-if="inputs.seo_content.image" :src="inputs.seo_content.image" alt="" class="full-width">
        <img v-else src="<?php echo american_accent_plugin_base_url() . 'application/assets/img/landscape-placeholder.png'; ?>" alt="" class="full-width">
    </a>
    <a v-if="inputs.seo_content.image" href="javascript:void(0)" @click="inputs.seo_content.image=null">remove</a>
</div>
<div class="mb-3">
    <p>More information with <a href="https://developers.facebook.com/docs/sharing/webmasters/" target="_blank">Open Graph</a>.</p>
</div>