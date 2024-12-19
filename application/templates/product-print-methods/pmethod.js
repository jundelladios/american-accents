var inputs = {
    product_id: productID,
    product_line_id: null,
    description: null,
    min_desc: null,
    min_value: null,
    //features_options: [],
    features_options2: null,
    disclaimer: null,
    // pdf files
    downloads: [
        {
            title: ``,
            preview: ``,
            link: ``
        }
    ],
    // blank images for virtual editor
    templates: [
        {
            title: ``,
            image: ``
        }
    ],
    feature_img: {
        title: ``,
        image: ``,
        top: null
    },
    showcase_img: {
        title: ``,
        image: ``
    },
    images: [
        {
            text: ``,
            image: ``,
            downloadLink: ``,
            usecurfile: false
        }
    ],
    index: null,
    priority: 1,
    seo_content: {
        description: ``,
        image: ``
    },
    package_count_min: 25,
    package_count_max: 25,
    package_count_as: null,
    imprint_width: null,
    imprint_height: null,
    imprint_bleed_wrap_width: null,
    imprint_bleed_wrap_height: null,
    allow_print_method_prefix: 1,
    imprint_as: null,
    imprint_bleed_as: null,
    shape: null,
    specs_json: [],
    spec_copy: [],
    specification_id: null,
    specification_output_id: null,
    keywords: null
}


const priceInputs = {
    alternative_value: null,
    quantity: null,
    value: null,
    index: null,
    unit_value: null,
    decimal_value: 3,
    show_currency: 0,
    hid: null
}

