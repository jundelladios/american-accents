<div class="mt-3 mb-3">

    <div class=" p-3 mb-3" v-for="(compliance, index) in inputs.compliances" :key="`compliance-${index}`" style="border: 1px solid rgb(195, 195, 195);border-radius: 5px;">

        <div class="col-md-12 mb-3 text-center">
            <a v-if="compliance.previewImage" :href="compliance.previewImage" target="_blank" class="link-img d-block" title="preview image">
                <img :src="compliance.previewImage" alt="" style="width: 100%;">
            </a>
            <a href="javascript:void(0)" v-if="!compliance.previewImage" class="d-block mt-2 mb-2" @click.stop="selectPreviewDocument(index)">select photo</a>
            <a href="javascript:void(0)" v-else class="d-block mt-2 mb-2" @click.stop="removePreview(index)">remove photo</a>
        </div>

        <div class="col-md-12">
            <input type="text" class="full-width d-block mb-3" v-model="compliance.compliance" placeholder="Compliance Name">
            <input type="text" v-model="compliance.documentLink" placeholder="Document Link">
            <a class="button" href="javascript:void(0)" @click.stop="selectDocument(index)">select</a>
            <a class="button" href="javascript:void(0)" @click.stop="removeCompliances(index)">remove</a>
        </div>

    </div>

    <a class="button" @click.stop="addCompliances">Add Compliance</a>

</div>