var printmethodcompare = /*html */`
<div>
    <div v-if="!pcompare.loading && pcompare.data && pcompare.data.length && !product.productline.printmethod.is_unprinted" 
    :class="\`product-method \${product.productline.printmethod.method_name}\-\${product.productline.printmethod.method_name2}\`">
        <span class="d-block mname1">{{ product.productline.printmethod.method_name }}</span>
        <span class="d-block mname2" :style="\`color:\${product.productline.printmethod.method_hex};\`">{{product.productline.printmethod.method_name2}}</span>
        <button v-if="pcompare.data.length > 1" data-toggle="modal" data-target="#comparePrintMethod" class="print-hide text-left d-block btnv2-link compare"><i class="icon-compare"></i> <span>compare print methods</span></button>
    </div>
    <div v-if="pcompare.loading" style="width: 135px;">
        <div class="skeleton animation mb-1" style="height: 20px;max-width: 100px;"></div>
        <div class="skeleton animation mb-1" style="height: 29px;"></div>
        <div class="skeleton animation mb-1" style="height: 10px;"></div>
    </div>
</div>
`;



var printmethodcomparemodal = /*html */`
<!-- Print Methods Comparison Modal -->
<div v-if="!pcompare.loading && pcompare.data && pcompare.data.length > 1" data-backdrop="static" data-keyboard="false" class="modal zoom-in comparemethod-modal" id="comparePrintMethod" tabindex="-1" role="dialog" aria-labelledby="compareModelLabel" aria-hidden="true">
<div class="modal-dialog" role="document" style="max-width: 100%; width: fit-content;">
    <div class="modal-content" style="max-width: 100%; margin: 0 auto">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="comparedefault=product.hid">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            
            <form action="#" @submit.prevent="changeProduct" id="printmethodcomparison">

                <h5 class="mb-5 modal-title-product font-weight-bold text-uppercase text-center">Select Print Method</h5>
                
                <div class="methodcomparison compare-methods row justify-content-center">

                    <div 
                    v-for="(cproduct, cproductindex) in interateCompareData"
                    :key="\`cproduct-compare-index-\${cproductindex}\`"
                    class="printmethodcol">
                        <div class="radio-container">
                            <input 
                                type="radio" 
                                :id="\`compare_value-\${cproduct.hid}\`" 
                                name="method"
                                :value="cproduct.hid"
                                v-model="comparedefault"
                            />
                            <span class="checkmark"></span>
                        </div>

                        <div class="print-method-comparison-item">
                            <div class="print-method-header" :style="\`background: \${cproduct.productline.printmethod.method_hex};\`">
                                <h4 class="text-center">
                                    <span class="text-capitalize font1">{{ cproduct.productline.printmethod.method_name }}</span> 
                                    <span class="text-uppercase font2">{{ cproduct.productline.printmethod.method_name2 }}</span>
                                </h4>
                            </div>

                            <div class="compare-tbl">
                                <table>
                                    <tr>
                                        <td class="th">quantity</td>
                                        <td class="th">price</td>
                                        <td class="th" 
                                            v-for="(plpricing, plpricingindex) in cproduct.productline.pricing_data" 
                                            :key="\`pline-pricing-index-\${plpricingindex}\`">
                                            <span :class="\`icon \${plpricing.chargetypes.icon}\`"></span>
                                        </td>
                                    </tr>

                                    <tr v-for="(price, priceindex) in cproduct.iteratedprice" :key="\`price-index-data-\${priceindex}\`">
                                        <td class="bold">{{ price.quantity.toLocaleString() }}</td>
                                        <td>{{ price.value }}<span v-if="price.asterisk" class="asterisk">*</span></td>
                                        <td v-for="(plineprice, plinepriceindex) in price.plines" :key="\`plineprice-index-data-\${plinepriceindex}\`">
                                            {{ plineprice.value }}{{plineprice.unit_value}}<span v-if="plineprice.asterisk" class="asterisk">*</span>
                                        </td>
                                    </tr>

                                    <!-- setup charge -->
                                    <tr v-if="cproduct.productline.formatted_setup_charge">
                                        <td class="text-left" :colspan="cproduct.productline.pricing_data.length+2">
                                            Setup Charge {{ cproduct.productline.formatted_setup_charge }} <span v-if="getglobaljsvars.chargesIndicator">{{getglobaljsvars.chargesIndicator}}</span> <template v-for="(scper, scperindex) in cproduct.setupChargePer">{{ scper.text }}<template v-if="scperindex+1!=cproduct.setupChargePer.length">,&nbsp;</template></template>
                                        </td>
                                    </tr>
                                    <!-- end of setup charge -->

                                    <!-- imrpint types -->
                                    <tr v-for="(imprint, imprintindex) in cproduct.theimprinttypes" :key="\`the-imprint-index-\${imprintindex}\`">
                                        <td class="text-left" :colspan="cproduct.productline.pricing_data.length+2">
                                            {{ imprint.textlabel }} <span v-if="getglobaljsvars.diechargeLabel">{{getglobaljsvars.diechargeLabel}}</span> {{ imprint.labeled_value }} <span v-if="getglobaljsvars.chargesIndicator">{{getglobaljsvars.chargesIndicator}}</span> <small style="white-space: nowrap;" v-if="imprint.min_prod_days">(<i>Minimum {{imprint.min_prod_days}} production days</i>)</small>
                                        </td>
                                    </tr>
                                    <!-- end of imprint types -->
                                    
                                    <!-- spacer ui -->
                                    <tr v-if="cproduct.thetablerowcounter % 2 != 0">
                                        <td class="text-left" :colspan="cproduct.productline.pricing_data.length+2"><p></p></td>
                                    </tr>
                                    <!-- end of spacer ui -->


                                    <!-- footer tbl -->
                                    <tr v-if="cproduct.theplinecharges.length || cproduct.productline.pnotesdata.length">
                                        <td class="text-left colored-column-mobile last-footer-col" :colspan="cproduct.productline.pricing_data.length+2">
                                            <p v-for="(tplc, tplcindex) in cproduct.theplinecharges" :key="\`theplinecharge-index-\${tplcindex}\`">
                                                <span :class="\`icon mr-1 \${tplc.chargetypes.icon}\`"></span>
                                                <span>{{ tplc.chargetypes.charge_name }}</span>
                                                <small v-if="tplc.perchargeTexts.length"> 
                                                    - <i class="price-charges" v-for="(pctxt, pctxtindex) in tplc.perchargeTexts" :key="\`pctxt-index-\${pctxtindex}\`">
                                                    {{ pctxt.text }} <span v-if="getglobaljsvars.chargesIndicator">{{getglobaljsvars.chargesIndicator}}</span><template v-if="pctxtindex+1!=tplc.perchargeTexts.length">, </template>
                                                    </i>
                                                </small>
                                            </p>

                                            <div v-if="cproduct.productline.pnotesdata.length" class="pricing-notes-mobile">
                                                <div class="pnotes-mobile">
                                                    <p class="mb-1" v-for="(pnt, pntindex) in cproduct.productline.pnotesdata" :key="\`pnotesdata-index-\${pntindex}\`">{{pnt}}</p>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                    <!-- end footer tbl -->

                                </table>
                            </div>

                            <div v-if="cproduct.productline.printmethod.keyfeaturesdata.length" class="features-c">
                                <ul class="tfeature">
                                    <li v-for="(keyf, keyfind) in cproduct.productline.printmethod.keyfeaturesdata" :key="\`keyf-ind-\${keyfind}\`">
                                        <span :class="\`icon icon \${keyf.image}\`" :style="\`color:\${cproduct.productline.printmethod.method_hex};\`"></span>
                                        <span :style="\`color:\${cproduct.productline.printmethod.method_hex};\`">{{keyf.text}}</span>
                                    </li>
                                </ul>
                            </div>


                        </div>

                    </div>

                </div>

                <div class="button-wrap text-center mb-5 mt-3">
                    <button type="submit" class="btn btn-primary btn-secondary-hover compare-method-changes">Confirm Changes</button>
                </div>

            </form>

        </div>
    </div>
</div>
</div>

<!-- end print method comparison modal -->
`;