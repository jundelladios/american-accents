new Vue({
    el: `#searchVueController`,
    template: htmlTemplate,
    mixins: [
        frontMixins
    ],
    components: {
        VuePaginate: VuejsPaginate,
        VueSlickCarousel: window['vue-slick-carousel']
    },
    data() {
        return {
            filters: {
                loading: false,
                data: {
                    subcategories: [],
                    material: [],
                    sizes: [],
                    thickness: [],
                    colors: [],
                    methods: []
                }
            },
            colorsLoading: false,
            subcategoriesLoading: false,
            isRangeExec: false,
            isRangeReady: false,
            filterParams: {
                priceMin: 0,
                priceMax: 0,
                priceMinFormatted: null,
                priceMaxFormatted: null,
                orderBy: `product_method_combination_name ASC`,
                page: 1
            },
            storageKey: `filter-json`,
            products: {
                loading: true,
                data: [],
                metas: {}
            },
            colordisplayLimit: 5,
            openFilter: false
        }
    },
    async mounted() { if( !this.queryParam ) { return false; }
        const e = this;
        jQuery(window).on('click', function() {
            e.openFilter = false;
        });
        this.getFilters()
        .then(() => {
            e.rangeInput();
        });
        this.getSubcategoriesFilter();
        this.getColorsFilter();
        this.filterParams.page = 1;
        this.getProducts({ mounted: true });
    },
    watch: {
        'filterParams.orderBy': function() {
            this.filterParams.page = 1;
            this.getProducts();
        },
        // 'filters.data': {
        //     handler(val) {
        //         console.log('material');
        //     },
        //     deep: true
        // }
        'selectedFilterString': function() {
            this.filterParams.page=1;
            this.getProducts({page:1});
        },
        priceRangeWatcher: _.debounce( async function() {
            if(this.isRangeReady && !this.isRangeExec) { 
                this.filterParams.page=1;
                await this.getProducts({page:1});
            }
        }, 1000)
    },
    computed: {
        priceRangeWatcher() {
            return JSON.stringify({
                min: this.filterParams.priceMinFormatted,
                max: this.filterParams.priceMaxFormatted
            });
        },
        queryParam() {
            var url = new URL(window.location.href);
            return url.searchParams.get('q');
        },
        selectedFilterString() {
            return JSON.stringify(this.getSelectedFilters);
        },
        getSelectedFilters() {
            return {
                subcategories: this.filters.data ? this.filters.data.subcategories.filter( row => row.checked ) : [],
                materials: this.filters.data ? this.filters.data.material.filter( row => row.checked ) : [],
                sizes: this.filters.data ? this.filters.data.sizes.filter( row => row.checked ) : [],
                thickness: this.filters.data ? this.filters.data.thickness.filter( row => row.checked ) : [],
                colors: this.filters.data ? this.filters.data.colors.filter( row => row.checked ) : [],
                methods: this.filters.data ? this.filters.data.methods.filter( row => row.checked ) : []
            }
        },
        apiParams() {
            return {
                category: inventoryJSVars.category,
                subcategory: this.filters.data && this.filters.data.subcategories.filter( row => row.checked ).map( row => row.sub_slug ).join(','),
                material: this.filters.data && this.filters.data.material.filter( row => row.checked ).map( row => row.material_type ).join(','),
                size: this.filters.data && this.filters.data.sizes.filter( row => row.checked ).map( row => row.product_size_details ).join(','),
                thickness: this.filters.data && this.filters.data.thickness.filter( row => row.checked ).map( row => row.product_tickness_details ).join(','),
                color: this.filters.data && this.filters.data.colors.filter( row => row.checked ).map( row => row.slug ).join(','),
                printmethod: this.filters.data && this.filters.data.methods.filter( row => row.checked ).map( row => row.method_slug ).join(','),
                ...this.filterParams,
                q: this.queryParam,
                paginate: 15
            }
        },
        getSorting() {
            return orderBy;
        },
        getPaginationCount() {
            return Math.ceil( this.products.metas.total/ this.apiParams.paginate );
        },
        getApiRequest() {
            return apiRequest;
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
        }
    },
    methods: {
        async getFilters() {
            try {
                const e = this;
                e.filters.loading = true;
                const res = await api.get(`/public/getFilter`, {
                    params: {
                        q: this.queryParam
                    }
                });

                e.filters.data = {
                    ...res.data,
                    ...e.filters.data,
                    // subcategories: res.data.subcategories.map( row => {
                    //     row.checked = false;
                    //     return row;
                    // }),
                    material: res.data.material.map( row => {
                        row.checked = false;
                        return row;
                    }),
                    sizes: res.data.sizes.map( row => {
                        row.checked = false;
                        return row;
                    }),
                    thickness: res.data.thickness.map( row => {
                        row.checked = false;
                        return row;
                    }),
                    // colors: res.data.colors.map( row => {
                    //     row.checked = false;
                    //     return row;
                    // }),
                    methods: res.data.methods.map( row => {
                        row.checked = false;
                        return row;
                    })
                };

                e.filterParams.priceMin = res.data.range.min;
                e.filterParams.priceMax = res.data.range.max;
                e.filterParams.priceMinFormatted = `${e.getglobaljsvars.inventoryCurrency}${e.rangeValueSetter(e.filters.data.range.min)}`;
                e.filterParams.priceMaxFormatted = `${e.getglobaljsvars.inventoryCurrency}${e.rangeValueSetter(e.filters.data.range.max)}`;

                e.filters.loading = false;
            } catch($e) {
                this.filters.loading = false;
                return;
            }
        },
        async getColorsFilter() {
            try {
                const e = this;
                e.colorsLoading = true;
                const res = await api.get(`/public/filter/getColors`, {
                    params: {
                        q: this.queryParam
                    }
                });
                e.filters.data = {
                    ...e.filters.data,
                    colors: res.data.map( row => {
                        row.checked = false;
                        return row;
                    })
                }
                e.colorsLoading = false;
            } catch($e) {
                this.colorsLoading = false;
                return;
            }
        },
        async getSubcategoriesFilter() {
            try {
                const e = this;
                e.subcategoriesLoading = true;
                const res = await api.get(`/public/filter/getSubcategories`, {
                    params: {
                        q: this.queryParam
                    }
                });
                e.filters.data = {
                    ...e.filters.data,
                    subcategories: res.data.map( row => {
                        row.checked = false;
                        return row;
                    })
                }
                e.subcategoriesLoading = false;
            } catch($e) {
                this.subcategoriesLoading = false;
                return;
            }
        },
        async getProducts($args) {
            try {
                this.products.loading = true;
                const res = await api.get(`/public/getProducts`, {
                    params: {...this.apiParams, ...$args}
                });
                this.products.data = res.data.data;
                this.products.metas = { ...res.data, data: null };
                this.products.loading = false;
                !$args.mounted && this.refreshSlicks();
            } catch($e) {
                this.products.loading = false;
                return;
            }
        },
        rangeInput() {
            const e = this;
            var min = parseFloat(e.filters.data.range.min);
            var max = parseFloat(e.filters.data.range.max);
            e.filters.data.range.min && e.filters.data.range.max && jQuery(`.input-range .frange`).slider({
                range: true,
                min: min,
                max: max,
                values: [e.filters.data.range.min, e.filters.data.range.max],
                step: 0.001,
                slide: function(event, ui) {
                    e.isRangeExec = false;
                    e.filterParams.priceMin = ui.values[0];
                    e.filterParams.priceMax = ui.values[1];
                    e.filterParams.priceMinFormatted = `${e.getglobaljsvars.inventoryCurrency}${e.rangeValueSetter(ui.values[0])}`;
                    e.filterParams.priceMaxFormatted = `${e.getglobaljsvars.inventoryCurrency}${e.rangeValueSetter(ui.values[1])}`;
                    if(!e.isRangeReady) {
                        e.isRangeReady = true;
                    }
                }
            });
        },
        uncheckMaterialFilter(material) {
            var index = this.filters.data.material.findIndex( row => row.material_type === material.material_type );
            this.filters.data.material[index].checked = false;
            this.filterParams.page = 1;
            this.getProducts();
        },
        uncheckSubcategory(sub) {
            var index = this.filters.data.subcategories.findIndex( row => row.sub_slug === sub.sub_slug );
            this.filters.data.subcategories[index].checked = false;
            this.filterParams.page = 1;
            this.getProducts();
        },
        unchecSizeFilter(size) {
            var index = this.filters.data.sizes.findIndex( row => row.product_size_details === size.product_size_details );
            this.filters.data.sizes[index].checked = false;
            this.filterParams.page = 1;
            this.getProducts();
        },
        unchecThicknessFilter(thick) {
            var index = this.filters.data.thickness.findIndex( row => row.product_tickness_details === thick.product_tickness_details );
            this.filters.data.thickness[index].checked = false;
            this.filterParams.page = 1;
            this.getProducts();
        },
        unchecMethodFilter(method) {
            var index = this.filters.data.methods.findIndex( row => row.method_slug === method.method_slug );
            this.filters.data.methods[index].checked = false;
            this.filterParams.page = 1;
            this.getProducts();
        },
        async resetFilter() {
            this.isRangeExec = true;
            this.filters.data.subcategories = [...this.filters.data.subcategories].map( row => { row.checked = false; return row; });
            this.filters.data.material = [...this.filters.data.material].map( row => { row.checked = false; return row; });
            this.filters.data.sizes = [...this.filters.data.sizes].map( row => { row.checked = false; return row; });
            this.filters.data.thickness = [...this.filters.data.thickness].map( row => { row.checked = false; return row; });
            this.filters.data.methods = [...this.filters.data.methods].map( row => { row.checked = false; return row; });
            this.filters.data.colors = [...this.filters.data.colors].map( row => { row.checked = false; return row; });
            this.filterParams.page = 1;
            jQuery(`.input-range .frange`).slider('values', 0, this.filters.data.range.min);
            jQuery(`.input-range .frange`).slider('values', 1, this.filters.data.range.max);
            this.filterParams.priceMin = this.filters.data.range.min;
            this.filterParams.priceMax = this.filters.data.range.max;
            this.filterParams.priceMinFormatted = `${this.getglobaljsvars.inventoryCurrency}${this.rangeValueSetter(this.filters.data.range.min)}`;
            this.filterParams.priceMaxFormatted = `${this.getglobaljsvars.inventoryCurrency}${this.rangeValueSetter(this.filters.data.range.max)}`;
            await this.getProducts();
        },
        rangeValueSetter(value) {
            return parseFloat(value).toLocaleString();
        },
        paginateProducts(page) {
            const e = this;
            e.filterParams.page = page;
            scrollToElem.scroll(jQuery('#topcategory'), function() { 
                !e.products.loading && e.getProducts();
            });
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
    }
})