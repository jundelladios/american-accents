
var selectedDefault = null;
if( inventoryJSVars.productOrMethod ) {
    var index = this.productlinejson.findIndex( row => row.method_slug === inventoryJSVars.productOrMethod );
    selectedDefault = this.productlinejson[index].hid;
}

new Vue({
    el: `#subcategoryVueController`,
    template: htmlTemplate,
    data() {
        return {
            selected: selectedDefault,
            sizes: {
                loading: false,
                data: []
            },
            thickness: [],
            products: {
                loading: true,
                data: [],
                metas: null
            },
            page: 1,
            plinejson: productlinejson.map((row, index) => {
                row.reactiveIndex = index;
                return row
            }),
            colordisplayLimit: 5,
            mobilecolordisplayLimit: 3
        }
    },
    mixins: [
        frontMixins
    ],
    watch: {
        selected(val) {
            if( val!= null ) {
                this.page = 1;
                this.products.loading = false;
                this.products.data = [];
                this.getPrintMethod();
            }
        },
        getSelectedSizes() {
            this.page = 1;
            this.products.loading = false;
            this.products.data = [];
            this.loadProducts();
        },
        getSelectedThickness() {
            this.page = 1;
            this.products.loading = false;
            this.products.data = [];
            this.loadProducts();
        },
        isMobile() {
            this.selected = null;
        }
    },
    components: {
        VueSlickCarousel: window['vue-slick-carousel']
    },
    computed: {
        getdefaultpline() {
            return this.plinejson.first();
        },
        getBanners() {
            return this.plinewithaccordionstatus.filter( row => row.banner_img);
        },
        plinewithaccordionstatus() {
            const e = this;
            return e.plinejson.map( row => {
                row.opened_accordion = e.selected != null && e.selected === row.hid ? true : false;
                row.opened_accordion_class = row.opened_accordion ? 'active' : '';
                return row;
            });
        },
        getArrangedData() {
            return [...this.plinewithaccordionstatus].sort((x,y) => {
                return y.opened_accordion - x.opened_accordion;
            });
        },
        getSelectedJson() {
            return this.selected && this.plinejson.filter( row => row.hid === this.selected )[0];
        },
        getSelectedSizes() {
            return this.sizes.data.filter(row => row.selected).map(row => row.product_size_details).join(',');
        },
        getSelectedThickness() {
            return this.thickness.filter(row => row.selected).map(row => row.product_tickness_details).join(',');
        },
        paginate() {
            if( this.isMobile ) {
                return 16;
            }
            return 15;
        },
        getProductsIterate() {
            return this.products.data.map( row => {
                if( row.price.min === row.price.max ) {
                    row.priceFormatted = row.price.formatted_min;
                } else {
                    row.priceFormatted = `${row.price.formatted_min} - ${row.price.formatted_max}`;
                }
                return row;
            });
        },
        jsvars() {
            return inventoryJSVars;
        },
        plinevar() {
            return plinevar;
        }
    },
    methods: {
        async getPrintMethod() {
            if( this.selected != null ) {
                this.changeSlideByIndex();
                await !this.sizes.loading && this.getSizes();
                await this.loadProducts();
                const $e = this;
                const listElem = document.querySelector(`.infiniteScroll[data-pline-id="${this.selected}"]`);
                listElem && listElem.addEventListener('scroll', async e => {
                    if(listElem.scrollTop + listElem.clientHeight >= listElem.scrollHeight) {
                        await $e.loadProducts();
                    }
                });
            } else {
                const listElem = document.querySelector(`.infiniteScroll`);
                listElem && listElem.removeEventListener('scroll');
            }
        },
        async loadProducts() {
            !(this.products.loading || !this.page) && await this.getProducts();
        },
        initCarousel() {
            this.$nextTick(() => {
                this.getPrintMethod();
            });
        },
        changeSlideByIndex() {
            var indexItem = jQuery(`.productLinesModule .pline-banner-wrap [data-pline-id="${this.selected}"]`).parent().closest('.slick-slide').data('index');
            indexItem >= 0 && this.$refs.bannerslick && this.$refs.bannerslick.goTo(indexItem);
        },
        setSelected(val) {
            const e = this;
            jQuery('html, body').animate({
                scrollTop: jQuery('#topHeaderScroll').offset().top - 50
            })
            .promise()
            .done( function() {
                setTimeout( function() {
                    e.selected = val;
                }, 200);
            });
        },
        async getProducts() {
            try {
                this.products.loading = true;
                var res = await api.get(`/public/getProducts`, {
                    params: {
                        thickness: this.getSelectedThickness,
                        size: this.getSelectedSizes, 
                        page: this.page, 
                        paginate: this.paginate,
                        printmethod: this.getSelectedJson.method_slug,
                        category: inventoryJSVars.category,
                        subcategory: inventoryJSVars.subcategory,
                        orderBy: 'products.product_size asc, products.product_thickness asc'
                    }
                });
                this.products.loading = false;
                this.products.data = [...this.products.data, ...res.data.data];
                this.products.metas = {...res.data, data: null};
                this.page = this.setNextPage(res.data.next_page_url);
            } catch($e) {
                this.products.loading = false;
                return;
            }
        },
        async getSizes() {
            try {
                this.sizes.loading = true;
                const res = await api.get(`/public/filter/getSizes`, {
                    params: {
                        category: inventoryJSVars.category,
                        subcategory: inventoryJSVars.subcategory,
                        method: this.getSelectedJson.method_slug
                    }
                });
                this.sizes.data = res.data.map( row => {
                    row.selected = false;
                    return row;
                });

                // include thickness
                const thickres = await api.get(`/public/filter/getThickness`, {
                    params: {
                        category: inventoryJSVars.category,
                        subcategory: inventoryJSVars.subcategory,
                        method: this.getSelectedJson.method_slug
                    }
                });
                this.thickness = thickres.data.map( row => {
                    row.selected = false;
                    return row;
                });

                this.sizes.loading = false;
            } catch($e) {
                this.sizes.loading = false;
                return;
            }
        },
        updateSlickSlideProduct(param) {
            var e = this;
            var selector = `[data-product-index="${param.product}"][data-color-index="${param.colorindex}"]`;
            var theslideIndex = jQuery(selector)
            .parent()
            .closest('[data-index]')
            .data('index');
            e.$refs[param.ref][0].goTo(theslideIndex);
        },
        updateProductColorData(param) {
            var colorIndex = jQuery(`[data-product-index="${param.product}"]`)
            .parent()
            .closest(`[data-index="${param.slideIndex}"]`)
            .children()
            .find('[data-color-index]')
            .data('color-index');

            var selectedcolor = this.products.data[param.product].availablecolors[colorIndex];
            this.$set( this.products.data, param.product, {
                ...this.products.data[param.product],
                selectedcolor: selectedcolor.slug,
                newURL: this.products.data[param.product].productURL+'?color='+selectedcolor.slug
            });
        },
        initProductCarousel(index) {
            this.$nextTick(() => {
                var selectedcolor = this.products.data[index].availablecolors[0];
                this.$set( this.products.data, index, {
                    ...this.products.data[index],
                    selectedcolor: selectedcolor.slug,
                    newURL: this.products.data[index].productURL+'?color='+selectedcolor.slug
                });
            });
        }
    },
});