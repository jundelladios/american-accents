var inputs = {
    hid: null,
    cat_slug: '',
    cat_name: '',
    notes: '',
    priority: 1,
    category_banner: '',
    category_banner_content: '',
    index: null,
    template_section: 1,
    bannerlist: [],
    seo_content: {
        description: ``,
        image: ``
    }
};

var categoryinstanceVue = new Vue({
    el: '#categoriesControllerVue',
    mixins: [mixVue],
    data: function() {
        return {
            loading: false,
            categories: [],
            pagination: {
                page: 1,
                limit: 10,
                metas: {}
            },
            search: null,
            sort: 'asc',
            form: false,
            input: {...inputs},
            inactive: false,
            dragGrid: false,
        }
    },
    computed: {
        defaultValue() {
            return {...inputs};
        },
        getPaginationCount() {
            return Math.ceil( this.pagination.metas.total/ this.pagination.limit );
        }
    },
    async mounted() {
        await this.init();
        the_ID && await this.hasID();
    },
    methods: {
        async init($args) {
            try {
                this.loading = true;
                var res = await api.get(`/categories`, {
                    params: { 
                        search: this.search, 
                        orderBy: `priority ${this.sort}`, 
                        inactive: this.inactive ? 1 : null,
                        pagination: true,
                        limit: this.pagination.limit,
                        page: this.pagination.page,
                        ...$args
                    }
                });
                this.categories = res.data.data;
                this.pagination.metas = { ...res.data, data: null };
                this.loading = false
            } catch(e) {
                this.loading = false;
            }
        },
        async paginateCategories(page) {
            this.pagination.page = page;
            await this.init();
        },
        async hasID() {
            try {
                var data = await api.get(`/categories`, {
                    params: {
                        id: the_ID
                    }
                })
                var res = data.data.data[0];
                var index = $indexer(this.categories, 'hid', res.hid);
                if(index>=0) {
                    this.formInputs(true, {...res, index, seo_content: this.inputJson(res.seo_content, 'seo_content')}, `_id=${res.hid}`);
                }
            } catch($e) { return; }
        },
        sorting() {
            this.sort = this.sort == 'asc' ? 'desc' : 'asc';
            this.init();
        },
        async updateStatus( $id, $index, status ) {
            var confirm = await swal('Are you sure you want to remove this?', {
                buttons: true,
                dangerMode: true
            });
            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.put('/categories', {
                        id: $id,
                        active: status
                    });
                    this.categories.splice( $index, 1 );
                    if( status ) {
                        swal('Category has been moved to active categories.', { icon: 'success' });
                    } else {
                        swal('Category has been moved to inactive categories.', { icon: 'success' });
                    }
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        },
        jsonToString($json) {
            return JSON.stringify($json);
        },
        async updateData() {
            try {
                await this.showLoading();
                const res = await api.put('/categories', {
                    ...this.input, 
                    id: this.input.hid, 
                    seo_content: this.jsonToString(this.input.seo_content),
                    bannerlist: this.jsonToString(this.input.bannerlist),
                });

                this.categories[this.input.index] = res.data;
                swal('Changes has been saved.', { icon: 'success' });
                this.formInputs(false, this.defaultValue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async insertData() {
            try {
                await this.showLoading();
                const res = await api.post('/categories', {
                    ...this.input, 
                    seo_content: this.jsonToString(this.input.seo_content),
                    bannerlist: this.jsonToString(this.input.bannerlist),
                });

                this.categories.unshift(res.data);
                swal('New Category has been added.', { icon: 'success' });
                this.formInputs(false, this.defaultValue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async saveCategory() {
            var valid = await this.$validator.validate();
            if(!valid) return;

            if( this.input.index!=null ) {
                this.updateData();
            } else {
                this.insertData();
            }
        },
        toggleActive() {
            this.inactive=!this.inactive;
            this.init();
        },
        formInputs(form = false, inputs = {...inputs, seo_content: inputs.seo_content}, appendUrl = "") {
            this.form = form;
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
        wpImage() {
            var $e = this;
            $e.chooseLibrary( 'Choose Category Banner Image', function( image_url ) {
                $e.input.category_banner = image_url;
            });
        },
        seoImage() {
            var $e = this;
            $e.chooseLibrary('Select SEO Image', function(e) {
                $e.input.seo_content.image = e;
            });
        },
        async forceDelete( $id, $index ) {
            var confirm = await swal(this.langs.removeNote, {
                buttons: true,
                dangerMode: true
            });

            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.delete(`/categories?id=${$id}`);
                    this.categories.splice( $index, 1 );
                    swal('Product has been completely removed.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        },

        selectBanner(index) {
            var $e = this;
            $e.chooseLibrary('Select Banner Image', function(url, obj) {
                $e.$set($e.input.bannerlist, index, {
                    ...$e.input.bannerlist[index],
                    title: obj.title,
                    alt: obj.alt,
                    image: url
                })
            });
        }
    }
});