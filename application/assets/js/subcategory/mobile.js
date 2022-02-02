// ================================= MOBILE TEMPLATE ===========================//
var mobileTemplate = /*html */`
<template v-else>
    <div class="frontend-mobile-only">
        <transition name="plinetoggle" enter-active-class="animate slideInUp animate-half1" leave-active-class="animate fadeOutDown pline-absolute animate-half1">
            <div v-if="selected==null" class="container" key="pline1">
                <div v-if="plinejson" class="row justify-content-center printmethodgrids v3 plistsmethod">
                    <div v-for="(pline, index) in plinejson" :key="'plinejsonkey-mobile-' + index" class="col-lg-4 colpm">
                        <a href="javascript:void(0)" @click.stop="setSelected(pline.hid)">
                            <div class="col-print-method">

                                <div class="timg mobile">
                                    <div class="timgwrap">
                                        <div class="img-fit-wrap">
                                            <v-img :img="pline.image" :alt="\`\${pline.plinecombination}\`" :width="180" :height="250" />
                                        </div>
                                    </div>
                                </div>

                                <div class="content-handle">
                                    <div class="pmdetails">
                                        <h5 class="t1">{{pline.method_name}}</h5>
                                        <h4 class="t2" :style="'color: ' + pline.method_hex + ';'">{{pline.method_name2}}</h4>
                                        <span class="price d-block">{{ pline.pricerange.formatted_min }} - {{ pline.pricerange.formatted_max }}</span>
                                        <span v-if="pline.price_tagline" class="price d-block">{{ pline.price_tagline }}</span>
                                    </div>

                                    <div v-if="toJson(pline.keyfeatures)" class="tfeaturewrap">
                                        <ul class="tfeature">
                                            <template v-for="(feature, index) in toJson(pline.keyfeatures)">
                                                <li v-if="feature.text">
                                                    <span v-if="feature.image" :class="'icon ' + feature.image + ''"></span>
                                                    <span>{{ feature.text }}</span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>

                                <div class="overlaybg"></div>

                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div v-else class="productLinesModule" key="pline2"> 
            
                <div class="pline-banner-wrap mb-5" v-if="getBanners.length">
                    <vue-slick-carousel 
                    ref="bannerslick"
                    :arrows="false"
                    :dots="false"
                    :draggable="false"
                    :swipe="false"
                    :infinite="false"
                    :slidesToShow="1"
                    @init="initCarousel">
                        <template v-for="(pline,index) in getBanners">
                            <v-img :img="pline.banner_img" :fallback="getImageFallback.banner" :alt="\`\${pline.plinecombination} Banner\`" :nofallback="true" class="pline-slick-banner-item full-width mobile" :width="375" :height="124" :data-pline-id="pline.hid" />
                        </template>
                    </vue-slick-carousel>
                </div>

                <transition-group class="container overflow-hidden" tag="div" name="transition-arrange-mobile">
                    <!-- row -->
                    <div v-for="(pline, index) in getArrangedData" :class="'plistsmethod v3 ' + pline.opened_accordion_class " :key="'reactive-index-mobile-' + pline.reactiveIndex">
                        <!-- col start -->

                        <div class="tag-labeled d-flex align-items-center" @click.stop="setSelected(pline.hid)">
                            <div>
                                <span class="d-block t-t1">{{ pline.method_name }}</span>
                                <span class="d-block t-t2" :style="'color: ' + pline.method_hex + ';'">{{ pline.method_name2 }}</span>
                                <p class="mb-0 p-price">{{ pline.pricerange.formatted_min }} - {{ pline.pricerange.formatted_max }} per piece</p>
                            </div>
                        </div>

                        <div class="main-pline-content">

                        <div class="col-blue colwrap blue">
                            <div class="imagebehind mobile">
                                <div class="imgwrapbehind">
                                    <div class="img-fit-wrap">
                                        <v-img :img="pline.image" :alt="\`\${pline.plinecombination}\`" :width="180" :height="250" />
                                    </div>
                                </div>
                            </div>

                            <div class="contentwrap printmethod">
                                <div class="pmethod">
                                    <h4 class="t1">{{pline.method_name}}</h4>
                                    <h5 class="t2" :style="'color: ' + pline.method_hex + ';'">{{pline.method_name2}}</h5>
                                </div>

                                <div class="pmethoddesc pmethodrow">
                                    <strong class="d-block smtitle">overview:</strong>
                                    <div v-html="pline.method_desc"></div>
                                </div>

                                <div class="prange pmethodrow">
                                    <strong class="d-block smtitle">price range:</strong>
                                    <p>{{ pline.pricerange.formatted_min }} - {{ pline.pricerange.formatted_max }} per piece</p>
                                </div>

                                <div class="pfeature pmethodrow">
                                    <strong class="d-block smtitle">key features:</strong>
                                    <ul v-if="toJson(pline.keyfeatures)" class="tfeature">
                                        <template v-for="(fs, index) in toJson(pline.keyfeatures)">
                                            <li v-if="fs.text">
                                                <span v-if="fs.image" :class="'icon ' + fs.image + ''"></span>
                                                <span>{{ fs.text }}</span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>

                                <div class="return-print-methods">
                                    <a href="javascript: void(0)" @click.stop="setSelected(null)"><span class="arrow-icon">&#x2199;</span> <i class="text-uppercase">return to print methods</i></a>
                                </div>

                            </div>
                        </div>
                        <!-- endcol -->

                        <!-- col start -->
                        <div class="col-dark colwrap dark">
                            <div class="contentwrap">
                            <div class="row fsizesfilter">
                                <div class="col-md-12">
                                    <transition name="loadedsizes" enter-active-class="animate fadeIn animate-half1">
                                        <div v-if="!sizes.loading">
                                            <strong class="aa-size-filter text-uppercase">Available in:</strong>
                                            <label v-for="(size, index) in sizes.data" :key="'size-filter-' + index" class="checkbox-btn-wrap">
                                                <span>{{size.product_size_details}}</span>
                                                <input type="checkbox" :value="size.product_size_details" @change="size.selected=!size.selected">
                                                <span class="fill"></span>
                                            </label>
                                        </div>
                                    </transition>
                                </div>

                                <div class="col-md-12">
                                        <div class="products-wrap infiniteScroll wrapsubcatmethod f-scroll-custom" :data-pline-id="pline.hid">
                                            <div class="row fsubcatwithmethod">
                                                <!-- products starts -->
                                                <div v-for="(product, index) in getProductsIterate" :key="'product-mobile-' + product.index" class="col-md-4 col-sm-6 col-6 productcolv2" :data-product-id="product.hid">
                                                    <product-component :product="product" />
                                                </div>

                                                <p v-if="!products.loading && !getProductsIterate.length" class="col-md-12 notfound d-block text-center mt-5 mb-5 notfound">There is no product available.</p>
                                                <!-- end products starts -->
                                                <!-- loading starts -->
                                                <div v-if="products.loading" class="ajax-loader append-loader">
                                                    <div class="loaderWrap">
                                                        <div>
                                                            <div class="loader"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end loading -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>

                            </div>
                            </div>
                        </div>
                        </div>
                        <!-- endcol -->
                    </div>
                    <!-- end row -->
                </transition-group>
            </div>
        </transition>
    </div>
</template>
`;