var productbreadcrumb = /*html */`
<div class="row print-hide">
    <div class="mt-5 col-md-12">
        <ul class="f-breadcrumb list-unstyled d-flex m-0">
            <li><a :href="jsvars.baseURL"><i class="icon-home icon"></i>Home</a></li>
            <li><a :href="jsvars.baseURL + '/product/' + product.cat_slug">{{ product.cat_name }}</a></li>
            <li><a :href="jsvars.baseURL + '/product/' + product.cat_slug + '/' + product.sub_slug">
                <span v-if="product.sub_name_alt" class="linkspan" v-html="product.sub_name_alt"></span>
                <span v-else>{{ product.sub_name }}</span>
            </a></li>
            <li><span class="text-uppercase">{{ product.product_method_combination_name }}</span></li>
        </ul>
    </div>
    <div class="col-md-12">
        <hr>
    </div>
</div>
`;


var productSubcategory = /*html */`
<div class="row">
    <div class="col-md-12">
        <div class="print-only">
            ${printmethodcompare}
        </div>

        <h3 class="categoryname  text-uppercase font-extra-bold">{{ product.sub_name }}</h3>
    </div>
</div>
`;



var productName = /*html */`
<div class="mb-4 frontend-mobile-only">
    <h1 
    class="mb-0 productname text-uppercase font-extra-bold" 
    :style="\`color: \${product.productline.printmethod.method_hex};\`">
        {{ product.product_method_combination_name }}
    </h1>
    <h4 class="productdesc">{{ product.product.product_description }}</h4>
</div>
`;


