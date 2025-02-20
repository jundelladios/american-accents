var ideagalleries = /*html */`
<div>

<div v-if="isMounted && variantLoading">
    <div class="idea-galleries mt-4">
        <h5 class="font-weight-bold text-uppercase d-block">idea gallery</h5>
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

<div v-if="getVariationEntries && getVariationEntries.ideagallerydata.length">
    <div class="idea-galleries mt-4">
        <h5 class="font-weight-bold text-uppercase d-block">idea gallery</h5>
        
        <div class="tbl-overflow">
            <div class="carousel non-slide gallery-item-small">

                <div v-for="(idg, ideagallerykey) in getVariationEntries.ideagallerydata.slice(0, limit)" 
                :key="'idea-gallery-key' + ideagallerykey" 
                class=" bg-transparent"
                @click.stop="openidegallerydefaultImage(ideagallerykey)"
                >
                    
                    <div v-if="idg.image.split('.').pop() != 'html'">
                        <div 
                        class="img-slide-item img-fit-wrap p-2 bg-slidethumb bg-slidethumbidg"
                        :style="{
                            paddingTop: idg.top + '%!important'
                        }"
                        >
                            <v-img 
                            :loading="variantLoading"
                            loadersize="sm"
                            :img="idg.image"
                            :width="62"
                            :height="61"
                            />
                        </div>
                        <span class="img-slide-caption idgthumb mt-1">{{idg.text}}</span>
                    </div>
                    
                    <div v-else>
                        <div 
                        class="img-slide-item img-fit-wrap p-2 bg-transparent bg-slidethumbidg"
                        :style="{
                            paddingTop: idg.top + '%!important'
                        }"
                        >
                            <iframe 
                            :src="idg.image" class="full-width full-height border-0 overflow-hidden" scrolling="no"></iframe>
                        </div>
                        <span class="img-slide-caption idgthumb mt-1">{{idg.text}}</span>
                    </div>

                </div>

                <div 
                class="img-slide-item align-items-center justify-content-center more-idg" 
                @click.stop="openidegallerydefaultImage(0)">
                    <div class="more-item-gallery">
                        <span class="icon d-block icon-image"></span>
                        <span class="d-block text-uppercase font-weight-bold font-10 text-xs">view full gallery</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- idea gallery modal -->
    <div class="modal zoom-in" v-if="!variantLoading" data-backdrop="static" id="ideagallerymodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pt-3 pb-5">
                    <div class="idea-gallery-info">
                        <h5 class="mb-4 modal-title-product font-weight-bold text-uppercase">idea gallery</h5>
                        <div>
                            <h4 class="mb-1 ideagallery-pname font-extra-bold text-uppercase" :style="\`color: \${product.productline.printmethod.method_hex};\`">
                                {{ product.product_method_combination_name }}
                            </h4>
                            <p class="ideagallery-min">{{ product.product.product_description }}</p>
                        </div>
                    </div>

                    <div class="idea-gallery-main-slider">
                        <div class="carousel-main-wrap"
                        >
                            <vue-slick-carousel 
                            ref="ideamain" 
                            class="carousel carousel-idea-main ideagallery slide-main ideagallery-modal-main" 
                            :key="'ideagallery-modal-main-images-'+realtimeKey" 
                            v-bind="ideagallerycarousel"
                            :asNavFor="ideagalleryThumbNavfor"
                            @beforeChange="ideaprogressBarUpdate"
                            >
                                <div class="img-slide-item bg-transparent" 
                                v-for="(idg, ideagallerykey) in getVariationEntries.ideagallerydata" 
                                :key="'ideagallery-modal-main-key-'+ideagallerykey"
                                >
                                    <div 
                                    v-if="idg.image.split('.').pop() != 'html'"
                                    class="img-fit-wrap bg-slidethumb"
                                    :style="{
                                        paddingTop: idg.top + '%!important'
                                    }"
                                    >
                                        <v-img 
                                        :img="idg.image"
                                        :width="373"
                                        :height="411"
                                        />
                                    </div>

                                    <div 
                                    v-else
                                    class="img-fit-wrap p-2 bg-transparent"
                                    :style="{
                                        paddingTop: idg.top + '%!important'
                                    }"
                                    >
                                        <iframe 
                                        :src="idg.image" class="full-width full-height border-0" scrolling="no"></iframe>
                                    </div>


                                    <span class="img-slide-caption mt-2 mb-2">{{idg.text}}</span>


                                    <div class="button-actions mt-3">
                                        <a href="#" :data-url="idg.image" data-type="email" class="btn-action button-light aa_social_share"><span class="icon mr-1 icon-email"></span> email</a>
                                        <a href="#" @click.prevent="() => printJS({
                                            printable: idg.image,
                                            documentTitle: idg.text,
                                            type: 'image',
                                            imageStyle: 'display:block;margin:20px auto;max-height:100%;max-width:100%;',
                                            showModal: true
                                        })" class="btn-action button-light frontend-desktop-only"><span class="icon mr-1 icon-icon-print"></span> print</a>
                                        <template v-if="idg.usecurfile">
                                            <a :href="idg.image" class="btn-action button-light" download target="_blank" rel="nofollow" class="btn-action button-light"><span class="icon mr-1 icon-download-single"></span> download</a>
                                        </template>
                                        <template v-else>
                                            <a v-if="idg.downloadLink" :href="idg.downloadLink" class="btn-action button-light" download target="_blank" rel="nofollow" class="btn-action button-light"><span class="icon mr-1 icon-download-single"></span> download</a>
                                        </template>
                                        <a href="#" @click.prevent="$refs.shareref[ideagallerykey].openModal()" class="btn-action button-light"><span class="icon mr-1 icon-share"></span> share</a>
                                    </div>
                                </div>
                                

                                <template #nextArrow="arrowOption">
                                    <button class="idea-modal-arrow right"><span class="icon idea-arrow icon-arrow-right"></span></button>
                                </template>
                                <template #prevArrow="arrowOption">
                                    <button class="idea-modal-arrow left rotate-left"><span class="icon idea-arrow icon-arrow-right"></span></button>
                                </template>
                            </vue-slick-carousel>
                        </div>
                    </div>


                    <!-- thumbnails -->
                    <div class="idea-gallery-carousel mb-2">
                        <div class="carousel-main-wrap">
                            <vue-slick-carousel 
                            ref="ideathumb" 
                            class="carousel carousel-idea-thumbnail slide-main product-image-slick" 
                            :key="'ideagallery-modal-thumb-images-'+realtimeKey" 
                            v-bind="ideagallerycarouselThumb"
                            :asNavFor="ideamaingalleryNavfor"
                            >
                                <div 
                                v-for="(idg, ideagallerykey) in getVariationEntries.ideagallerydata" 
                                :key="'ideagallery-modal-thumb-key-'+ideagallerykey"
                                class="aa-idea-thumbnail-slide">

                                    <div v-if="idg.image.split('.').pop() != 'html'">
                                        <div 
                                        v-if="idg.image.split('.').pop() != 'html'"
                                        class="img-fit-wrap p-2 bg-slidethumb img-slide-item"
                                        :style="{
                                            paddingTop: idg.top + '%!important'
                                        }"
                                        >
                                            <v-img 
                                            :img="idg.image"
                                            :width="86"
                                            :height="52"
                                            />
                                        </div>
                                        <span class="img-slide-caption">{{idg.text}}</span>
                                    </div>

                                    <div v-else>
                                        <div 
                                        class="img-fit-wrap p-2 bg-slidethumb img-slide-item"
                                        :style="{
                                            paddingTop: idg.top + '%!important'
                                        }"
                                        >
                                            <iframe 
                                            :src="idg.image" class="full-width full-height border-0" scrolling="no"></iframe>
                                        </div>
                                        <span class="img-slide-caption">{{idg.text}}</span>
                                    </div>
                                    
                                </div>


                                <template #nextArrow="arrowOption">
                                    <button class="idea-modal-arrow right"><span class="icon idea-arrow icon-arrow-right-double"></span></button>
                                </template>
                                <template #prevArrow="arrowOption">
                                    <button class="idea-modal-arrow left rotate-left"><span class="icon idea-arrow icon-arrow-right-double"></span></button>
                                </template>
                            </vue-slick-carousel >
                        </div>
                    </div>
                    <!-- end of thumbnails -->

                    <div class="idea-scrollbarwrap">
                        <div class="scrollbar-ideagallery-indicator" style="width: 0%;"></div>
                    </div>

                    <div v-if="getIdeaArchive" class="modal-button-centered text-center mt-5">
                        <a :href="getIdeaArchive" rel="nofollow" class="btn btn-primary" download>
                            <span class="icon icon-download-archive mr-1"></span>
                            <span class="text">Download All</span>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- end idea gallery modal -->
    

    <!-- modal share -->
    <v-modal 
    v-for="(idg, ideagallerykey) in getVariationEntries.ideagallerydata"
    :key="'ideagallery-shares-'+ideagallerykey" 
    ref="shareref"
    :dialogStyle="{ 'max-width': '300px' }"
    >
        <button
        data-type="fb"
        :data-url="idg.image"
        class="btn d-block full-width text-left mb-2 text-light aa_social_share" style="background: #4267B2;">
            <span class="icon icon-facebook-square mr-1"></span> Facebook
        </button>

        <button
        data-type="twitter"
        :data-url="idg.image"
        class="btn d-block full-width text-left mb-2 text-light aa_social_share" style="background: #1DA1F2;">
            <span class="icon icon-twitter-square mr-1"></span> Twitter
        </button>

        <button
        data-type="linkedin"
        :data-url="idg.image"
        class="btn d-block full-width text-left mb-2 text-light aa_social_share" style="background: #0077b5;">
            <span class="icon icon-instagram-square mr-1"></span> linkedin
        </button>
    </v-modal>
    <!-- end modal share -->
</div>

</div>
`;