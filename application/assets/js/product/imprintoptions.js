var imprintOptions = /*html */`
<div v-if="product.productline.plinecolors.length || product.productline.imprinttypes.length" data-accordion-module-item class="product-accordion open mb-4">
    <button class="product-accordion-header" data-accordion-module>
        <div class="accordion-header d-flex align-items-center">
            <span class="icon icon-swatch-book"></span>
            <span class="text text-uppercase font-extra-bold">imprint options</span>
        </div>
        <div class="accordion-indicator" data-accordion-indicator>
            <div class="accicon-indicator">
                <span></span>
                <span></span>
            </div>
        </div>
    </button>
    <div id="content" class="pad" data-accordion-module-content>
        <div 
        v-for="(plinecol, plinecoli) in product.productline.plinecolors"
        :key="\`pline-color-collection-\${plinecoli}\`"
        class="color-collection-group">
            <h5 class="color-colleciton-title font-weight-bold text-uppercase mb-4">
                <template v-if="plinecol.title">{{plinecol.title}}</template>
                <template v-else>{{plinecol.colorcollections.title}}</template>
            </h5>
            
            <div class="color-items">
                <div 
                v-for="(pcolor, pcolori) in plinecol.colorcollections.collections"
                :key="\`pcolor-index-\${pcolori}\`"
                v-tooltip:top="pcolor.name"
                class="color-item">

                    <div v-if="pcolor.isImage && pcolor.image" 
                    class="color-hex lazyload bg-cover"
                    v-img:data-bgset="pcolor.image"
                    :style="\`background-image:url(\${pcolor.image});\`"
                    ></div>

                    <div 
                    class="color-hex" 
                    :style="\`background: \${pcolor.hex};\`"
                    v-else>
                        <span class="color-pantone" v-autocolor="pcolor.hex">{{pcolor.pantone}}</span>
                    </div>

                    <span class="color-name">{{ pcolor.name }}</span>
                </div>
            </div>
        </div>

        <div v-if="product.productline.imprinttypes.length" class="color-collection-group">
            <h5 class="color-colleciton-title font-weight-bold text-uppercase mb-4">
                <span class="icon icon-imprint-method mr-2"></span>imprint methods
            </h5>

            <div class="imprint-method-lists">
                <div 
                v-for="(imprnt, imprnti) in product.productline.imprinttypes"
                :key="\`imprint-key-spec-index-\${imprnti}\`"
                class="imprint-item">
                    <div class="imprint-bg lazyload" 
                    v-if="imprnt.image"
                    v-img:data-bgset="imprnt.image"
                    :style="\`background-image:url(\${imprnt.image});\`"
                    ></div>
                    <span>{{ imprnt.imprinttype.title }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
`;