var leftButtons = /*html */`
<div class="right-buttons print-hide">
    <button v-for="(pbtn, pbtnindex) in productbuttons" v-if="!pbtn.hide" @click="pbtn.callback" :key="'pbtn-key' + pbtnindex" class="button-icon mt-3" :disabled="pbtn.soon">
        <span :class="'icon ' + pbtn.icon"></span>
        <span class="text text-uppercase">{{ pbtn.text }}</span>
        <div v-if="pbtn.soon" class="coming-soon">
            <svg xmlns="http://www.w3.org/2000/svg" width="83.667" height="62.572" viewBox="0 0 83.667 62.572"><g transform="translate(-1460.123 -268.725)"><path d="M1543.273,281.985l-9.979,30.28a15.852,15.852,0,0,1-15.057,10.891h-9.26l-14.252,7.97a1.27,1.27,0,0,1-1.637-1.869l4.566-6.1h-14.626a15.854,15.854,0,0,1-15.276-11.611l-7.256-26.128a10.1,10.1,0,0,1,9.112-12.782l63.457-3.89A10.1,10.1,0,0,1,1543.273,281.985Z" fill="#88a4cd"/><path d="M1483.979,289.836l-.421,2.028a5.585,5.585,0,0,1-.7,1.606,4.385,4.385,0,0,1-1.1,1.085,2.961,2.961,0,0,1-1.767.5,2.214,2.214,0,0,1-1.586-.5,1.912,1.912,0,0,1-.6-1.085,3.3,3.3,0,0,1-.019-1.606l1.846-8.693a4.889,4.889,0,0,1,.683-1.586,4.33,4.33,0,0,1,1.084-1.1,3.232,3.232,0,0,1,1.787-.5,2.274,2.274,0,0,1,1.566.5,1.983,1.983,0,0,1,.622,1.1,3.441,3.441,0,0,1,.041,1.586l-.442,2.028h-2.028l.442-2.028a1.076,1.076,0,0,0-.141-.924.578.578,0,0,0-.461-.16.95.95,0,0,0-.783.542,3.8,3.8,0,0,0-.2.542l-1.847,8.693a1.239,1.239,0,0,0,.14.924.458.458,0,0,0,.382.16.91.91,0,0,0,.863-.542,2.6,2.6,0,0,0,.2-.542l.421-2.028Z" fill="#fff"/><path d="M1490.5,291.864a5.557,5.557,0,0,1-.7,1.606,4.375,4.375,0,0,1-1.1,1.085,2.959,2.959,0,0,1-1.766.5,2.214,2.214,0,0,1-1.586-.5,1.9,1.9,0,0,1-.6-1.085,3.314,3.314,0,0,1-.02-1.606l1.847-8.693a4.891,4.891,0,0,1,.683-1.586,4.334,4.334,0,0,1,1.084-1.1,3.232,3.232,0,0,1,1.787-.5,2.274,2.274,0,0,1,1.566.5,1.984,1.984,0,0,1,.622,1.1,3.427,3.427,0,0,1,.04,1.586Zm-.321-8.693a1.236,1.236,0,0,0-.1-.924.5.5,0,0,0-.4-.16.9.9,0,0,0-.763.542,2.142,2.142,0,0,0-.18.542l-1.848,8.693a1.081,1.081,0,0,0,.1.924.456.456,0,0,0,.381.16.829.829,0,0,0,.763-.542,2.544,2.544,0,0,0,.2-.542Z" fill="#fff"/><path d="M1494.919,294.9l.583-9.276.04-.622h-.121l-2.088,9.9h-2.007l3.132-14.757h2.65l-.161,6.746-.06.783h.04l.261-.783,2.731-6.746h2.65l-3.132,14.757h-2.008l2.088-9.9h-.12l-.2.622-3.353,9.276Z" fill="#fff"/><path d="M1504.174,280.139h2.168L1503.21,294.9h-2.168Z" fill="#fff"/><path d="M1508.731,287.206l-.04-.682h-.121L1506.8,294.9h-1.987l3.132-14.757h1.927l.462,7.69.04.682h.12l1.787-8.372h1.988L1511.14,294.9h-1.927Z" fill="#fff"/><path d="M1518.226,285.439l.482-2.268a1.236,1.236,0,0,0-.1-.924.5.5,0,0,0-.4-.16.9.9,0,0,0-.763.542,2.147,2.147,0,0,0-.18.542l-1.888,8.874a1.079,1.079,0,0,0,.1.923.454.454,0,0,0,.381.161.829.829,0,0,0,.763-.542,2.62,2.62,0,0,0,.2-.542l.562-2.67h-.863l.381-1.827h3.032l-1.546,7.348H1517.1l-.121-.7a2.71,2.71,0,0,1-1.867.863,1.846,1.846,0,0,1-1.445-.5,1.9,1.9,0,0,1-.522-1.085,3.471,3.471,0,0,1,.1-1.606l1.847-8.693a4.891,4.891,0,0,1,.683-1.586,4.334,4.334,0,0,1,1.084-1.1,3.232,3.232,0,0,1,1.787-.5,2.274,2.274,0,0,1,1.566.5,1.984,1.984,0,0,1,.622,1.1,3.427,3.427,0,0,1,.04,1.586l-.481,2.268Z" fill="#fff"/><path d="M1494.923,300.849a4.6,4.6,0,0,0,.14-1.446c-.06-.4-.221-.682-.562-.682-.562.02-.863.6-1.044,1.465a2.791,2.791,0,0,0,.2,1.586c.221.562.482,1.145.784,1.767a15.23,15.23,0,0,1,.923,2.249,4.491,4.491,0,0,1,.141,2.309,5.67,5.67,0,0,1-1.165,2.59,3.268,3.268,0,0,1-2.63,1.124c-1.385,0-2.068-.522-2.329-1.325a5.8,5.8,0,0,1,.081-2.972l2.168-.2a5.365,5.365,0,0,0-.181,1.706c.06.482.221.8.683.8.642,0,1-.683,1.224-1.626a2.99,2.99,0,0,0-.22-1.626c-.061-.141-.121-.3-.181-.442s-.121-.3-.181-.442c-.14-.3-.3-.622-.462-.963s-.321-.683-.482-1.064a6.875,6.875,0,0,1-.582-2.289,3.4,3.4,0,0,1,.081-1.185,4.8,4.8,0,0,1,1.144-2.329,3.275,3.275,0,0,1,2.409-1.124c1.345-.02,1.968.522,2.229,1.3a5.473,5.473,0,0,1-.02,2.61Z" fill="#fff"/><path d="M1502.209,308.619a5.557,5.557,0,0,1-.7,1.606,4.379,4.379,0,0,1-1.1,1.084,2.964,2.964,0,0,1-1.767.5,2.214,2.214,0,0,1-1.586-.5,1.9,1.9,0,0,1-.6-1.084,3.314,3.314,0,0,1-.02-1.606l1.847-8.694a4.889,4.889,0,0,1,.683-1.586,4.348,4.348,0,0,1,1.084-1.1,3.24,3.24,0,0,1,1.787-.5,2.279,2.279,0,0,1,1.566.5,1.987,1.987,0,0,1,.622,1.1,3.441,3.441,0,0,1,.041,1.586Zm-.321-8.694a1.233,1.233,0,0,0-.1-.923.5.5,0,0,0-.4-.161.9.9,0,0,0-.763.542,2.169,2.169,0,0,0-.18.542l-1.847,8.694a1.076,1.076,0,0,0,.1.923.454.454,0,0,0,.382.161.831.831,0,0,0,.763-.542,2.651,2.651,0,0,0,.2-.542Z" fill="#fff"/><path d="M1509.316,308.619a5.588,5.588,0,0,1-.7,1.606,4.367,4.367,0,0,1-1.1,1.084,2.962,2.962,0,0,1-1.767.5,2.217,2.217,0,0,1-1.586-.5,1.9,1.9,0,0,1-.6-1.084,3.289,3.289,0,0,1-.02-1.606l1.847-8.694a4.866,4.866,0,0,1,.682-1.586,4.37,4.37,0,0,1,1.085-1.1,3.237,3.237,0,0,1,1.786-.5,2.284,2.284,0,0,1,1.567.5,1.993,1.993,0,0,1,.622,1.1,3.457,3.457,0,0,1,.04,1.586Zm-.321-8.694a1.229,1.229,0,0,0-.1-.923.5.5,0,0,0-.4-.161.893.893,0,0,0-.763.542,2.136,2.136,0,0,0-.181.542l-1.847,8.694a1.08,1.08,0,0,0,.1.923.452.452,0,0,0,.381.161.832.832,0,0,0,.763-.542,2.7,2.7,0,0,0,.2-.542Z" fill="#fff"/><path d="M1514.053,303.961l-.04-.683h-.12l-1.767,8.372h-1.988l3.132-14.756h1.927l.462,7.689.041.683h.12l1.787-8.372h1.987l-3.131,14.756h-1.928Z" fill="#fff"/><path d="M1518.57,309.421h2.108l-.462,2.229h-2.108Zm2.549-12.527h2.349l-2.831,10.781h-1.264Z" fill="#fff"/></g></svg>
        </div>
    </button>

    <div class="modal zoom-in" data-backdrop="static" id="catalogpages" tabindex="-1" role="dialog" aria-labelledby="catalogpages" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pt-3 pb-5">
                    <h5 class="mb-4 modal-title-product font-weight-bold text-uppercase">catalog pages</h5>
                    <div>
                        <a :href="catalog.catalog" target="_blank" class="btn btn-primary full-width d-block text-left mb-2" v-for="(catalog, catalogindex) in product.catalogs" :key="\`catalog-indexes-\${catalogindex}\`">
                            <span class="icon icon-icon-paper mr-1"></span> {{ catalog.title }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
`;


