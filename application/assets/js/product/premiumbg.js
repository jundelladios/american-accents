var premiumBackgrounds = /*html*/`
<div v-if="iteratePremiumBackgrounds && iteratePremiumBackgrounds.length">
    <div class="product-templates mt-4 mb-5">
        <h5 class="font-weight-bold text-uppercase d-block">premium backgrounds</h5>
        <div>
            <div class="carousel non-slide gallery-item-small">
                <div v-for="(pbg, pbgkey) in iteratePremiumBackgrounds" :key="'premiumbg-key' + pbgkey" class="img-slide-item">
                    <div class="img-fit-wrap p-2 bg-grey" 
                    @click.stop="premiumBgModal(false)"
                    >
                        <v-img 
                        loadersize="sm"
                        :alt="\`\${pbg.code} \${pbg.type}\`"
                        :img="pbg.image"
                        :width="61"
                        :height="61"
                        />
                    </div>
                </div>

                <div class="img-slide-item more align-items-center justify-content-center">
                    <a href="javascript:void(0)" @click.stop="premiumBgModal(false)" rel="nofollow" class="more-item-gallery">
                        <span class="icon d-block icon-image"></span>
                        <span class="d-block text-uppercase font-weight-bold font-10 text-xs">View All Backgrounds</span>
                    </a>
                </div>

            </div>
        </div>
    </div>



    <div class="modal zoom-in" data-backdrop="static" id="premiumbackground" data-modal-scroll tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" @click.stop="premiumBgModal(true)" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pt-3 pb-5">
                    <h5 class="mb-5 modal-title-product font-weight-bold text-uppercase text-center">premium backgrounds</h5>

                    <div class="mb-5" v-html="product.product_line_helpers.premiumBackgroundCompliance"></div>

                    <div data-accordion-autoclose>
                        <div v-for="(prmbg, prmbgindex) in product.productline.premiumbg" :key="\`premiumbg-item-\${prmbgindex}\`" data-accordion-module-item class="product-accordion mb-4">
                            <button :class="\`product-accordion-header \${prmbgindex==0?'first':''}\`" data-accordion-module data-element-scroll="#premiumbackground" >
                                <div class="accordion-header d-flex align-items-center">
                                    <span class="icon icon-image-v2 mr-4"></span>
                                    <span class="text text-uppercase font-extra-bold">
                                        <template v-if="prmbg.title">{{ prmbg.title }}</template>
                                        <template v-else>{{ prmbg.premiumbg.title }}</template>
                                    </span>
                                </div>
                                <div class="accordion-indicator" data-accordion-indicator>
                                    <div class="accicon-indicator">
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                            </button>

                            <div id="content" data-accordion-module-content>

                                <div class="plinecollectionwrap d-flex flex-wrap full-width justify-content-center">

                                    <div v-for="(colec, colindex) in prmbg.premiumbg.collections" :key="\`premiumbg-collection-modal-\${colindex}\`" class="plinecollection-item">
                                        <div class="item-wrap">
                                            <v-img :img="colec.image" 
                                            width="100"
                                            height="100"
                                            />
                                        </div>

                                        <div class="mt-2 text-center">
                                            <span class="d-block font-10" v-if="colec.code">{{colec.code}}</span>
                                            <span class="d-block font-10" v-if="colec.type">{{colec.type}}</span>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <a href="javascript:void(0)" class="back_to_top inmodal" style="display: block;" data-elem="#premiumbackground">
            <span class="icon-topscroll icon d-block"></span>
            <span class="top-text text-uppercase">back to top</span>
        </a>
    </div>

</div>
`;