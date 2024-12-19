var productPricing = /*html */`
    <div data-accordion-module-item class="product-accordion open mb-4" attr-module="pricing">
        <button class="product-accordion-header" data-accordion-module>
            <div class="accordion-header d-flex align-items-center">
                <span class="icon icon-dollar"></span>
                <span class="text text-uppercase font-extra-bold">pricing</span>
            </div>
            <div class="accordion-indicator" data-accordion-indicator>
                <div class="accicon-indicator">
                    <span></span>
                    <span></span>
                </div>
            </div>
        </button>

        <div id="content" data-accordion-module-content>

            <p v-if="product.productline.couponcode && product.productline.couponcode.code" class="product-cuopon-code text-right f-text-primary font-weight-bold mb-1 text-uppercase">{{ product.productline.couponcode.code }}</p>

            <div class="tbl-overflow f-scroll-custom thin">
                <!-- desktop table -->
                <div class="frontend-desktop-only">
                    <table class="table-stripe aa-table reverse small">
                        <tr>
                            <td class="font-weight-bold text-uppercase">item #</td>
                            <td class="font-weight-bold text-uppercase">description</td>
                            <td 
                            v-for="(qty, qtyi) in product.all_quantities"
                            :key="\`qty-key-\${qtyi}\`"
                            class="font-weight-bold text-uppercase">{{ qty.toLocaleString() }}</td>
                        </tr>

                        <tr v-for="(itp, itpi) in interatePricing" :key="\`iterate-pricing-index-\${itpi}\`">
                            <td>{{ itp.itemno }}</td>
                            <td>
                                <span class="d-block">{{ itp.desc }}</span>
                                <small>
                                    <i v-for="(apnd, apndx) in itp.appends" :key="\`ipndx-\${apndx}\`" class="price-charges">{{ apnd.text }} <template v-if="apnd.pvalue">({{apnd.pvalue}})</template><template v-if="apndx+1!=itp.appends.length">,&nbsp;</template></i>
                                </small>
                            </td>
                            <template v-if="itp.note_value">
                                <td :colspan="product.all_quantities.length" class="text-center">{{itp.note_value}}</td>
                            </template>
                            <template v-else>
                                <td v-for="(price, pricei) in itp.price" :key="\`itp-price-\${pricei}\`">
                                    {{ price.value }}{{price.unit_value}}<span v-if="price.asterisk" class="asterisk">*</span>
                                </td>
                            </template>
                        </tr>

                        <!-- setup charge -->
                        <tr v-if="product.productline.formatted_setup_charge">
                            <td></td>
                            <td class="text-left" :colspan="product.all_quantities.length+1">
                                Setup Charge {{ product.productline.formatted_setup_charge }} <span v-if="getglobaljsvars.chargesIndicator">{{getglobaljsvars.chargesIndicator}}</span> <template v-for="(scper, scperindex) in productSetupChargeIterate">{{ scper.text }}<template v-if="scperindex+1!=productSetupChargeIterate.length">,&nbsp;</template></template>
                            </td>
                        </tr>
                        <!-- end of setup charge -->


                        <!-- imrpint types -->
                        <tr v-for="(imprint, imprintindex) in productImprintsIterate" :key="\`the-imprint-pricing-index-\${imprintindex}\`">
                            <td></td>
                            <td class="text-left" :colspan="product.all_quantities.length+1">
                                {{ imprint.textlabel }} <span v-if="getglobaljsvars.diechargeLabel">{{getglobaljsvars.diechargeLabel}}</span> {{ imprint.labeled_value }} <span v-if="getglobaljsvars.chargesIndicator">{{getglobaljsvars.chargesIndicator}}</span> <small style="white-space: nowrap;" v-if="imprint.min_prod_days">(<i>Minimum {{imprint.min_prod_days}} production days</i>)</small>
                            </td>
                        </tr>
                        <!-- end of imprint types -->

                        <!-- spacer -->
                        <tr v-if="rowCounterDesktop%2==0">
                            <td></td>
                            <td :colspan="product.all_quantities.length+1"><p></p></td>
                        </tr>
                        <!-- end spacer -->
                    </table>

                    <div v-if="product.productline.pnotesdata.length" class="pricing-notes-desktop d-flex justify-content-end">
                        <div class="text-right pnotes-desktop">
                            <p class="mb-1" v-for="(pn, pni) in product.productline.pnotesdata" :key="\`pndata-index-\${pni}\`">{{pn}}</p>
                        </div>
                    </div>
                </div>
                <!-- end of desktop table -->

                <!-- mobile table -->
                <div class="frontend-mobile-only">
                    <table class="table-stripe aa-table reverse small frontend-mobile-only">
                        <tr>
                            <td class="mthead font-weight-bold text-uppercase">quantity</td>
                            <td class="mthead font-weight-bold text-uppercase">price</td>
                            <td v-for="(itp, itpi) in interatePricing.slice(1)" :key="\`itp-price-mobile-\${itpi}\`">
                                <span :class="\`icon \${itp.icon}\`"></span>
                            </td>
                        </tr>

                        <tr v-for="(qty, qtyi) in product.all_quantities">
                            <td class="font-weight-bold text-uppercase">{{ qty.toLocaleString() }}</td>
                            <td v-for="(itp, itpi) in interatePricing" :key="\`itp-with-qty-mobile-\${itpi}\`">
                                {{ itp.price[qtyi].value }}{{itp.price[qtyi].unit_value}}<span v-if="itp.price[qtyi].asterisk" class="asterisk">*</span>
                            </td>
                        </tr>

                        <!-- setup charge -->
                        <tr v-if="product.productline.formatted_setup_charge">
                            <td class="text-left" :colspan="product.all_quantities.length+1">
                                Setup Charge {{ product.productline.formatted_setup_charge }} <span v-if="getglobaljsvars.chargesIndicator">{{getglobaljsvars.chargesIndicator}}</span> <template v-for="(scper, scperindex) in productSetupChargeIterate">{{ scper.text }}<template v-if="scperindex+1!=productSetupChargeIterate.length">,&nbsp;</template></template>
                            </td>
                        </tr>
                        <!-- end of setup charge -->

                        <!-- imrpint types -->
                        <tr v-for="(imprint, imprintindex) in productImprintsIterate" :key="\`the-imprint-pricing-index-\${imprintindex}\`">
                            <td class="text-left" :colspan="interatePricing.length+1">
                                {{ imprint.textlabel }} <span v-if="getglobaljsvars.diechargeLabel">{{getglobaljsvars.diechargeLabel}}</span> {{ imprint.labeled_value }} <span v-if="getglobaljsvars.chargesIndicator">{{getglobaljsvars.chargesIndicator}}</span> <small style="white-space: nowrap;" v-if="imprint.min_prod_days">(<i>Minimum {{imprint.min_prod_days}} production days</i>)</small>
                            </td>
                        </tr>
                        <!-- end of imprint types -->

                        <tr v-if="routCounterMobile%2!=1">
                            <td :colspan="interatePricing.length+1"><p></p></td>
                        </tr>

                        <tr>
                            <td class="colored-column-mobile" :colspan="interatePricing.length+1">

                                <p v-for="(itp, itpi) in interatePricing.slice(1)" :key="\`itp-charges-mobile-\${itpi}\`">
                                    <span :class="\`icon mr-1 \${itp.icon}\`"></span>
                                    <span>{{itp.desc}}</span>

                                    <small>
                                        <i v-for="(apnd, apndx) in itp.appends" :key="\`ipndx-\${apndx}\`" class="price-charges">{{ apnd.text }} <template v-if="apnd.pvalue">({{apnd.pvalue}})</template><template v-if="apndx+1!=itp.appends.length">,&nbsp;</template></i>
                                    </small>
                                </p>

                                <div class="pricing-notes-mobile mt-3">
                                    <div class="pnotes-mobile">
                                        <p class="mb-1" v-for="(pn, pni) in product.productline.pnotesdata" :key="\`pndata-index-mobile-\${pni}\`">{{pn}}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        
                    </table>
                </div>
                <!-- end of mobile table -->

            </div>
        </div>
    </div>
`;