var productcolorstemplate = /*html */`
<div v-if="colorListsIterate && colorListsIterate.length" class="colors mb-5">
    <h5 class="blue-title text-uppercase">available colors</h5>
    <div class="print-hide colormodule available-colors">
        <div 
        v-for="(acolor, acolorindex) in colorListsIterate"
        :key="\`pcolorbind-\${acolorindex}\`"
        :class="\`color-item \${color == acolor._hid ? 'active' : ''}\`" 
        v-tooltip:top="acolor.colorname"
        @click.stop="setColor(acolor._hid)"
        >
            <span v-if="!acolor.iscolorimage" class="color-hex" :style="'background: ' + acolor.colorhex + ';'"></span>
            <template v-else>
                <span v-if="acolor.colorimageurl" 
                class="color-hex lazyload bg-cover" 
                v-img:data-bgset="acolor.colorimageurl"
                :fallback="getImageFallback.normal"
                ></span>
                <span v-else
                class="color-hex lazyload bg-cover" 
                :data-bgset="getImageFallback.normal"
                ></span>
            </template>
            <span class="color-pantone">{{acolor.pantone}}</span>
        </div>
    </div>

    <div class="print-only">
        <div class="colormodule available-colors">
            <div 
            v-for="(acolor, acolorindex) in colorListsIterate"
            class="color-item"
            :key="\`pcolorbind-print-\${acolorindex}\`"
            >
                <span v-if="!acolor.iscolorimage" class="color-hex" :style="'background: ' + acolor.colorhex + ';'"></span>
                <template v-else>
                    <span v-if="acolor.colorimageurl" 
                    class="color-hex lazyload bg-cover" 
                    v-img:data-bgset="acolor.colorimageurl"
                    :fallback="getImageFallback.normal"
                    :style="\`background-image:url(\${acolor.colorimageurl});\`"
                    ></span>
                    <span v-else
                    class="color-hex lazyload bg-cover" 
                    :data-bgset="getImageFallback.normal"
                    ></span>
                </template>
            </div>
        </div>
    </div>

    <div class="print-only">
    <span
    v-for="(acolor, acolorindex) in colorListsIterate"
    :key="\`pcolorbind-print-text-\${acolorindex}\`"
    >{{acolor.colorname}}<template v-if="acolorindex!=(colorListsIterate.length-1)">, </template>
    </span>
    </div>
</div>
`;

