var productVue = new Vue({
    el: `#productVueController`,
    template: productTemplate,
    mixins: [
        frontMixins
    ],
    components: {
        VueSlickCarousel: window['vue-slick-carousel']
    },
    data() {
        return {
            product: productJSON,
            maingalleryNavFor: null,
            maingalleryThumbNavfor: null,
            ideamaingalleryNavfor: null,
            ideagalleryThumbNavfor: null,
            popupMainGalleryNav: null,
            popupMainGalleryThumb: null,
            realtimeKey: Date.now(),
            comparedefault: productJSON.hid,
            pcompare: {
                data: [],
                loading: false
            },
            limit: 4,
            variantLoading: true,
            variation: inventoryJSVars.variation,
            color: null,
            stockshape: null,
            productVariant: null,
            isMounted: true,
            zoomelevated: false
        }
    },
    computed: {
        _currentVariant() {
            if( this.product.variations && this.product.variations.variations.length ) {
                if( !this.variation ) {
                    return this.product.variations.variations[0];
                }
                return this.product.variations.variations.find(row => row.slug == this.variation);
            }
            return null;
        },
        archiveLink() {
            return `${AA_JS_OBJ.API_BASE}/wp-json/v1/download`;
        },
        jsvars() {
            return inventoryJSVars;
        },
        productbuttons() {
            const e = this;
            return [
                { text: 'Download catalog page', icon: 'icon-catalog-download', iconstyle: '', soon: false, hide: !e.product.catalogs || !e.product.catalogs.length, callback: () => {
                    if(e.product.catalogs && e.product.catalogs.length) {
                        if(e.product.catalogs.length > 1) {
                            jQuery('#catalogpages').modal('show');
                        } else {
                            window.open(e.product.catalogs[0].catalog, '_blank');
                        }
                    }
                } },
                { text: 'create virtual mockup', icon: 'icon-edit-square', iconstyle: '', soon: false, hide: !e.jsvars.SAGEVDSAUTH || !e.jsvars.SAGEVDSSUPPID || !e._currentVariant || !e._currentVariant.vdsid, callback: (event) => {
                    LaunchVDS(
                        event.target,
                        e.jsvars.SAGEVDSSUPPID,
                        e._currentVariant.vdsid,
                        ''
                    );
                } },
                { text: 'order a sample', icon: 'icon-cart-plus', iconstyle: '', soon: true, callback: () => {
                    
                } },
                { text: 'build a quote', icon: 'icon-dollar-square', iconstyle: 'font-size: 25px;', soon: true, callback: () => {
                    
                } },
                { text: 'shipping estimate', icon: 'icon-car', iconstyle: '', soon: true, callback: () => {
                    
                } },
                { text: 'email', icon: 'icon-email', iconstyle: '', soon: false, callback: () => {
                    const emailParams = jQuery.param({
                        subject: `${this.product.product_method_combination_name} - EMAIL`,
                        body: `Hi,I found this website and thought you might like it, please check the link below. \n\n${window.location.href}`
                    });
                    window.open(`mailto:?${emailParams}`);
                } },
                { text: 'print', icon: 'icon-icon-print', iconstyle: '', soon: false, callback: () => {
                    e.printPreload();
                    window.print();
                } },
            ]
        },
        colorListsIterate() {
            if( !this.product.variations ) { return null; }
            if( this.product.variations.key == 'color' ) {
                return this.product.variations.variations.map(row => {
                    row._hid = row.hid;
                    return row;
                });
            }
            if( this.product.variations.key == 'color-stockshape') {
                return this.$_.uniqBy(this.product.variations.variations, 'coloridhex').map( row => {
                    row.colorname = row.thecolor.colorname;
                    row.colorimageurl = row.thecolor.colorimageurl;
                    row.iscolorimage = row.thecolor.iscolorimage;
                    row.colorhex = row.thecolor.colorhex;
                    row._hid = row.thecolor.hid;
                    row.in_stock = row.thecolor.in_stock && row.theshape.in_stock;
                    row.isavailable = row.thecolor.isavailable;
                    row.pantone = row.thecolor.pantone;
                    return row;
                });
            }
            return null;
        },
        stockShapesListsIterate() {
            if( !this.product.variations ) { return null; }
            if( this.product.variations.key == 'stockshape' ) {
                return this.product.variations.variations.map( row => {
                    row.text = `${row.code} <i> - ${row.stockname}</i>`;
                    row.reference = `${row.code} - ${row.stockname}`;
                    row.value = row.hid;
                    return row;
                });
            }
            if( this.product.variations.key == 'color-stockshape') {
                return this.$_.uniqBy(this.product.variations.variations, 'stockshapeidhex').map( row => {
                    row.text = `${row.theshape.code} <i> - ${row.theshape.stockname}</i>`;
                    row.reference = `${row.theshape.code} - ${row.theshape.stockname}`;
                    row.value = row.theshape.hid;
                    return row;
                });
            }
            return null;
        },
        getVariationName() {
            if( this.productVariant ) {
                if( this.product.variations.key == 'color-stockshape' ) {
                    return `${this.productVariant.thecolor.colorname} ${this.productVariant.theshape.code}`
                }

                if( this.product.variations.key == 'color') {
                    return this.productVariant.colorname;
                }

                if( this.product.variations.key == 'stockshape') {
                    return this.productVariant.code;
                }
            }
            return null;
        },
        getVariationEntries() {

            const stocks = [];
            if(this.productVariant) {
                if(this.productVariant.hasOwnProperty('thecolor')) {
                    stocks.push(this.productVariant.thecolor.in_stock);
                }
                if(this.productVariant.hasOwnProperty('theshape')) {
                    stocks.push(this.productVariant.theshape.in_stock);
                }
                if(this.productVariant.hasOwnProperty('in_stock')) {
                    stocks.push(this.productVariant.in_stock);
                }
            }

            let out_of_stock = stocks.filter(stock => stock == 0).length;

            if( this._currentVariant ) {
                return {
                    templatedata: this.productVariant ? this.productVariant.templatedata : [],
                    ideagallerydata: this.productVariant ? this.productVariant.ideagallerydata : [],
                    imagedata: this._currentVariant.imagedata,
                    variation: this.product.variations.key,
                    slug: this.variation,
                    isavailable: 1,
                    variationName: this.getVariationName,
                    out_of_stock
                }
            }

            return {
                templatedata: [],
                ideagallerydata: [],
                imagedata: [{
                    ...this.product.productcomboimage
                }],
                variation: null,
                slug: this.product.productURL,
                isavailable: 1,
                out_of_stock,
                variationName: this.getVariationName
            };
        },
        getTemplateArchive() {
            if( this.getVariationEntries && Array.isArray(this.getVariationEntries.templatedata) && this.getVariationEntries.templatedata.length ) {
                var links = this.getVariationEntries.templatedata.filter( row => row.link );
                if( links.length ) {
                    let filename = this.product.product_method_combination_name;
                    if( this.getVariationEntries.isavailable ) {
                        filename += ` [${this.getVariationEntries.variationName}]`;
                    }
                    filename += ' product-templates.zip';

                    return `${this.archiveLink}/templates?${jQuery.param({
                        output_filename: filename,
                        slug: this.getVariationEntries.slug,
                        printmethodid: this.product.hid,
                        variant: this.getVariationEntries.variation
                    })}`;
                }
                return null;
            }
        },
        getImagesArchive() {
            let filename = this.product.product_method_combination_name;
            filename += ` [${this.getVariationEntries.variationName}]`;
            filename += ' images.zip';

            return `${this.archiveLink}/images?${jQuery.param({
                output_filename: filename,
                slug: this.getVariationEntries.slug,
                printmethodid: this.product.hid,
                variant: this.getVariationEntries.variation
            })}`;
        },
        getIdeaArchive() {
            if( this.getVariationEntries && Array.isArray(this.getVariationEntries.ideagallerydata) && this.getVariationEntries.ideagallerydata.length ) {
                var links = this.getVariationEntries.ideagallerydata.filter( row => (row.usecurfile && row.image) || (row.downloadLink) );
                if( links.length ) {
                    let filename = this.product.product_method_combination_name;
                    if( this.getVariationEntries.isavailable ) {
                        filename += ` [${this.getVariationEntries.variationName}]`;
                    }
                    filename += ' idea-galleries.zip';

                    return `${this.archiveLink}/ideagalleries?${jQuery.param({
                        output_filename: filename,
                        slug: this.getVariationEntries.slug,
                        printmethodid: this.product.hid,
                        variant: this.getVariationEntries.variation
                    })}`;
                }
                return null;
            }
        },
        // getDefaultImages() {
        //     if( this.getVariationEntries ) {
        //         return this.getVariationEntries.imagedata;
        //     } else {
        //         return [{
        //             image: this.product.productcomboimage.image,
        //             title: ''
        //         }];
        //     }
        // },
        maingallerycarousel() {
            return {
                pauseOnHover: true,
                dots: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                asNavFor: this.maingalleryNavFor,
                focusOnSelect: true,
                infinite: true
            }
        },
        maingallerythumbcarousel() {
            return {
                autoplay: true,
                pauseOnHover: true,
                dots: false,
                slidesToShow: 5,
                slidesToScroll: 1,
                arrows: false,
                asNavFor: this.maingalleryThumbNavfor,
                focusOnSelect: true,
                infinite: true,
                centerMode: false
            }
        },
        popupMaingallerycarousel() {
            return {
                autoplay: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                focusOnSelect: true,
                asNavFor: this.popupMainGalleryThumb,
                drag: true,
                infinite: true
            }
        },
        popupMaincarouselThumb() {
            return {
                autoplay: false,
                pauseOnHover: true,
                slidesToShow: 6,
                slidesToScroll: 6,
                dots: false,
                focusOnSelect: true,
                asNavFor: this.popupMainGalleryNav,
                drag: false,
                infinite: true
            }
        },
        ideagallerycarousel() {
            return {
                autoplay: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                focusOnSelect: true,
                asNavFor: this.ideagalleryThumbNavfor,
                drag: true,
                infinite: true
            }
        },
        ideagallerycarouselThumb() {
            return {
                autoplay: false,
                pauseOnHover: true,
                slidesToShow: 6,
                slidesToScroll: 6,
                dots: false,
                focusOnSelect: true,
                asNavFor: this.ideamaingalleryNavfor,
                drag: false,
                infinite: true
            }
        },
        interateCompareData() {
            return this.pcompare.data.map( product => {
                // handle pricing
                product.iteratedprice = [];
                product.all_quantities.map( qty => {
                    var thevalue = product.pricings.find(row => row.quantity == qty);
                    var ret = {
                        quantity: qty,
                        value: product.product_line_helpers.not_applicable,
                        asterisk: 0,
                        plines: product.productline.pricing_data.map( pline => {
                            var filterqty = pline.pvalues.find(x => x.quantity == qty);
                            var retpline = { quantity: qty, value: product.product_line_helpers.not_applicable, asterisk: 0 }
                            if( filterqty ) {
                                retpline.value = filterqty.labeled_value ? filterqty.labeled_value : filterqty.alternative_value;
                                retpline.unit_value = filterqty.unit_value;
                                retpline.asterisk = filterqty.asterisk;
                            }
                            return retpline;
                        })
                    };
                    if( thevalue ) {
                        ret.value = thevalue.labeled_value ? thevalue.labeled_value : thevalue.alternative_value;
                        ret.unit_value = thevalue.unit_value;
                        ret.asterisk = thevalue.asterisk;
                    }
                    product.iteratedprice.push(ret);
                });

                var setupcharges = [
                    { text: product.product_line_helpers.color, value: product.productline.multicolor },
                    { text: product.product_line_helpers.side, value: product.productline.second_side },
                    { text: product.product_line_helpers.wrap, value: product.productline.wrap },
                    { text: product.product_line_helpers.thousand, value: product.productline.per_thousand },
                    { text: product.product_line_helpers.item, value: product.productline.per_item }
                ];

                product.thetablerowcounter = product.all_quantities.length;

                product.setupChargePer = [];
                if( product.productline.formatted_setup_charge) {
                    product.thetablerowcounter++
                    product.setupChargePer = setupcharges.filter( row => row.value );
                }


                var imprintCharges = product.productline.imprinttypes.filter( row => row.sortref && row.formatted_value ).map( row => {
                    row.dupcount = product.productline.imprinttypes.filter(x => x.sortref == row.sortref).length
                    return row;
                });

                product.theimprinttypes = this.$_.uniqBy(this.$_.orderBy(imprintCharges, ['dupcount'], ['desc']), 'sortref').map( row => {
                    product.thetablerowcounter++;
                    var combinetitle = imprintCharges.filter( fimp => fimp.sortref == row.sortref).map( x => x.imprinttype.title).join(' / ');
                    if( combinetitle ) {
                        row.textlabel = combinetitle;
                    }
                    return row;
                });

                product.theplinecharges = product.productline.pricing_data.map( row => {
                    var handlepercharge = [
                        { text: product.product_line_helpers.piece, value: row.per_piece },
                        { text: product.product_line_helpers.color, value: row.per_color },
                        { text: product.product_line_helpers.side, value: row.per_side },
                        { text: product.product_line_helpers.thousand, value: row.per_thousand },
                        { text: product.product_line_helpers.item, value: row.per_item }
                    ]
                    row.perchargeTexts = handlepercharge.filter( x => x.value );
                    return row;
                });
                
                return product;
            });
        },
        interatePricing() {
            var e = this;
            var pricingInit = [{
                itemno: e.product.product_method_combination_name,
                desc: e.product.product.product_description,
                appends: [],
                icon: ``,
                note_value: ``,
                price: e.product.all_quantities.map( qty => {
                    var theprice = e.product.pricings.find(p => p.quantity == qty); 
                    var ret = { quantity: qty, value: e.product.product_line_helpers.not_applicable, asterisk: 0 };
                    if( theprice ) {
                        ret.value = theprice.labeled_value ? theprice.labeled_value : theprice.alternative_value;
                        ret.unit_value = theprice.unit_value;
                        ret.asterisk = theprice.asterisk;
                    }
                    return ret;
                })  
            }];

            e.product.productline.pricing_data.map( row => {
                var appendToPricing = {
                    itemno: ``,
                    appends: [],
                    desc: row.chargetypes.charge_name,
                    icon: row.chargetypes.icon,
                    note_value: row.note_value,
                    price: e.product.all_quantities.map( qty => {
                        var theprice = row.pvalues.find(p => p.quantity == qty );
                        var ret = { quantity: qty, value: e.product.product_line_helpers.not_applicable, asterisk: 0 };
                        if( theprice ) {
                            ret.value = theprice.labeled_value ? theprice.labeled_value : theprice.alternative_value;
                            ret.unit_value = theprice.unit_value;
                            ret.asterisk = theprice.asterisk;
                        }
                        return ret;
                    }) 
                };
                
                var percharges = [
                    { text: e.product.product_line_helpers.piece, value: row.per_piece, pvalue: row.per_piece_value },
                    { text: e.product.product_line_helpers.color, value: row.per_color, pvalue: row.per_color_value },
                    { text: e.product.product_line_helpers.side, value: row.per_side, pvalue: row.per_side_value },
                    { text: e.product.product_line_helpers.thousand, value: row.per_thousand, pvalue: row.per_thousand_value },
                    { text: e.product.product_line_helpers.item, value: row.per_item, pvalue: null }
                ]
                appendToPricing.appends = percharges.filter(x => x.value);
                pricingInit.push(appendToPricing);
            });


            return pricingInit;
        },
        productImprintsIterate() {

            var imprintCharges = this.product.productline.imprinttypes.filter( row => row.formatted_value ).map( row => {
                row.dupcount = this.product.productline.imprinttypes.filter(x => x.formatted_value == row.formatted_value).length
                return row;
            });

            return this.$_.uniqBy(this.$_.orderBy(imprintCharges, ['dupcount'], ['desc']), 'sortref').map( row => {
                var combinetitle = imprintCharges.filter( fimp => fimp.sortref == row.sortref).map( x => x.imprinttype.title).join(' / ');
                if( combinetitle ) {
                    row.textlabel = combinetitle;
                }
                return row;
            });

        },

        productSetupChargeIterate() {

            var setupcharges = [
                { text: this.product.product_line_helpers.color, value: this.product.productline.multicolor },
                { text: this.product.product_line_helpers.side, value: this.product.productline.second_side },
                { text: this.product.product_line_helpers.wrap, value: this.product.productline.wrap },
                { text: this.product.product_line_helpers.thousand, value: this.product.productline.per_thousand },
                { text: this.product.product_line_helpers.item, value: this.product.productline.per_item }
            ];

            var setupChargePer = [];
            if( this.product.productline.formatted_setup_charge) {
                setupChargePer = setupcharges.filter( row => row.value );
            }

            return setupChargePer

        },

        rowCounterDesktop() {
            var counter = 0;
            if( this.product.productline.formatted_setup_charge) { counter++; }
            counter+=this.productImprintsIterate.length;
            counter+=this.interatePricing.length;
            return counter+1;
        },

        routCounterMobile() {
            var counter = 0;
            if( this.product.productline.formatted_setup_charge) { counter++; }
            counter+=this.productImprintsIterate.length;
            counter+=this.product.all_quantities.length;
            return counter+1;
        },

        getComplianceArchive() {
            if( this.getVariationEntries && Array.isArray(this.product.productline.compliancesdata) && this.product.productline.compliancesdata.length ) {
                var links = this.product.productline.compliancesdata.filter( row => row.documentLink );
                return links;
            }
        },

        productSpecification() {
            if( !this.product.specificationIterate.length ) { return []; }
            const halfWay = Math.ceil(this.product.specificationIterate.length/2);
            const spec = this.product.specificationIterate.slice();
            const firstWay = [...spec].splice(0, halfWay);
            const secondWay = [...spec].slice(halfWay, spec.length)
            return {
                firstWay,
                secondWay,
                full: [...firstWay, ...secondWay]
            };
        },
        iteratePremiumBackgrounds() {
            let ret = [];
            this.product.productline.premiumbg.filter(row => row.premiumbg.collections.length).map(row => {
                ret = [...ret, ...row.premiumbg.collections];
            });
            return ret.slice(0, 4);
        },
        iterateStockShapes() {
            let ret = [];
            this.product.productline.stockshapes.filter(row => row.stockshapes.collections.length).map(row => {
                ret = [...ret, ...row.stockshapes.collections];
            });
            return ret.slice(0, 4);
        }
    },
    async mounted() {
        this.refreshRefs();
        await this.initVariation();
        await this.getProductCompare();
        this.isMounted = false;

        const e = this;
        document.getElementsByTagName("body")[0].onbeforeprint = function() {
            e.printPreload();
        };

        this.slickZoomSetter();
    },
    methods: {
        slickZoomSetter() {
            const e = this;
            jQuery('.zoomContainer').remove();
            var slickSelector = jQuery('.carousel-main-wrap .slick-active.slick-current [data-zoom-image]');
            slickSelector.elevateZoom({
                galleryActiveClass: 'elevatezoom-active',
                cursor: "crosshair",
                scrollZoom: false,
			    borderSize: 1,
                borderColour: '#e6e6e6',
                zoomWindowWidth: 390,
			    zoomWindowHeight: 411,
                zoomWindowOffetx: 50,
			    zoomWindowOffety: -15,
                zoomWindowFadeIn: 500,
			    zoomWindowFadeOut: 500,
                lensFadeIn: 500,
			    lensFadeOut: 500,
                onZoomedImageLoaded: function() {
                    jQuery(".zoomLens").bind('mousemove',function() {
                        if(!e.zoomelevated) {
                            e.pauseThumbnailsAutoplay();
                            e.zoomelevated = true;
                        }
                    });
    
                    jQuery(".zoomLens").bind('mouseout',function() {
                        if(e.zoomelevated) {
                            e.playThumbnailAutoplay();
                            e.zoomelevated = false;
                        }
                    });
                }
            })
        },
        zoomElevateRefresh() {
            this.slickZoomSetter();
        },
        printPreload() {
            jQuery('.lazyload, [loading="lazy"]').addClass('lazyloaded');
            jQuery('[loading="lazy"]').removeAttr('loading');
            jQuery('.product-accordion').addClass('open');
            jQuery('.product-accordion #content').show();
        },
        async getProductCompare() {
            try {
                this.pcompare.loading = true;
                const res = await api.get(`/public/getProduct`, {
                    params: {
                        category: this.product.cat_slug,
                        subcategory: this.product.sub_slug,
                        product: this.product.product_slug,
                        multiple: 1
                    }
                });
                this.pcompare.data = res.data;
                this.pcompare.loading = false;
            } catch($e) {
                this.pcompare.loading = false;
                return;
            }
        },
        pauseThumbnailsAutoplay() {
            const e = this;
            this.$nextTick(() => {
                e.$refs.maingallerythumb && e.$refs.maingallerythumb.pause();
            });
        },
        playThumbnailAutoplay() {
            const e = this;
            this.$nextTick(() => {
                e.$refs.maingallerythumb && e.$refs.maingallerythumb.play();
            });
        },
        pauseIdeaThumbnailsAutoplay() {
            const e = this;
            this.$nextTick(() => {
                e.$refs.ideathumb && e.$refs.ideathumb.pause();
            });
        },
        playIdeaThumbnailAutoplay() {
            const e = this;
            this.$nextTick(() => {
                e.$refs.ideathumb && e.$refs.ideathumb.play();
            });
        },
        refreshRefs() {
            const e = this;
            this.$nextTick(() => {
                // e.realtimeKey = Date.now();
                e.maingalleryNavFor = e.$refs.maingallerythumb;
                e.maingalleryThumbNavfor = e.$refs.maingallery;
                e.ideamaingalleryNavfor = e.$refs.ideamain;
                e.ideagalleryThumbNavfor = e.$refs.ideathumb;
                
                e.popupMainGalleryNav = e.$refs.galleryMainCarouselPopup;
                e.popupMainGalleryThumb = e.$refs.galleryMainCarouselPopupThumb;

            });
        },
        ideaprogressBarUpdate(prevIndex, nextIndex) {
            var countslickslides = jQuery('.carousel-idea-main.ideagallery .slick-slide:not(.slick-cloned)').length;
            var calc = ( (nextIndex) / (countslickslides-1) ) * 100;
            jQuery('.scrollbar-ideagallery-indicator').css({
                width: `${calc}%`
            });
        },
        popupGalleryprogressBarUpdate(prevIndex, nextIndex) {
            var countslickslides = jQuery('.carousel-idea-main.popupgallery .slick-slide:not(.slick-cloned)').length;
            var calc = ( (nextIndex) / (countslickslides-1) ) * 100;
            jQuery('.scrollbar-popgallery-indicator').css({
                width: `${calc}%`
            });
        },
        async setColor(color) {
            if( color === this.color ) { return; }
            this.color = color;
            const ret = await this.setVariation();
            if(ret) {
                this.realtimeKey = Date.now();
                this.refreshRefs();
            }
        },
        async  selectProductStockshape(value) {
            if( value === this.stockshape ) { return; }
            this.stockshape = value;
            const ret = await this.setVariation();
            if(ret) {
                this.realtimeKey = Date.now();
                this.refreshRefs();
            }
        },
        initVariation() {
            if( !this.product.variations ) { return false; }
            if( this.product.variations.key == 'color-stockshape' ) {
                this.color = this._currentVariant.thecolor.hid;
                this.stockshape = this._currentVariant.theshape.hid;
            }

            if( this.product.variations.key == 'color' ) {
                this.color = this._currentVariant.hid;
            }

            if( this.product.variations.key == 'stockshape' ) {
                this.stockshape = this._currentVariant.hid;
            }

            this.setVariation();
        },
        async setVariation() {
            if( this.product.variations.key == 'color-stockshape' ) {
                var selected = this.product.variations.variations.find(row => row.thecolor.hid == this.color && row.theshape.hid == this.stockshape);
                if( !selected ) { alert('Selected variation is not available'); return false; }
                this.variantLoading = true;
                const res = await api.get(`/public/product/variations?key=color-stockshape&id=${selected.hid}`);
                this.productVariant = res.data;
                this.variation = selected.slug;
                window.history.pushState(
                    {},
                    this.product.theProductComboTitle, 
                    `${this.product.productURL}${this.product.variations.router_slug}/${this.variation}`
                );
                this.variantLoading = false;
            }

            if( this.product.variations.key == 'color' ) {
                var selected = this.product.variations.variations.find(row => row.hid == this.color);
                if( !selected ) { alert('Selected variation is not available'); return false; }
                this.variantLoading = true;
                const res = await api.get(`/public/product/variations?key=color&id=${selected.hid}`);
                this.productVariant = res.data;
                this.variation = selected.slug;
                window.history.pushState(
                    {},
                    this.product.theProductComboTitle, 
                    `${this.product.productURL}${this.product.variations.router_slug}/${this.variation}`
                );
                this.variantLoading = false;
            }

            if( this.product.variations.key == 'stockshape' ) {
                var selected = this.product.variations.variations.find(row => row.hid == this.stockshape);
                if( !selected ) { alert('Selected variation is not available'); return false; }
                this.variantLoading = true;
                const res = await api.get(`/public/product/variations?key=stockshape&id=${selected.hid}`);
                this.productVariant = res.data;
                this.variation = selected.slug;
                window.history.pushState(
                    {},
                    this.product.theProductComboTitle, 
                    `${this.product.productURL}${this.product.variations.router_slug}/${this.variation}`
                );
                this.variantLoading = false;
            }

            this.zoomElevateRefresh();
            this.refreshRefs();
            return true;
        },
        openidegallerydefaultImage(index) {
            const e = this;
            this.$nextTick(() => {
                e.$refs.ideamain.goTo(index);
                jQuery('#ideagallerymodal').modal('show');
            });
        },
        openTemplateModal() {
            this.$nextTick(() => {
                jQuery('#producttemplatesmodal').modal('show');
            });
        },
        changeProduct() {
            var newproduct = this.pcompare.data.find( row => row.hid == this.comparedefault );
            if( newproduct ) {
                this.color = null
                this.stockshape = null
                this.productVariant = null
                this.variation = null;
                this.product = newproduct;
                this.initVariation();
                this.realtimeKey = Date.now();
                this.refreshRefs();
                window.history.pushState({},this.product.theProductComboTitle, this.product.productURL);
                document.title = this.product.theProductComboTitle;
                jQuery('#comparePrintMethod').modal('hide');
                this.zoomElevateRefresh();
            }
        },
        premiumBgModal(ishidemodal=false) {
            if(ishidemodal) {
                window.accordionModule.toggle(jQuery('#premiumbackground [data-accordion-module]'), 'close');
            } else {
                window.accordionModule.toggle(jQuery('#premiumbackground [data-accordion-module].first'), 'open');
            }
            jQuery('#premiumbackground').modal('toggle');
        },
        stockshapeModal(ishidemodal=false) {
            if(ishidemodal) {
                window.accordionModule.toggle(jQuery('#stockshapeModal [data-accordion-module]'), 'close');
            } else {
                window.accordionModule.toggle(jQuery('#stockshapeModal [data-accordion-module].first'), 'open');
            }
            jQuery('#stockshapeModal').modal('toggle');
        },
        ideaEmail(idea) {

            var bodycontent = `Hi, I have sent you an email for IDEA GALLERY. please check the link below. \n\n`;
            if(idea.usecurfile) {
                bodycontent += idea.image;
            } else {
                bodycontent += idea.downloadLink;
            }

            const emailParam = jQuery.param({
                subject: `${this.product.product_method_combination_name} - IDEA GALLERY EMAIL`,
                body: bodycontent
            });

            window.open(`mailto:?${emailParam}`);
        },
        popupGalleryEmail(img) {

            var bodycontent = `Hi, I have sent you an email for IMAGE. please check the link below. \n\n`;
            bodycontent += img.image;

            const emailParam = jQuery.param({
                subject: `${this.product.product_method_combination_name} - IMAGE EMAIL`,
                body: bodycontent
            });

            window.open(`mailto:?${emailParam}`);
        },
        templateEmail(template) {
            var bodycontent = `Hi, I have sent you an email for TEMPLATE. please check the link below. \n\n${template.link}`;

            const emailParam = jQuery.param({
                subject: `${this.product.product_method_combination_name} - TEMPLATE EMAIL`,
                body: bodycontent
            });

            window.open(`mailto:?${emailParam}`);
        },
        openMainGalleryPopup(index) {
            const e = this;
            this.$nextTick(() => {
                e.$refs.galleryMainCarouselPopup.goTo(index);
                jQuery('#maingallerymodal').modal('show');
            });
        }
    },
})