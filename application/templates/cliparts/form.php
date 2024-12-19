<div v-if="form" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content md">
            <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="aa-close">&times;</a>
            <h1 class="wp-heading-inline">
                <span v-if="inputs.index!=null">Edit ClipArt</span>
                <span v-else>New ClipArt</span>
            </h1>
            <form action="/" @submit.prevent="saveClipArt" class="mt-3" autocomplete="off">

                <div class="mb-3">
                    <label class="d-block mb-2">Enter Clip Art Category</label>
                    <input type="text" v-model="inputs.clipartcategory" v-validate="'required'" id="clipartcategory" name="clipartcategory" data-vv-as="clip art category" />
                    <span class="v-error">{{errors.first('clipartcategory')}}</span>
                </div>

                <div class="mb-3">
                    <label class="d-block mb-2">Priority #</label>
                    <input type="text" v-model="inputs.priority" v-validate="'required'" id="priority" name="priority" data-vv-as="priority" />
                    <span class="v-error">{{errors.first('priority')}}</span>
                </div>

                <div class="mb-3">
                    <a href="javascript:void(0)" @click.stop="addClipArtIcon" class="button">Add clipart images</a>
                </div>

                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div v-for="(clp, index) in inputs.clipartdata" :key="`clip-art-${index}`" class="col-md-4 mb-4">
                            <div>
                                <a href="javascript:void(0)" @click.stop="selectclipimage(index)" class="d-block link-img mb-3 img-form-wrap" title="select icon">
                                    <img v-if="clp.image" :src="clp.image" alt="" style="width: auto; max-width: 100%;">
                                    <img v-else src="<?php echo american_accent_plugin_base_url() . '/application/assets/img/placeholder-square.png'; ?>" alt="" class="full-width">
                                </a>
                                <input type="text" v-model="inputs.clipartdata[index].title" class="full-width" placeholder="Clip Art Info">
                                <a href="javascript:void(0)" @click.stop="removeclipart(index)" class="button mt-2">remove</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="floating-button-save">
                    <a href="javascript:void(0)" @click.stop="addClipArtIcon" class="button mr-2">Add clipart images</a>
                    <button type="submit" id="btn" v-if="inputs.index!=null" class="button button-primary mr-2">Save Changes</button>
                    <button type="submit" id="btn" v-else class="button button-primary mr-2">Save Clipart</button>
                    <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="button button-default">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>