var productStockShapes = /*html */`
<div v-if="stockShapesListsIterate && stockShapesListsIterate.length">

    <div class="colors mb-5 print-hide" style="display:flex;align-items:center;">
        <h5 class="blue-title text-uppercase mb-2">available stock shapes:</h5>
        <dropdown-vue 
        :lists="stockShapesListsIterate" 
        @input="(value) => selectProductStockshape(value)"
        :value="stockshape"
        placeholder="Search"
        style="max-width:300px;"
        class="mb-2 ml-3"
        ></dropdown-vue>
    </div>

    <div class="colors mb-5 print-only">
        <h5 class="blue-title text-uppercase mb-2">available stock shapes:</h5>
        <div class="mt-3">
            <label
            v-for="(stc, stci) in stockShapesListsIterate"
            :key="\`stockshape-printable-\${stci}\`"
            v-html="stc.text"
            class="mr-2 pt-1 pb-1 pl-3 pr-3"
            style="border: 1px solid #1f4385;border-radius:3px;color:#1f4385;"
            ></label>
        </div>
    </div>

</div>
`;


var productfeaturesOptions = /*html */`
<div data-accordion-module-item v-if="product.features_options2" class="product-accordion open mb-4">
    <button class="product-accordion-header align-items-center" data-accordion-module>
        <div class="accordion-header d-flex">
            <span class="icon icon-list"></span>
            <span class="text text-uppercase font-extra-bold">features &amp; options</span>
        </div>
        <div class="accordion-indicator" data-accordion-indicator>
            <div class="accicon-indicator">
                <span></span>
                <span></span>
            </div>
        </div>
    </button>
    <div id="content" v-html="product.features_options2" class="pad features_and_options" data-accordion-module-content>
        <!-- html feature -->
    </div>
</div>
`;

var compliancesData = /*html */`
<div v-if="getComplianceArchive && getComplianceArchive.length" data-accordion-module-item class="product-accordion open mb-4">
    <button class="product-accordion-header" data-accordion-module>
        <div class="accordion-header d-flex align-items-center">
            <span class="icon icon-icon-paper"></span>
            <span class="text text-uppercase font-extra-bold">compliances</span>
        </div>
        <div class="accordion-indicator" data-accordion-indicator>
            <div class="accicon-indicator">
                <span></span>
                <span></span>
            </div>
        </div>
    </button>
    <div id="content" class="pad" data-accordion-module-content>
        <div v-html="product.product_line_helpers.complianceNote"></div>

        <ul class="list-unstyled p-0 list-compliances">
            <li v-for="(comp, compi) in product.productline.compliancesdata" :key="\`compliance-index-\${compi}\`">
                <a v-if="comp.documentLink" :href="comp.documentLink" target="_blank">{{ comp.compliance }}</a>
                <span v-else>{{ comp.compliance }}</span>
            </li>
        </ul>

        <a :href="\`\${AA_JS_OBJ.API_BASE}/wp-json/v1/download/compliances?product=\${product.product_slug}\`" download rel="nofollow" class="button-icon mt-3 max-300 print-hide">
            <span class="icon icon-water"></span>
            <span class="text text-uppercase">Download aLL</span>
        </a>

    </div>

</div>
`;

