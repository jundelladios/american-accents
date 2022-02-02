<div class="mt-3 mb-3">

    <div class="mb-3 d-block">

    <div v-for="(feature, index) in inputs.features_pivot" :key="`feature-lists-${index}`" class="d-flex mb-3 align-items-center">

        <div style="width: 50px;" class="mr-3">

            <a href="javascript:void(0)" @click.stop="wpImageFeaturePivot(index)" class="d-block link-img" title="select icon">
                <img v-if="feature.image" :src="feature.image" alt="" class="full-width">
                <img v-else src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder-square.png'; ?>" alt="" class="full-width">
            </a>

            <a v-if="feature.image" href="javascript:void(0)" @click.stop="feature.image=null;"><small>remove</small></a>

        </div>

        <div style="width: 100%;" class="mr-3">
            <input type="text" v-model="feature.text" class="full-width">
        </div>

        <div>
            <a v-if="index!=0" title="remove" href="javascript:void(0)" @click.stop="inputs.features_pivot.splice(index, 1);">&times;</a>
        </div>

    </div>

    <a href="javascript:void(0)" @click.stop="inputs.features_pivot.push({ image: '', text: '' })" class="button mb-3">Add Property</a>

    </div>

</div>