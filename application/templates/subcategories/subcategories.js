var inputs = {
    hid: null,
    sub_slug: '',
    sub_name: '',
    sub_description: '',
    priority: 1,
    index: null,
    seo_content: {
        description: ``,
        image: ``
    },
    categorize_as: null,
    banner_img: null,
    catalogs: [],
    sub_name_alt: null
};

var subCatInstanceVue = new Vue({
    el: '#subcategoriesControllerVue',
    mixins: [mixVue],
    data: function() {
        return {
            loadingCategory: false,
            loadingSubCategory: false,
            subcategories: [],
            categories: [],
            categoryId: category_ID,
            search: null,
            sort: 'asc',
            inactive: false,
            form: false,
            input: {...inputs},
            catalogdrag: false,
            printMethods: {
                loading: false,
                data: [],
                subcategoryId: null
            },
            categorizeLists: {
                loading: false,
                data: []
            },
            pagination: {
                page: 1,
                limit: 10,
                metas: {}
            }
        }
    },
    async mounted() {
        await this.getCategories();
        this.categoryId && await this.getSubcategories();
        the_ID && await this.hasID();
    },
    computed: {
        defaultValue() {
            return {...inputs};
        },
        selectedCategory() {
            const selected = this.categories.filter(row => row.hid === this.categoryId);
            if( selected.length )
            {
                return selected[0];
            }

            return null;
        },
        getPaginationCount() {
            return Math.ceil( this.pagination.metas.total/ this.pagination.limit );
        }
    },
    watch: {
        categoryId() {
            $route_change(["categoryId"], `categoryId=${this.categoryId}`);
            this.getSubcategories();
        }
    },
    methods: {
        async getCategories() {
            try {
                this.loadingCategory = true;
                var res = await api.get('/categories');
                this.categories = res.data.data;
                this.loadingCategory = false;
            } catch( $e ) {
                this.loadingCategory = false;
            }
        },
        async getSubcategories($args) {
            try {
                if( this.categoryId ) {
                    this.loadingSubCategory = true;
                    var res = await api.get('/subcategories', {
                        params: {
                            product_category_id: this.categoryId,
                            search: this.search,
                            orderBy: `priority ${this.sort}`,
                            inactive: this.inactive ? 1 : null,
                            pagination: true,
                            limit: this.pagination.limit,
                            page: this.pagination.page,
                            ...$args
                        }
                    });
                    this.subcategories = res.data.data;
                    this.pagination.metas = { ...res.data, data: null };
                    this.loadingSubCategory = false;
                }
            } catch($e) {
                this.loadingSubCategory=false
            }
        },
        async paginateSubCategories(page) {
            this.pagination.page = page;
            await this.getSubcategories();
        },
        async hasID() {
            try {
                var data = await api.get(`/subcategories`, {
                    params: {
                        id: the_ID
                    }
                })
                var res = data.data.data[0];
                var index = $indexer(this.subcategories, 'hid', res.hid);
                if(index>=0) {
                    this.formInputs(true, {...res, index, seo_content: this.inputJson(res.seo_content, 'seo_content')}, `_id=${res.hid}`);
                }
            } catch($e) { return; }
        },
        sorting() {
            this.sort = this.sort == 'asc' ? 'desc' : 'asc';
            this.getSubcategories();
        },
        async updateStatus( $id, $index, status ) {
            var confirm = await swal('Are you sure you want to remove this?', {
                buttons: true,
                dangerMode: true
            });
            if( confirm ) {
                try {
                    await this.showLoading();
                    var res = await api.put('/subcategories', {
                        id: $id,
                        active: status
                    });
                    this.subcategories.splice( $index, 1 );

                    if( status ) {
                        swal('Subcategory has been moved to active categories.', { icon: 'success' });
                    } else {
                        swal('Subcategory has been moved to inactive categories.', { icon: 'success' });
                    }
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        },
        toggleActive() {
            this.inactive=!this.inactive;
            this.getSubcategories();
        },
        async updateData() {
            try {
                await this.showLoading();
                const res = await api.put('/subcategories', {
                    ...this.input, 
                    id: this.input.hid, 
                    seo_content: this.jsonToString(this.input.seo_content),
                    catalogs: this.jsonToString(this.input.catalogs)
                });
                this.subcategories[this.input.index] = res.data;
                swal('Changes has been saved.', { icon: 'success' });
                this.formInputs(false, this.defaultValue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async insertData() {
            try {
                await this.showLoading();
                const res = await api.post('/subcategories', {
                    ...this.input, product_category_id: this.categoryId, 
                    seo_content: this.jsonToString(this.input.seo_content),
                    catalogs: this.jsonToString(this.input.catalogs)
                });
                this.subcategories.unshift(res.data);
                swal('New subcategory has been added.', { icon: 'success' });
                this.formInputs(false, this.defaultValue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async saveSubCategory() {
            var valid = await this.$validator.validate();
            if(!valid) return;

            if( this.input.index!=null ) {
                this.updateData();
            } else {
                this.insertData();
            }
        },
        formInputs(form = false, inputs = {...inputs, seo_content: inputs.seo_content}, appendUrl = "") {
            this.form = form;
            if( this.form ) { this.loadCategorizeLists(); }
            this.input = inputs;
            $route_change(["_id"], appendUrl);
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
        seoImage() {
            var $e = this;
            $e.chooseLibrary('Select SEO Image', function(e) {
                $e.input.seo_content.image = e;
            });
        },
        async loadCategorizeLists() {
            try {
                this.categorizeLists.loading = true;
                const res = await api.get(`/subcategories/categorize-lists?product_category_id=${this.categoryId}`);
                this.categorizeLists.data = res.data;
                this.categorizeLists.loading = false;
            } catch($e) {
                return;
            }
        },
        wpImage() {
            var $e = this;
            $e.chooseLibrary( 'Choose Category Banner Image', function( image_url ) {
                $e.input.banner_img = image_url;
            });
        },
        async selectCatalog(index) {
            var $e = this;
            $e.chooseLibrary( 'Choose Catalog', async function( url, obj ) {
                try {
                    await $e.showLoading('Generating PDF to Image...');
                    // generate pdf to image
                    var generateToImagePDF = await api.post(`/pdftoimage`, {
                        id: obj.id
                    });

                    $e.input.catalogs[index].catalog = url;
                    $e.input.catalogs[index].title = obj.title;
                    $e.input.catalogs[index].image = generateToImagePDF.data.image;
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
        catalogimageselect(index) {
            var $e = this;
            $e.chooseLibrary('Select SEO Image', function(url) {
                $e.$set($e.input.catalogs, index, {
                    ...$e.input.catalogs[index],
                    image: url
                })
            });
        },
        async autoassignCatalog() {

            var confirm = await swal(`Are you sure you want to auto assign catalog pages?`, {
                buttons: true,
                dangerMode: false
            });

            if( !confirm ) {
                return; 
            }

            try {
                await this.showLoading();
                await api.post(`/subcategories/catalog-assign`, {
                    categoryid: this.categoryId
                });
                await swal('Catalog has been automatically assigned.', { icon: 'success' });
                await this.getSubcategories();
            } catch($e) {
                return this.backEnd($e);
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
                    await api.delete(`/subcategories?id=${$id}`);
                    this.subcategories.splice( $index, 1 );
                    swal('Product has been completely removed.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        }
    }
});