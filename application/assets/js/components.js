Vue.component('product-component', {
    template: /*html */`
        <div :data-product-id="product.hid">
            <a :href="getURL" data-product-link>
                <div class="fprodimagewrap d-flex justify-content-center align-items-end">
                    
                    <template v-if="product.variations && product.variations.variations.length">

                        <!-- for color + stockshape -->
                        <vue-slick-carousel
                        @init="setURL(0)"
                        class="product-image-slick"
                        v-if="!loading && product.variations.key=='color-stockshape'" 
                        :ref="'productimageslick-'+product.hid"
                        :arrows="false"
                        :dots="false"
                        :draggable="false"
                        :swipe="false"
                        :infinite="false"
                        :dontAnimate="false"
                        :slidesToShow="1"
                        @beforeChange="(oldSlideIndex, slideIndex) => updateProductSlide(slideIndex)">
                            <div v-for="(variant, variantindex) in product.variations.variations.slice(0, colordisplayLimit)" 
                            :data-img-index="variantindex"
                            :key="'variant-'+variantindex" 
                            class="img-fit-wrap"
                            :style="{
                                paddingTop: variant.imagedata[0].top + '%'
                            }"
                            >
                                <v-img 
                                :img="variant.imagedata[0].image" 
                                :alt="\`\${product.product_method_combination_name}\${variant.thecolor ? ' ' + variant.thecolor.colorname : ''}\${variant.theshape ? ' ' + variant.theshape.code : ''}\`" 
                                width="auto" 
                                height="auto" />
                            </div>
                        </vue-slick-carousel>
                        
                        <!-- for color | non-available color -->
                        <vue-slick-carousel
                        @init="setURL(0)"
                        class="product-image-slick"
                        v-if="!loading && (product.variations.key=='color' || product.variations.key=='na-color')" 
                        :ref="'productimageslick-'+product.hid"
                        :arrows="false"
                        :dots="false"
                        :draggable="false"
                        :swipe="false"
                        :infinite="false"
                        :dontAnimate="false"
                        :slidesToShow="1"
                        @beforeChange="(oldSlideIndex, slideIndex) => updateProductSlide(slideIndex)">
                            <div v-for="(variant, variantindex) in product.variations.variations.slice(0, colordisplayLimit)" 
                            :data-img-index="variantindex"
                            :key="'variant-'+variantindex" 
                            class="img-fit-wrap"
                            :style="{
                                paddingTop: variant.imagedata[0].top + '%'
                            }"
                            >
                                <v-img 
                                :img="variant.imagedata[0].image" 
                                :alt="\`\${product.product_method_combination_name} \${variant.colorname}\`" 
                                width="auto" 
                                height="auto" />
                            </div>
                        </vue-slick-carousel>

                        <!-- for stockshape -->
                        <vue-slick-carousel
                        class="product-image-slick"
                        v-if="!loading && product.variations.key=='stockshape'" 
                        :ref="'productimageslick-'+product.hid"
                        :arrows="false"
                        :dots="false"
                        :draggable="false"
                        :swipe="false"
                        :infinite="false"
                        :dontAnimate="false"
                        :slidesToShow="1"
                        @beforeChange="(oldSlideIndex, slideIndex) => updateProductSlide(slideIndex)">
                            <div v-for="(variant, variantindex) in product.variations.variations.slice(0, colordisplayLimit)" 
                            :data-img-index="variantindex"
                            :key="'variant-'+variantindex" 
                            class="img-fit-wrap"
                            :style="{
                                paddingTop: variant.imagedata[0].top + '%'
                            }"
                            >
                                <v-img 
                                :img="variant.imagedata[0].image" 
                                :alt="\`\${product.product_method_combination_name} \${variant.code}\`" 
                                width="auto" 
                                height="auto" />
                            </div>
                        </vue-slick-carousel>

                    </template>
                    <template v-else>
                        <!-- for default -->
                        <div 
                        class="img-fit-wrap"
                        :style="{
                            paddingTop: product.productcomboimage.top + '%'
                        }"
                        >
                            <v-img :img="product.productcomboimage.image" :alt="\`\${product.product_method_combination_name}\`" width="auto" height="auto" />
                        </div>
                    </template>

                    <!-- detail viewer -->
                    <div class="details-viewer">
                        <span class="icon icon-magnifying-glass"></span>
                        <span class="detail-text">View Details</span>
                    </div>
                </div>
            </a>
            
            <!-- for color -->
            <div v-if="product.variations && product.variations.variations.length > 1 && product.variations.key == 'color'"  class="product-field-colors aa-color-filter">
                <label v-for="(circlecolor, cindex) in product.variations.variations.slice(0, colordisplayLimit)" 
                :key="'circlecolor-index-' + cindex" 
                class="round-checkbox color-filter"
                v-tooltip:top="circlecolor.colorname"
                >
                    <input type="radio" 
                    class="unchecked"
                    @click.stop="(e) => updateSlickSlideProduct(cindex)" :value="circlecolor.slug">
                    
                    <span v-if="!circlecolor.iscolorimage" class="checkmark" :style="'background: ' + circlecolor.colorhex + ';'"></span>
                    <template v-else>
                        <span v-if="circlecolor.colorimageurl" 
                        class="checkmark lazyload bg-cover"
                        v-img:data-bgset="circlecolor.colorimageurl"
                        :fallback="getImageFallback.normal"
                        ></span>
                        <span v-else
                        class="checkmark lazyload bg-cover" 
                        :data-bgset="getImageFallback.normal"
                        ></span>
                    </template>
                    
                    <span v-if="slug == circlecolor.slug" class="underline active"></span>
                    <span v-else class="underline"></span>
                </label>

                <a v-if="product.variations.variations.length > colordisplayLimit" :href="product.productURL" rel="nofollow" class="morelinkcolor text-dark">+more</a>
            </div>

            <!-- for shape + color -->
            <div v-if="product.variations && product.variations.variations.length > 1 && product.variations.key == 'color-stockshape'"  class="product-field-colors aa-color-filter">
                
                <template v-for="(circlecolor, cindex) in product.variations.variations.slice(0, colordisplayLimit)">
                <label v-if="circlecolor.thecolor"
                class="round-checkbox color-filter"
                v-tooltip:top="circlecolor.thecolor && circlecolor.thecolor.colorname"
                >
                    <input type="radio" 
                    class="unchecked"
                    @click.stop="(e) => updateSlickSlideProduct(cindex)" :value="circlecolor.slug">
                    
                    <span v-if="!circlecolor.thecolor.iscolorimage" class="checkmark" :style="'background: ' + circlecolor.thecolor.colorhex + ';'"></span>
                    <template v-else>
                        <span v-if="circlecolor.thecolor.colorimageurl" 
                        class="checkmark lazyload bg-cover"
                        v-img:data-bgset="circlecolor.thecolor.colorimageurl"
                        :fallback="getImageFallback.normal"
                        ></span>
                        <span v-else
                        class="checkmark lazyload bg-cover" 
                        :data-bgset="getImageFallback.normal"
                        ></span>
                    </template>
                    

                    <span v-if="slug == circlecolor.slug" class="underline active"></span>
                    <span v-else class="underline"></span>
                </label>
                </template>

                <a v-if="product.variations.variations.length > colordisplayLimit" :href="product.productURL" rel="nofollow" class="morelinkcolor text-dark">+more</a>
            </div>
            
            
            <a :href="getURL" data-product-link>
                <div class="productdesc">
                    <span class="pname">{{ product.product_method_combination_name }}</span>
                    <span class="pdesc">
                        {{ product.product_description }}
                    </span>
                    
                    <span class="pprice">{{ product.priceFormatted }}</span>
                </div>
            </a>

        </div>
    `,
    components: {
        VueSlickCarousel: window['vue-slick-carousel']
    },
    data() {
        return {
            url: null,
            slug: null
        }
    },
    props: {
        loading: {
            type: Boolean
        },
        product: {
            type: Object
        }
    },
    computed: {
        getURL() {
            return this.url ? this.url : this.product.productURL;
        },
        colordisplayLimit() {
            return 5;
        },
        getImageFallback() {
            return {
                normal: `${inventoryJSVars.pluginURL}application/assets/img/square-placeholder.png`,
                banner: `${inventoryJSVars.pluginURL}application/assets/img/banner-placeholder.png`,
            };
        }
    },
    methods: {
        updateProductSlide(index) {
            this.setURL(index);
        },
        updateSlickSlideProduct(index) {
            const e = this;
            this.$nextTick(() => {
                e.$refs[`productimageslick-${this.product.hid}`].goTo(index);
            });
        },
        setURL(index) {
            if( !this.product.variations ) { return false; }
            this.slug = this.product.variations.variations[index].slug;
            this.url = `${this.product.productURL}${this.product.variations.router_slug}/${this.slug}`
        }
    }
});