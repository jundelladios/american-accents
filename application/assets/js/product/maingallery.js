var productCarouselImages = /*html */`
<div class="product-image-slide position-relative">

    <div v-if="product.product.banner_img" :class="\`product-banner-image-wrap \${product.product.banner_class}\`">
        <img :src="product.product.banner_img"/>
        <div class="product-banner-content" v-if="product.product.banner_content" v-html="product.product.banner_content"></div>
    </div>
    <template v-else>
        <div v-if="getVariationEntries.out_of_stock" class="product-banner-image-wrap out-of-stock-banner">
            <img :src="\`${inventoryJSVars.pluginURL}/application/assets/img/outofstock.png\`"/>
            <div class="product-banner-content">
                out of stock
            </div>
        </div>
    </template>

    <div class="carousel-main-wrap"
    @mouseover="pauseThumbnailsAutoplay"
    @mouseleave="playThumbnailAutoplay"
    >
        
        <vue-slick-carousel 
        ref="maingallery" 
        class="carousel carousel-main product-image-slick" 
        :key="'product-main-gallery-imagedata-'+realtimeKey" 
        v-bind="maingallerycarousel"
        :draggable="getVariationEntries.imagedata.length > 1 ? true : false"
        @afterChange="zoomElevateRefresh"
        >
            <template v-if="getVariationEntries.imagedata.length">
                <div :class="'img-slide-item img-zoom-js main-gallery-images-' + dfimgindex" 
                v-for="(dfimg, dfimgindex) in getVariationEntries.imagedata" 
                :key="'dfimg-key-'+dfimgindex" 
                @click.stop="openMainGalleryPopup(dfimgindex)">
                    <div 
                    v-if="dfimg.image.split('.').pop() != 'html'"
                    class="img-fit-wrap"
                    :style="{
                        paddingTop: dfimg.top + '%'
                    }"
                    >
                        
                        <v-img 
                        :data-zoom-image="dfimg.image"
                        :img="dfimg.image"
                        />
                    </div>

                    <div
                    v-else
                    :style="{
                        paddingTop: dfimg.top + '%'
                    }"
                    >
                        <iframe :src="dfimg.image" class="full-width full-height border-0" scrolling="no"></iframe>
                    </div>

                    <span class="img-slide-caption mt-2">{{dfimg.title}}</span>
                </div>
            </template>

            <template v-else>
                <div :class="'img-slide-item img-zoom-js'" >
                    <div class="img-fit-wrap"
                    :style="{
                        paddingTop: product.productcomboimage.top + '%'
                    }"
                    >
                        <v-img 
                        :data-zoom-image="product.productcomboimage.image"
                        :img="product.productcomboimage.image" :alt="\`\${product.product_method_combination_name}\`"
                        />
                    </div>
                    <span class="img-slide-caption mt-2">{{dfimg.title}}</span>
                </div>
            </template>
        </vue-slick-carousel>
    </div>

    <div v-if="getVariationEntries.imagedata.length > 1" class="carousel-thumbnail-wrap mt-3 colpmethodthumbnail">
        <vue-slick-carousel 
        ref="maingallerythumb" 
        class="carousel carousel-thumbnail product-image-slick" 
        :key="'product-thumb-gallery-imagedata-'+realtimeKey" 
        v-bind="maingallerythumbcarousel"
        :draggable="false"
        >
            <div class="img-slide-item mb-0 bg-transparent" v-for="(dfimg, dfimgindex) in getVariationEntries.imagedata" :key="'dfimg-key-'+dfimgindex">
            
                <div v-if="dfimg.image.split('.').pop() != 'html'">
                    <div 
                    class="img-fit-wrap p-2 position-relative bg-slidethumb"
                    :style="{
                        paddingTop: dfimg.top + '%'
                    }"
                    >
                        <v-img 
                        :img="dfimg.image" 
                        />
                    </div>
                    <span class="img-slide-caption mt-1">{{dfimg.title}}</span>
                </div>

                
                <div v-else>
                    <div
                    class="img-fit-wrap p-2 position-relative bg-transparent"
                    :style="{
                        paddingTop: dfimg.top + '%'
                    }"
                    >

                        <iframe :src="dfimg.image" class="full-width full-height border-0" scrolling="no"></iframe>
                    </div>
                    <span class="img-slide-caption mt-1">{{dfimg.title}}</span>
                </div>

            </div>
        </vue-slick-carousel>
    </div>

    
</div>
`;



