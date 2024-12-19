// ============ DESKTOP TEMPLATE =================

var desktopTemplate = /*html */`
<template v-if="!isMobile">
    <div class="frontend-desktop-only">
        <transition name="plinetoggle" enter-active-class="animate slideInUp animate-half1" leave-active-class="animate fadeOutDown pline-absolute animate-half1">
            <div v-if="selected==null" class="container" key="pline1">
                <div v-if="plinejson" class="row justify-content-center printmethodgrids plistsmethod">
                    <div v-for="(pline, index) in plinejson" :key="'plinejsonkey-' + pline.hid" :class="\`col-md-4 colpm \${pline.method_name}\-\${pline.method_name2}\`">
                        <a :href="\`\${inventoryJSVars.baseURL}product/\${pline.cat_slug}/\${pline.sub_slug}/print-method/\${pline.method_slug}\`" @click.prevent="selected=pline.hid">
                            <div class="col-print-method"> 

                                <div class="content-handle">
                                    <div class="pmdetails">
                                        <h5 class="t1">{{pline.method_name}}</h5>
                                        <h4 class="t2" :style="'color: ' + pline.method_hex + ';'">{{pline.method_name2}}</h4>
                                        <span class="price d-block">{{ pline.pricerange.formatted_min }} - {{ pline.pricerange.formatted_max }}</span>
                                        <span v-if="pline.price_tagline" class="price d-block">{{ pline.price_tagline }}</span>
                                    </div>

                                    <div class="timg position-relative">
                                        <div class="img-fit-wrap">
                                            <v-img :img="pline.image" :alt="\`\${pline.plinecombination}\`" :width="400" :height="400" />
                                        </div>

                                        <div class="pmethoddesc pmethodrow pmdetails overview-content">
                                            <strong class="d-block smtitle">overview:</strong>
                                            <div v-html="pline.method_desc"></div>
                                        </div>
                                    </div>

                                    <div v-if="toJson(pline.keyfeatures)" class="tfeaturewrap">
                                        <ul class="tfeature">
                                            <template v-for="(feature, index) in toJson(pline.keyfeatures)">
                                                <li v-if="feature.text">
                                                    <span v-if="feature.image" :class="'icon ' + feature.image"></span>
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
                            <v-img :nofallback="true" :fallback="getImageFallback.banner" :img="pline.banner_img" :alt="\`\${pline.plinecombination} Banner\`" :width="1903" :height="630" class="pline-slick-banner-item full-width desktop" :data-pline-id="pline.hid" />
                        </template>
                    </vue-slick-carousel>
                </div>

                <transition-group class="container custom-container" name="transition-arrange">
                    <!-- row -->
                    <div v-for="(pline, index) in getArrangedData" :key="'pline-reactive-desktop-' + pline.reactiveIndex"
                    :class="\`pmethodselected \${pline.method_name}\-\${pline.method_name2}\ plistsmethod v2 \${pline.opened_accordion_class}\`"
                    >
                        <!-- col start -->
                        <div class="col-blue colwrap blue">
                            <div class="imagebehind">
                                <div class="imgwrapbehind">
                                    <div class="img-fit-wrap">
                                        <v-img :img="pline.image" :alt="\`\${pline.plinecombination}\`" :width="400" :height="400" />
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
                                    <a :href="\`\${inventoryJSVars.baseURL}product/\${pline.cat_slug}/\${pline.sub_slug}\`" @click.prevent="setSelected(null)"><span class="arrow-icon">&#x2199;</span> <i class="text-uppercase">return to print methods</i></a>
                                </div>

                            </div>
                        </div>
                        <!-- endcol -->

                        <!-- col start -->
                        <div class="col-dark colwrap dark">
                            <div class="contentwrap v2">
                            <div class="row fsizesfilter">
                                <div class="col-md-12">
                                    <transition name="loadedsizes" enter-active-class="animate fadeIn animate-half1">
                                        <div v-if="!sizes.loading && (sizes.data || thickness)">
                                            <strong class="aa-size-filter text-uppercase">Available in:</strong>
                                            <label v-for="(size, index) in sizes.data" :key="'size-filter-' + index + ''" class="checkbox-btn-wrap">
                                                <span>{{size.product_size_details}}</span>
                                                <input type="checkbox" :value="size.product_size_details" @change="size.selected=!size.selected">
                                                <span class="fill"></span>
                                            </label>
                                            <label v-for="(thc, index) in thickness" :key="'size-filter-' + index + ''" class="checkbox-btn-wrap">
                                                <span>{{thc.product_tickness_details}}</span>
                                                <input type="checkbox" :value="thc.product_tickness_details" @change="thc.selected=!thc.selected">
                                                <span class="fill"></span>
                                            </label>
                                        </div>
                                    </transition>
                                </div>

                                <div class="col-md-12">
                                    <div class="products-wrap infiniteScroll wrapsubcatmethod f-scroll-custom" :data-pline-id="pline.hid">
                                        <div class="row fsubcatwithmethod">
                                            <!-- products starts -->
                                            <div v-for="(product, index) in getProductsIterate" :key="'product-desktop-' + product.hid + ''" class="col-xl-4 col-lg-6 productcolv2" :data-product-id="product.hid">
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
                        <!-- endcol -->

                        <!-- col start -->
                        <a :href="\`\${inventoryJSVars.baseURL}product/\${pline.cat_slug}/\${pline.sub_slug}/print-method/\${pline.method_slug}\`" @click.prevent="selected=pline.hid" class="col-tag">
                            <div class="col-tag-content">
                                <span class="name1">{{pline.method_name}}</span>
                                <span class="name2 text-uppercase" :style="'color: ' + pline.method_hex + ';'">{{pline.method_name2}}</span>
                            </div>
                        </a>
                        <!-- endcol -->
                        
                    </div>
                    <!-- end row -->
                </transition-group>
            </div>
        </transition>
    </div>
</template>
`;