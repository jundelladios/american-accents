const inputs = {
    image: ``,
    features: [{ image: ``, text: `` }],
    price_tagline: null,
    index: null,
    print_method_id: null,
    banner_img: null,
    priority: 1,
    coupon_code_id: null,
    features_pivot: [{ image: ``, text: `` }],
    second_side: 0,
    wrap: 0,
    bleed: 0,
    multicolor: 1,
    process: 1,
    white_ink: 1,
    hotstamp: 1,
    per_thousand: 0,
    per_item: 1,
    setup_charge: 40,
    colors: [
        {
            type: ``, // Foil, Metalic Inc, Neon Inc, Standard, Product
            colors: [
                { 
                    hex: ``,
                    pantone: ``,
                    name: ``
                }
            ]
        }
    ],
    compliances: [
        {
            previewImage: ``,
            documentLink: ``,
            compliance: ``
        }
    ],
    pnotes: [''],
    pnotes2: null,
    seo_content: {
        description: ``,
        image: ``
    }
};


const colorInput = {
    type: ``, // Foil, Metalic Inc, Neon Inc, Standard, Product
    colors: [
        { 
            hex: ``,
            pantone: ``,
            name: ``
        }
    ]
}

var subCatMethodsInstance = new Vue({
    el: '#productLinesController',
    mixins: [mixVue, imprintProductLine, premiumBackgroundsVue, stockShapeVue],
    data: function() {
        return {
            tree: {
                loading: false,
                data: null
            },
            methods: {
                loading: false,
                data: []
            },
            coupons: {
                loading: false,
                data: []
            },
            inputs: null,
            form: false,
            subcatMethods: {
                loading: false,
                data: []
            },
            inactive: false,
            sort: 'asc',
            charges: {
                modal: false,
                data: [],
                step: 1,
                forms: [],
                loading: false
            },
            colors: {
                plineindex: null,
                index: null,
                data: [],
                loading: false,
                input: {
                    priority: 1,
                    title: null,
                    color_collection_id: null,
                    index: null
                }
            }
        }
    },
    computed: {
        colorspline() {
            return this.methods.data[this.colors.plineindex];
        },
        subcategory() {
            if( this.tree.data && this.tree.data.subcategories.length ) {
                return this.tree.data.subcategories[0];
            }
            return null;
        },
        defaultValue() {
            return {...inputs, product_subcategory_id: _IDS.subcategory};
        },
        plinecolors() {
            return this.inputs.colors && this.inputs.colors.map(row => {
                row.valid = row.type ? !!!row.colors.filter(col => !col.hex || !col.pantone || !col.name ).length : false;
                return row;
            });
        },
        apiParams() {
            const getnotes = this.inputs.pnotes.filter(row => row!="");
            return {
                ...this.inputs, 
                features: this.jsonToString(this.inputs.features),
                features_pivot: this.jsonToString(this.inputs.features_pivot),
                colors: window.CircularJSON.stringify(this.inputs.colors),
                compliances: this.jsonToString(this.inputs.compliances.filter(row => row.compliance!="")),
                pnotes: getnotes.length ? this.jsonToString(getnotes) : null,
                seo_content: this.jsonToString(this.inputs.seo_content)
            }
        }
    },
    async mounted() {
        await this.getCategoryTree();
        await this.getMethods();
        await this.getCoupons();
        await this.getProductLines();
        the_ID && await this.hasID();
        await this.getColorCollections();
    },
    methods: {
        async getColorCollections() {
            try {
                this.colors.loading = true;
                const res = await api.get(`/colors`);
                this.colors.data = res.data.data;
                this.colors.loading = false;
            } catch($e) {
                this.colors.loading = false;
                return;
            }
        },
        async getProductLines() {
            try {
                this.subcatMethods.loading = true;
                const res = await api.get(`/product-lines`, {
                    params: {
                        orderBy: `priority ${this.sort}`,
                        inactive: this.inactive ? 1 : null,
                        product_subcategory_id: _IDS.subcategory,
                        admin: 1
                    }
                });
                this.subcatMethods.data = res.data.data;
                this.subcatMethods.loading = false;
            } catch($e) {
                this.subcatMethods.loading = false;
                return;
            }
        },
        async getCategoryTree() {
            try {
                this.tree.loading = true;
                const res = await api.get(`/categories?tree=1&id=${_IDS.category}&subcatId=${_IDS.subcategory}`);
                this.tree.data = res.data[0];
                this.tree.loading = false;
            } catch($e) {
                this.tree.loading = false;
                return;
            }
        },
        async getMethods() {
            try {
                this.methods.loading = true;
                const res = await api.get(`/print-methods`);
                this.methods.data = res.data.data;
                this.methods.loading = false;
            } catch($e) {
                this.methods.loading = false;
                return;
            }
        },
        async getCoupons() {
            try {
                this.coupons.loading = true;
                const res = await api.get(`/coupon-codes`);
                this.coupons.data = res.data.data;
                this.coupons.loading = false;
            } catch($e) {
                this.coupons.loading = false;
                return;
            }
        },
        async hasID() {
            try {
                const data = await api.get(`/product-lines`,{
                    params: {
                        id: the_ID
                    }
                });
                var res = data.data.data[0];
                var index = $indexer(this.subcatMethods.data, 'hid', res.hid);
                if(index>=0) {

                    const features = this.inputJson(res.features, 'features');
                    const features_pivot = this.inputJson(res.features_pivot, 'features_pivot');
                    const colors = this.inputJson(res.colors, 'colors');
                    const compliances = this.inputJson(res.compliances, 'compliances');
                    const pnotes = this.inputJson(res.pnotes, 'pnotes')
                    const seo_content = this.inputJson(res.seo_content, 'seo_content');

                    this.formInputs(true, {
                        index
                        ,...res, 
                        features:features, 
                        features_pivot, 
                        id: res.hid, 
                        print_method_id: res.printmethod.hid,
                        colors,
                        compliances,
                        coupon_code_id: res.couponcode ? res.couponcode.hid : null,
                        pnotes,
                        seo_content
                    }, `_id=${res.hid}`);

                }
            } catch($e) { return; }
        },
        async removeColorSection($index, $cindex) {
            var confirm = await swal('Are you sure you want to remove this color?', {
                buttons: true,
                dangerMode: true
            });
            if(!confirm) return;
            this.inputs.colors[$index].colors.splice($cindex, 1);
        },
        async removeSection($index) {
            var confirm = await swal('Are you sure you want to remove this color section?', {
                buttons: true,
                dangerMode: true
            });
            if(!confirm) return;
            this.inputs.colors.splice($index, 1);
        },
        addColorSection() {
            this.inputs.colors.push({
                type: ``, // Foil, Metalic Inc, Neon Inc, Standard, Product
                colors: [
                    { 
                        hex: ``,
                        pantone: ``,
                        name: ``
                    }
                ]
            });
        },
        addColor($index) {
            this.inputs.colors[$index].colors.push({...inputs.colors[0]});
        },
        addNew() {
            this.formInputs(true, {...this.defaultValue, features: [{ image: ``, text: `` }], colors: inputs.colors});
        },
        wpImage() {
            var $e = this;
            this.chooseLibrary( `Product Line Image`, url => {
                $e.inputs.image = url;
            });
        },
        wpImageFeature($index) {
            var $e = this;
            this.chooseLibrary( `Product Line Feature Image`, url => {
                $e.inputs.features[$index].image = url;
            });
        },
        wpImageFeaturePivot($index) {
            var $e = this;
            this.chooseLibrary( `Feature Pivot`, url => {
                $e.inputs.features_pivot[$index].image = url;
            });
        },
        // setJsonValue(val, key) {
        //     if( val ) {
        //         this.inputs[key] = inputs[key];
        //     } else {
        //         this.inputs[key] = null;
        //     }
        // },
        async updateData() {
            try {
                await this.showLoading();
                const res = await api.put('/product-lines', this.apiParams);
                this.subcatMethods.data[this.inputs.index] = res.data;
                swal('Changes has been saved.', { icon: 'success' });
                this.formInputs(false, this.defaultValue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async insertData() {
            try {
                await this.showLoading();
                const res = await api.post('/product-lines', this.apiParams);
                this.subcatMethods.data.unshift(res.data);
                swal('New product line has been added.', { icon: 'success' });
                this.formInputs(false, this.defaultValue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async saveSubCategoryMethod() {
            var valid = await this.$validator.validate();
            if(!valid) return;

            if( this.inputs.index!=null ) {
                this.updateData();
            } else {
                this.insertData();
            }
        },
        formInputs(form = false, inputs = {...inputs, seo_content: inputs.seo_content}, appendUrl = "") {
            this.form = form;
            this.inputs = inputs;
            $route_change(["_id"], appendUrl);
            accordioJS.state = null;
            this.fixValidator();
        },
        inputJson($data, $json) {
            if(!$data || $data==null) return inputs[$json];
            try {
                return JSON.parse($data);
            } catch($e) {
                return inputs[$json];
            }
        },
        jsonToString($json) {
            return JSON.stringify($json);
        },
        async updateStatus( $id, $index, status ) {
            var confirm = await swal('Are you sure you want to remove this?', {
                buttons: true,
                dangerMode: true
            });
            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.put('/product-lines', {
                        id: $id,
                        active: status
                    });
                    this.subcatMethods.data.splice( $index, 1 );
                    if( status ) {
                        swal('Product line has been moved to active.', { icon: 'success' });
                    } else {
                        swal('Product line has been moved to inactive.', { icon: 'success' });
                    }
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        },
        toggleActive() {
            this.inactive=!this.inactive;
            this.getProductLines();
        },
        sorting() {
            this.sort = this.sort == 'asc' ? 'desc' : 'asc';
            this.getProductLines();
        },
        addCompliances() {
            this.inputs.compliances.push( {
                previewImage: ``,
                documentLink: ``,
                compliance: ``
            } );
        },
        removeCompliances($index) {
            this.inputs.compliances.splice($index, 1);
        },
        async selectDocument($index) {
            var $e = this;
            this.chooseLibrary( `Choose Document`, async (url, obj) => {
                try {
                    await $e.showLoading('Generating PDF to Image...');
                    // generate pdf to image
                    var generateToImagePDF = await api.post(`/pdftoimage`, {
                        id: obj.id
                    });

                    $e.inputs.compliances[$index].documentLink = url;
                    $e.inputs.compliances[$index].compliance = obj.title;
                    $e.inputs.compliances[$index].previewImage = generateToImagePDF.data.image;
                    await swal.close();
                } catch($err) {
                    $e.backEnd($err);
                }

            }, {
                library: {
                    type: ['application/pdf']
                }
            });
        },
        selectPreviewDocument($index) {
            var $e = this;
            this.chooseLibrary( `Choose Document Preview`, (url) => {
                $e.inputs.compliances[$index].previewImage = url;
            });
        },
        removePreview($index) {
            this.inputs.compliances[$index].previewImage = null;
        },
        requiredChecker($lists) {
            var responses = [];
            for(var key in this.inputs) {
                if( $lists.includes(key) && (this.inputs[key] == null || this.inputs[key] == '') ) {
                    responses.push(key);
                }
            }
            return responses.length ? false : true;
        },
        setCharge($pline) {
            chargesInstance.setChargeState({
                pline: {...$pline, subcat: this.subcategory}
            });
        },
        addNote() {
            this.inputs.pnotes.push('');
        },
        removeNote($index) {
            this.inputs.pnotes.splice($index, 1);
        },
        seoImage() {
            var $e = this;
            $e.chooseLibrary('Select SEO Image', function(e) {
                $e.inputs.seo_content.image = e;
            });
        },
        collapseColors(index) {
            jQuery(`[data-form-collapse="collapse-${index}"]`).slideToggle();
        },
        wpImageBanner() {
            var $e = this;
            this.chooseLibrary( `Product Line Banner`, url => {
                $e.inputs.banner_img = url;
            });
        },



        // pline colors
        resetInputplinecolor() {
            this.colors.input = {
                priority: 1,
                title: null,
                color_collection_id: null,
                index: null
            }

            this.fixValidator();
        },
        setEditplineColor(data, index) {
            this.colors.input = {
                priority: data.priority,
                title: data.title,
                color_collection_id: data.color_collection_id_hash,
                index,
                id: data.hid
            }
        },
        async plineColorsSave() {
            var valid = await this.$validator.validate();
            if(!valid) return;

            if( !this.colors.input.id ) {
                await this.plinecoloradd();
            } else {
                await this.plinecolorupdate();
            }
        },
        async plinecoloradd() {
            try {
                await this.showLoading();
                const res = await api.post('/product-line-colors', {
                    ...this.colors.input, 
                    product_line_id: this.subcatMethods.data[this.colors.plineindex].hid
                });
                swal('New product line color collection has been added.', { icon: 'success' });
                this.subcatMethods.data[this.colors.plineindex].plinecolors = res.data.plinecolors;
                this.resetInputplinecolor();
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async plinecolorupdate() {
            try {
                await this.showLoading();
                const res = await api.put('/product-line-colors', {
                    ...this.colors.input, 
                    product_line_id: this.subcatMethods.data[this.colors.plineindex].hid
                });
                swal('Product line color collection has been updated.', { icon: 'success' });
                this.subcatMethods.data[this.colors.plineindex].plinecolors = res.data.plinecolors;
                this.resetInputplinecolor();
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async plineColorsDelete(index, id) {
            var confirm = await swal('Are you sure you want to remove this color collection?', {
                buttons: true,
                dangerMode: true
            });
            var e = this;
            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.delete(`/product-line-colors?id=${id}`);
                    e.subcatMethods.data[e.colors.plineindex].plinecolors.splice(index, 1);
                    swal('Removed', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        },
        async forceDeleteProductLine( $id, $index ) {
            var confirm = await swal(this.langs.removeNote, {
                buttons: true,
                dangerMode: true
            });

            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.delete(`/product-lines?id=${$id}`);
                    this.subcatMethods.data.splice( $index, 1 );
                    swal('Productline has been completely removed.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        }
    }
});