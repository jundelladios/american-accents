<div class="mt-3 mb-3">

    <div class="d-flex p-3 mb-3" v-for="(dl, index) in inputs.downloads" :key="`download-${index}`" style="border: 1px solid rgb(195, 195, 195);border-radius: 5px;">

        <div class="col-md-3 text-center">
            <a v-if="dl.preview" :href="dl.preview" target="_blank" class="link-img d-block" title="preview image">
                <img :src="dl.preview" alt="" style="width: 100%;">
            </a>
            <a href="javascript:void(0)" v-if="!dl.preview" class="d-block mt-2 mb-2" @click.stop="selectDownloadPhoto(index)">select photo</a>
            <a href="javascript:void(0)" v-else class="d-block mt-2 mb-2" @click.stop="removeDownloadPhoto(index)">remove</a>
        </div>

        <div class="col-md-9">

            <input type="text" v-model="dl.link" placeholder="Document Link">
            <a class="button" href="javascript:void(0)" @click.stop="selectDownloadLink(index)">select</a>
            <div class="d-block mt-2 mb-2">
                <input type="text" class="full-width" v-model="dl.title" placeholder="Enter Title" style="max-width: 230px;">
            </div>
            <a class="button mt-2" href="javascript:void(0)" @click.stop="removeDownload(index)">remove</a>
        </div>

    </div>

    <a class="button" @click.stop="addDownload">Add Item</a>

</div>