var productSpecs = /*html */`
<div v-if="product.specificationIterate && product.specificationIterate.length" data-accordion-module-item class="product-accordion open mb-4">
    <button class="product-accordion-header" data-accordion-module>
        <div class="accordion-header d-flex">
            <span class="icon icon-pencil2"></span>
            <span class="text text-uppercase font-extra-bold">specifications</span>
        </div>
        <div class="accordion-indicator" data-accordion-indicator>
            <div class="accicon-indicator">
                <span></span>
                <span></span>
            </div>
        </div>
    </button>
    <div id="content" data-accordion-module-content>
        <div class="row print-hide">
            <div class="col-lg-6">
                <table class="table-stripe specs">
                    <tr v-for="(spec, speci) in productSpecification.firstWay" :key="\`product-firstWay-combo-\${speci}\`">
                        <td><strong>{{spec.label}}</strong></td>
                        <td>{{spec.value}}</td>
                    </tr>
                    <tr v-for="(spec, speci) in productSpecification.secondWay" :key="\`product-secondWay-revert-combo-\${speci}\`" class="frontend-mobile-only revert">
                        <td><strong>{{spec.label}}</strong></td>
                        <td>{{spec.value}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-6 frontend-desktop-only">
                <table class="table-stripe specs">
                    <tr v-for="(spec, speci) in productSpecification.secondWay" :key="\`product-secondWay-combo-\${speci}\`">
                        <td><strong>{{spec.label}}</strong></td>
                        <td>{{spec.value}}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row print-only">
            <div class="col-lg-12">
                <table class="table-stripe specs">
                    <tr v-for="(spec, speci) in productSpecification.full" :key="\`product-full-combo-\${speci}\`">
                        <td><strong>{{spec.label}}</strong></td>
                        <td>{{spec.value}}</td>
                    </tr>
                </table>
            </div>
        </div>


        <div v-if="product.productline.pnotes2" v-html="product.productline.pnotes2" class="mt-5 pnotes2div"></div>

    </div>
</div>
`;



var productFieldsAccordions = /*html */`
${productPricing}

${productfeaturesOptions}

${productSpecs}

${imprintOptions}

${compliancesData}
`;


var productLayoutTemplate = /*html */`
<div class="product-main-wrap">
<div class="d-flex product-wrap mt-5 justify-content-between">

    <div class="prod-col prod-col-left" style="margin-bottom: 50px;">

        <!-- product name -->
        ${productName}
        <!-- end of product name -->

        <!-- product carousels images -->
        ${productCarouselImages}
        ${maingallerypopup}
        <!-- end product carousels images -->


        <!-- idea galleries -->
        ${ideagalleries}
        <!-- end of idea galleries-->


        <!-- templates -->
        ${template_}
        <!-- end of templates -->

        <!-- stock shape library -->
        ${stockShapeLibrary}
        <!-- end of stock shape library -->


        <!-- premium bg -->
        ${premiumBackgrounds}
        <!-- end of premum bg -->


        <div class="frontend-mobile-only">
            <!-- product description -->
            <div class="description mb-5">
                <h5 class="blue-title text-uppercase">description</h5>
                <div v-html="product.description"></div>
            </div>
            <!-- end product description -->


            <!-- product colors -->
            ${productcolorstemplate}
            <!-- end product colors -->

            <!-- stock shapes -->
            ${productStockShapes}
            <!-- end of stock shapes -->
        </div>


        <!-- product buttons -->
        ${leftButtons}
        <!-- end product buttons -->

    </div>



    <div class="prod-col prod-col-right">

        <div class="mb-5 d-flex justify-content-between ">

            <div class="frontend-desktop-only">
                <span 
                class="mb-0 productname text-uppercase font-extra-bold" 
                :style="'color: ' + product.productline.printmethod.method_hex + ';'">
                    {{ product.product_method_combination_name }}
                </span>
                <span class="d-block productdesc">{{ product.product.product_description }}</span>
            </div>
                
            <div class="print-hide">
                ${printmethodcompare}
                ${printmethodcomparemodal}
            </div>

        </div>

        <div class="frontend-desktop-only">
            <!-- product description -->
            <div class="description mb-5">
                <h5 class="blue-title text-uppercase">description</h5>
                <div v-html="product.description"></div>
            </div>
            <!-- end product description -->


            <!-- product colors -->
            ${productcolorstemplate}
            <!-- end product colors -->

            <!-- stock shapes -->
            ${productStockShapes}
            <!-- end of stock shapes -->
        </div>

        <div class="print-hide">
            ${productFieldsAccordions}
        </div>


    </div>
</div>

<div class="print-only">
    ${productFieldsAccordions}
</div>

</div>
`;


var productTemplate = /*html */``;

productTemplate += /*html */`<div id="productprint" class="container">`;

productTemplate += productbreadcrumb;

productTemplate += productSubcategory;

productTemplate += productLayoutTemplate;

productTemplate += /*html */`</div>`;