var productsInstanceVue = new Vue({
    el: '#productsPrintMethodControllerVue',
    mixins: [mixVue, printMethodColors, productStockShapes, specificationVue, printMethodColorStockshape],
    data() { return {
        product: {
            loading: false,
            data: null
        },
        plines: {
            loading: false,
            data: []
        },
        combos: {
            loading: false,
            data: []
        },
        inactive: false,
        sort: 'asc',
        inputs: {...inputs},
        form: false,
        priceInputs: {...priceInputs}
    } },
    async mounted() {
        await this.loadProduct();
        await this.loadProductCombos();
        await this.loadProductLines();
        //the_ID && await this.hasID();
    },
    computed: {
        pcgcountoptions() {
            return [
                { "text": "Bulk", "value": "bulk" },
                { "text": "Individual", "value": "individual"}
            ]
        },
        defaultValue() {
            return {...inputs};
        },
        defaultpriceInputs() {
            return {...priceInputs}
        },
        inputParamsApi() {
            return {
                ...this.inputs,
                downloads: this.jsonToString(this.inputs.downloads.filter( row => row.link && (row.preview || row.title) )),
                templates: this.jsonToString(this.inputs.templates.filter( row => row.image )),
                //features_options: this.jsonToString(this.inputs.features_options.filter( row => row )),
                images: this.jsonToString(this.inputs.images.filter( row => row.image )),
                feature_img: this.jsonToString(this.inputs.feature_img),
                showcase_img: this.jsonToString(this.inputs.showcase_img),
                seo_content: this.jsonToString(this.inputs.seo_content),
                specs_json: this.apiSpecJSON
            }
        },
        getInputBreakdown() {

            let quantities = [];

            if( this.inputs.hid && this.inputs.productline.pricing_data ) {

                this.inputs.productline.pricing_data.map(row => {

                    q = row.pvalues.map( q => q.quantity );
                    quantities = [...quantities, ...q];

                });

                return Array.from(new Set(quantities));

            }

            return [];
        }
    },
    methods: {
        async loadProduct() {
            try {
                this.product.loading = true;
                const res = await api.get(`/products?id=${productID}`);
                this.product.data = res.data.data[0];
                this.product.loading = false;
            } catch($e) {
                this.product.loading = false;
                return;
            }
        },
        async loadProductLines() {
            try {
                this.plines.loading = true;
                const res = await api.get(`/product-lines?product_subcategory_id=${this.product.data.subcategory.hid}`);
                this.plines.data = res.data.data;
                this.plines.loading = false;
            } catch($e) {
                this.plines.loading = false;
                return;
            }
        },
        async loadProductCombos() {
            try {
                this.combos.loading = true;
                const res = await api.get(`/products-combo`, {
                    params: {
                        product_id: this.product.data.hid,
                        orderBy: `priority ${this.sort}`,
                        inactive: this.inactive ? 1 : null
                    }
                });
                this.combos.data = res.data.data;
                this.combos.loading = false;
            } catch($e) {
                this.combos.loading = false;
                return;
            }
        },
        // async hasID() {
        //     try {
        //         const data = await api.get(`/products-combo`,{
        //             params: {
        //                 id: the_ID
        //             }
        //         });
        //         var res = data.data.data[0];
        //         var index = $indexer(this.combos.data, 'hid', res.hid);
        //         if(index>=0) {
        //             this.$resSetter({...res, index});
        //         }
        //     } catch($e) { return; }
        // },
        $resSetter($res) {
            //const features_options = this.inputJson($res.features_options, 'features_options');
            const downloads = this.inputJson($res.downloads, 'downloads');
            const templates = this.inputJson($res.templates, 'templates');
            const images = this.inputJson($res.images, 'images');

            const feature_img = this.inputJson($res.feature_img, 'feature_img');
            const showcase_img = this.inputJson($res.showcase_img, 'showcase_img');
            const seo_content = this.inputJson($res.seo_content, 'seo_content');

            var index = $indexer(this.combos.data, 'hid', $res.hid);

            this.formInputs(true, {
                ...$res,
                index,
                feature_img,
                showcase_img,
                //features_options,
                downloads, 
                id: $res.hid, 
                templates,
                images,
                seo_content,
                specs_json: [...$res.specification],
                spec_copy: [...$res.specification],
                specification_id: $res.spechandler ? $res.spechandler.hid : null,
                specification_output_id: $res.spechandleroutput ? $res.spechandleroutput.hid : null,
                product_line_id: $res.productline ? $res.productline.id : null,
            }, `_id=${$res.hid}`);
        },
        toggleActive() {
            this.inactive=!this.inactive;
            this.loadProductCombos();
        },
        addNew() {
            this.formInputs(true, {...inputs});
        },
        sorting() {
            this.sort = this.sort == 'asc' ? 'desc' : 'asc';
            this.loadProductCombos();
        },
        async updateStatus( $id, $index, status ) {
            var confirm = await swal('Are you sure you want to remove this?', {
                buttons: true,
                dangerMode: true
            });
            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.put('/products-combo', {
                        id: $id,
                        active: status
                    });
                    this.combos.data.splice( $index, 1 );
                    if( status ) {
                        swal('Product Combo has been moved to active.', { icon: 'success' });
                    } else {
                        swal('Product Combo has been moved to inactive.', { icon: 'success' });
                    }
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        },
        async forceDelete( $id, $index ) {
            var confirm = await swal(this.langs.removeNote, {
                buttons: true,
                dangerMode: true
            });

            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.delete(`/products-combo?id=${$id}`);
                    this.combos.data.splice( $index, 1 );
                    swal('Product Combo has been completely removed.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        },
        formInputs(form = false, inputs = {...inputs, seo_content: inputs.seo_content}, appendUrl = "") {
            this.form = form;
            this.inputs = inputs;
            $route_change(["_id"], appendUrl);
            this.fixValidator();
            if(!form) { accordioJS.state = null; }
        },
        inputJson($data, $json) {
            if(!$data || $data==null) return inputs[$json];
            try {
                return JSON.parse($data);
            } catch($e) {
                return inputs[$json];
            }
        },
        resToJson($data) {
            try {
                return JSON.parse($data);
            } catch($e) {
                return null;
            }
        },
        jsonToString($json) {
            return JSON.stringify($json);
        },
        chooseFeatureImage() {
            var $e = this;
            this.chooseLibrary( `Product Combination Image`, (url, obj) => {
                $e.inputs.feature_img.image = url;
                $e.inputs.feature_img.title = obj.title;
            });
        },
        chooseDescriptionImage() {
            var $e = this;
            this.chooseLibrary( `Product Description Image`, url => {
                $e.inputs.showcase_img.image = url;
            });
        },
        selectImageSlides(index) {
            var $e = this;
            this.chooseLibrary( `Choose Image`, url => {
                $e.inputs.images[index].image = url;
            });
        },
        selectDownloadPhoto(index) {
            var $e = this;
            this.chooseLibrary( `Choose Download Preview`, (url) => {
                $e.inputs.downloads[index].preview = url;
            });
        },
        removeDownloadPhoto(index) {
            this.inputs.downloads[index].preview = null;
        },
        selectDownloadLink(index) {
            var $e = this;
            this.chooseLibrary( `Choose Download File`, (url, obj) => {
                $e.inputs.downloads[index].title = obj.title;
                $e.inputs.downloads[index].link = url;
            });
        },
        removeDownload(index) {
            this.inputs.downloads.splice(index, 1);
        },
        addDownload() {
            this.inputs.downloads.push({
                title: ``,
                preview: ``,
                link: ``
            });
        },
        selectImageTemplate(index) {
            var $e = this;
            this.chooseLibrary( `Choose Image`, (url) => {
                $e.inputs.templates[index].image = url;
            });
        },
        async saveProductCombo() {
            var valid = await this.$validator.validateAll('product');
            if(!valid) return;

            if( this.inputs.hid ) {
                this.updateData();
            } else {
                this.insertData();
            }
        },
        async insertData() {
            try {
                await this.showLoading();
                const res = await api.post(`/products-combo`, { 
                    ...this.inputParamsApi
                });
                swal('New Product Combo has been added.', { icon: 'success' });
                this.combos.data.push(res.data);
                this.$resSetter(res.data);
                jQuery(".aa-popup").animate({ scrollTop: 0 }, "fast");
                accordioJS.navigate(jQuery('[data-target="#information"]'));
            } catch($e) {
                this.backEnd($e);
            }
        },
        async updateData() {
            try {
                await this.showLoading();
                const res = await api.put(`/products-combo`, { 
                    ...this.inputParamsApi,
                    id: this.inputs.hid
                });
                swal('Saved', { icon: 'success' });
                this.combos.data[this.inputs.index]  = res.data;
                this.$resSetter({...res.data, index: this.inputs.index});
                jQuery(".aa-popup").animate({ scrollTop: 0 }, "fast");
                accordioJS.navigate(jQuery('[data-target="#information"]'));
            } catch($e) {
                this.backEnd($e);
            }
        },

        async synchronizePricingSage() {
            try {
                await api.post(`/vdsitems/sync`, {
                    id: this.inputs.hid
                });
            } catch($e) {
                this.backEnd($e);
            }
        },

        async saveBreakdown() {
            var valid = await this.$validator.validateAll('breakdown');
            if(!valid) return;

            if( this.priceInputs.hid ) {
                await this.updateBreakdown();
            } else {
                await this.addBreakdown();
            }
            await this.showLoading('Synchronizing SAGE Product Pricings...');
            await this.synchronizePricingSage();
            swal('SAGE Products Pricing has been updated.', { icon: 'success' });
        },
        resetBreakdownInputs() {
            this.priceInputs = {...this.defaultpriceInputs};
            this.fixValidator();
        },
        async updateBreakdown() {
            try {
                await this.showLoading();
                const res = await api.put(`/pricing-data/values`, { 
                    ...this.priceInputs,
                    product_print_method_id: this.inputs.hid,
                    id: this.priceInputs.hid,
                    value: this.priceInputs.value ? this.priceInputs.value : null
                });
                swal('Pricing Breakdown has been saved.', { icon: 'success' });
                this.combos.data[this.inputs.index].price  = res.data.priceMinMax;
                this.inputs.pricings[this.priceInputs.index] = res.data;
                this.resetBreakdownInputs();
            } catch($e) {
                this.backEnd($e);
            }
        },
        async addBreakdown() {
            try {
                await this.showLoading();
                const res = await api.post(`/pricing-data/values`, { 
                    ...this.priceInputs,
                    product_print_method_id: this.inputs.hid,
                    value: this.priceInputs.value ? this.priceInputs.value : null
                });
                swal('Pricing Breakdown has been added.', { icon: 'success' });
                this.combos.data[this.inputs.index].price  = res.data.priceMinMax;
                this.inputs.pricings.push(res.data);
                this.resetBreakdownInputs();
            } catch($e) {
                this.backEnd($e);
            }
        },
        async removeBreakdown($id, $index) {
            var confirm = await swal('Are you sure you want to remove this breakdown?', {
                buttons: true,
                dangerMode: true
            });
            if(!confirm) { return; }
            try {
                await this.showLoading();
                const res = await api.delete(`/pricing-data/values?id=${$id}&showMinMax=1`);
                this.combos.data[this.inputs.index].price  = res.data;
                this.inputs.pricings.splice($index, 1);
                swal('Successfully removed.', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        seoImage() {
            var $e = this;
            $e.chooseLibrary('Select SEO Image', function(e) {
                $e.inputs.seo_content.image = e;
            });
        },
        selectDownloadFileImage(index) {
            var $e = this;
            this.chooseLibrary( `Select Downloadable File`, url => {
                $e.inputs.images[index].downloadLink = url;
            });
        }
    }
});