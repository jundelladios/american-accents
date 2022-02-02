<div class="mt-3 mb-3">

    <div class="mb-3">
        <a href="javascript:void(0)" @click.stop="wpImageBanner" class="d-block link-img ">
            <img v-if="inputs.banner_img" :src="inputs.banner_img" alt="" class="full-width">
            <img v-else src="<?php echo american_accent_plugin_base_url() . 'application/assets/img/landscape-placeholder.png'; ?>" alt="" class="full-width">
        </a>
        <a href="javascript:void(0)" v-if="inputs.banner_img" @click.stop="inputs.banner_img=''"><small>Remove Banner</small></a>
    </div>

    <div v-if="inputs.index==null" class="mb-3">
        <label class="d-block mb-2">Select Print Method</label>
        <select 
        v-model="inputs.print_method_id"
        v-validate="'required'"
        name="print_method"
        data-vv-as="print method"
        >
            <option v-for="(method, index) in methods.data" :value="method.hid" :key="`method-index-${index}`">{{ method.method_name }} {{ method.method_name2 }}</option>
        </select>
        <span class="v-error">{{errors.first('print_method')}}</span>
    </div>

    <div class="mb-3">
        <label class="d-block mb-2">Priority #</label>
        <input 
        type="number" 
        v-model="inputs.priority"
        v-validate="'required'"
        name="priority"
        data-vv-as="priority #"
        >
        <span class="v-error">{{errors.first('priority')}}</span>
    </div>

    <div class="mb-3 d-block" style="max-width: 230px;">
        <label class="d-block mb-2">Image</label>
        <a href="javascript:void(0)" @click.stop="wpImage" class="d-block link-img img-form-wrap">
            <img v-if="inputs.image" :src="inputs.image" alt="" class="full-width">
            <img v-else src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder.png'; ?>" alt="" class="full-width">
        </a>
        <a v-if="inputs.image" href="javascript:void(0)" @click.stop="inputs.image=null;" class="mt-2 mb-2"><small>remove</small></a>
    </div>

    <div class="mb-3">
        <label class="d-block mb-2">Pricing Tagline</label>
        <input 
        type="text" 
        v-model="inputs.price_tagline"
        >
    </div>
</div>