var maingallerypopup = /*html */`
<div>



<div v-if="getVariationEntries && getVariationEntries.imagedata.length">

    <!-- image gallery modal -->
    <div class="modal zoom-in modalgallerypopup" v-if="!variantLoading" data-backdrop="static" id="maingallerymodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pt-3 pb-5">
                    <div class="idea-gallery-info">
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
                            ref="galleryMainCarouselPopup" 
                            class="carousel carousel-idea-main popupgallery slide-main ideagallery-modal-main" 
                            v-bind="popupMaingallerycarousel"
                            :asNavFor="popupMainGalleryThumb"
                            @beforeChange="popupGalleryprogressBarUpdate"
                            :key="'popupgal-modal-main-images-'+realtimeKey" 
                            >
                                <div class="img-slide-item" 
                                v-for="(img, imgi) in getVariationEntries.imagedata" 
                                :key="'popupgal-modal-main-key-'+imgi"
                                >
                                    <div 
                                    v-if="img.image.split('.').pop() != 'html'"
                                    class="img-fit-wrap"
                                    :style="{
                                        paddingTop: img.top + '%!important'
                                    }"
                                    >
                                        <v-img 
                                        :img="img.image"
                                        :width="373"
                                        :height="411"
                                        :akt="img.title"
                                        />

                                        <iframe 
                                        v-else
                                        :src="img.image" class="full-width full-height border-0" scrolling="no"></iframe>
                                    </div>


                                    <div 
                                    v-else
                                    class="img-fit-wrap p-0"
                                    :style="{
                                        paddingTop: img.top + '%!important'
                                    }"
                                    >
                                        <iframe 
                                        :src="img.image" class="full-width full-height border-0" scrolling="no"></iframe>
                                    </div>


                                    <span class="img-slide-caption mt-2 mb-2">{{img.title}}</span>


                                    <div class="button-actions mt-3">
                                        <a href="#" :data-url="img.image" data-type="email" class="btn-action button-light aa_social_share"><span class="icon mr-1 icon-email"></span> email</a>
                                        <a href="#" @click.prevent="() => printJS({
                                            printable: img.image,
                                            documentTitle: img.title,
                                            type: 'image',
                                            imageStyle: 'display:block;margin:20px auto;max-height:100%;max-width:100%;',
                                            showModal: true
                                        })" class="btn-action button-light frontend-desktop-only"><span class="icon mr-1 icon-icon-print"></span> print</a>
                                        <a :href="img.image" class="btn-action button-light" download target="_blank" rel="nofollow" class="btn-action button-light"><span class="icon mr-1 icon-download-single"></span> download</a>
                                        <a href="#" @click.prevent="$refs.sharerefMainGallery[imgi].openModal()" class="btn-action button-light"><span class="icon mr-1 icon-share"></span> share</a>    
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
                            ref="galleryMainCarouselPopupThumb" 
                            class="carousel carousel-idea-thumbnail slide-main product-image-slick" 
                            :key="'popupgal-modal-thumb-images-'+realtimeKey" 
                            v-bind="popupMaincarouselThumb"
                            :asNavFor="popupMainGalleryNav"
                            >
                                <div 
                                v-for="(img, imgi) in getVariationEntries.imagedata" 
                                :key="'ideagallery-modal-thumb-key-'+imgi"
                                class="aa-idea-thumbnail-slide">
                                        
                                    <div v-if="img.image.split('.').pop() != 'html'">
                                        <div 
                                        class="img-fit-wrap p-2 bg-slidethumb img-slide-item"
                                        :style="{
                                            paddingTop: img.top + '%!important'
                                        }"
                                        >
                                            <v-img 
                                            :img="img.image"
                                            :width="86"
                                            :height="52"
                                            :alt="img.title"
                                            />
                                        </div>
                                        <span class="img-slide-caption">{{img.title}}</span>
                                    </div>
                                    <div v-else>
                                        <div 
                                        class="img-fit-wrap p-2 bg-transparent img-slide-item"
                                        :style="{
                                            paddingTop: img.top + '%!important'
                                        }"
                                        >
                                            <iframe 
                                            :src="img.image" class="full-width full-height border-0" scrolling="no"></iframe>
                                        </div>
                                        <span class="img-slide-caption">{{img.title}}</span>
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
                        <div class="scrollbar-popgallery-indicator" style="width: 0%;"></div>
                    </div>

                    <div v-if="getImagesArchive" class="modal-button-centered text-center mt-5">
                        <a :href="getImagesArchive" rel="nofollow" class="btn btn-primary" download>
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
    v-for="(img, imgi) in getVariationEntries.imagedata" 
    :key="'popup-shares-'+imgi" 
    ref="sharerefMainGallery"
    :dialogStyle="{ 'max-width': '300px' }"
    >
        <button
        data-type="fb"
        :data-url="img.image"
        class="btn d-block full-width text-left mb-2 text-light aa_social_share" style="background: #4267B2;">
            <span class="icon icon-facebook-square mr-1"></span> Facebook
        </button>

        <button
        data-type="twitter"
        :data-url="img.image"
        class="btn d-block full-width text-left mb-2 text-light aa_social_share" style="background: #1DA1F2;">
            <span class="icon icon-twitter-square mr-1"></span> Twitter
        </button>

        <button
        data-type="linkedin"
        :data-url="img.image"
        class="btn d-block full-width text-left mb-2 text-light aa_social_share" style="background: #0077b5;">
            <span class="icon icon-instagram-square mr-1"></span> linkedin
        </button>
    </v-modal>
    <!-- end modal share -->
    

</div>

</div>
`;