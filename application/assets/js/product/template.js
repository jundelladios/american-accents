var template_ = /*html */`

<div>

<div v-if="isMounted && variantLoading">
    <div class="product-templates mt-4 mb-5">
        <h5 class="font-weight-bold text-uppercase d-block">product templates</h5>
        <div class="tbl-overflow">
            <div class="carousel non-slide gallery-item-small">

                <div v-for="(i, index) in 3" 
                :key="'idea-gallery-key' + index" 
                class="img-slide-item"
                >
                    <div class="img-fit-wrap p-2">
                        <v-img 
                        :loading="true"
                        loadersize="sm"
                        :img="null"
                        :width="62"
                        :height="61"
                        />
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div v-if="getVariationEntries && getVariationEntries.templatedata.length">
    <div class="product-templates mt-4 mb-5">
        <h5 class="font-weight-bold text-uppercase d-block">product templates</h5>
        <div class="tbl-overflow">
            <div class="carousel non-slide gallery-item-small">

                <div v-for="(tmp, tmpkey) in getVariationEntries.templatedata.slice(0, limit)" :key="'template-key' + tmpkey" class="img-slide-item">
                    <div class="img-fit-wrap p-0"
                    data-toggle="modal"
                    data-target="#producttemplatesmodal"
                    >
                        <v-img
                        :loading="variantLoading"
                        loadersize="sm"
                        :img="tmp.preview"
                        :width="62"
                        :height="61"
                        />
                    </div>
                </div>

                <div class="img-slide-item more align-items-center justify-content-center">
                    <a href="#" data-toggle="modal"
                    data-target="#producttemplatesmodal" target="_blank" rel="nofollow" class="more-item-gallery">
                        <span class="icon d-block icon-paper-download"></span>
                        <span class="d-block text-uppercase font-weight-bold font-10 text-xs">download templates</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <div class="modal zoom-in" v-if="!variantLoading" data-backdrop="static" data-modal-scroll id="producttemplatesmodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pt-3 pb-5">
                    <h5 class="mb-5 modal-title-product font-weight-bold text-uppercase text-center">product templates</h5>

                    <div class="mb-5 product-template-modal-content-wrap">
                        <div 
                        v-for="(tmp, tmpkey) in getVariationEntries.templatedata" 
                        :key="'template-key-modal-' + tmpkey"
                        class="product-template-modal-content-item">
                            <div class="p-template-wrap">

                                <div class="ptemplate-img-wrap">
                                    <v-img 
                                    :img="tmp.preview"
                                    :width="283"
                                    :height="283"
                                    />
                                </div>

                                <div class="button-actions mt-3">
                                    <a href="#" :data-url="tmp.link" data-type="email" class="btn-action button-light aa_social_share"><span class="icon mr-1 icon-email"></span> email</a>
                                    <a :href="tmp.link" target="_blank"
                                    class="btn-action button-light frontend-desktop-only"><span class="icon mr-1 icon-icon-print"></span> print</a>
                                    <a v-if="tmp.link" :href="tmp.link" target="_blank" rel="nofollow" download class="btn-action button-light"><span class="icon mr-1 icon-download-single"></span> download</a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div v-if="getTemplateArchive" class="modal-button-centered text-center">
                        <a :href="getTemplateArchive" rel="nofollow" class="btn btn-primary" download>
                            <span class="icon icon-download-archive mr-1"></span>
                            <span class="text">Download All</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <a href="javascript:void(0)" class="back_to_top inmodal" style="display: block;" data-elem="#producttemplatesmodal">
            <span class="icon-topscroll icon d-block"></span>
            <span class="top-text text-uppercase">back to top</span>
        </a>

    </div>
</div>

</div>
`;