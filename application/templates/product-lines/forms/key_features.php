<div class="mt-3 mb-3">

    <div class="mb-3 d-block">

    <div v-for="(feature, index) in inputs.features" :key="`feature-lists-${index}`" class="d-flex mb-3">

        <div class="mr-3">
            <input type="text" v-model="feature.image" placeholder="Enter icon name." />
            <p>Icon preview: <span :class="`icon ${feature.image} icon-dark`"></span></p>
            <small class="d-block">Icon names found in settings > theme icons.</small>
        </div>

        <div style="width: 100%;" class="mr-3">
            <input type="text" v-model="feature.text" class="full-width">
        </div>

        <div>
            <a v-if="index!=0" title="remove" href="javascript:void(0)" @click.stop="inputs.features.splice(index, 1);">&times;</a>
        </div>

    </div>

    <a href="javascript:void(0)" @click.stop="inputs.features.push({ image: '', text: '' })" class="button mb-3">Add Key Feature</a>

    </div>

</div>