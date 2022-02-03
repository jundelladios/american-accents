
var inputs = {
    index: null,
    priority: 1,
    product_name: '',
    product_description: '',
    case_quantity: '',
    case_weight: '',
    case_dim_weight: '',
    case_length: '',
    case_width: '',
    case_height: '',
    dim_top: '',
    dim_height: '',
    dim_base: '',
    area: '',
    item_width: '',
    item_height: '',
    pallet_quantity: '',
    pallet_length: '',
    pallet_width: '',
    pallet_height: '',
    pallet_weight: '',
    class_code: '',
    product_size: '',
    product_size_details: '',
    product_slug: '',
    product_color_hex: '',
    product_color_details: '',
    material_type: '',
    product_thickness: '',
    product_tickness_details: '',
    product_depth: null,
    area_sq_in: null,
    specification_id: null,
    specs_json: [],
    spec_copy: []
};

var backupInputs = {...inputs};

var productsInstanceVue = new Vue({
    el: '#productsControllerVue',
    mixins: [mixVue, specificationVue],
    data() { return {
        filter: {
            category: CATEGORY_ID,
            subcategory: SUBCATEGORY_ID
        },
        materialFilter: null,
        categories: {
            loading: false,
            data: []
        },
        subcategories: {
            loading: false,
            data: []
        },
        pfilters: {
            loading: false,
            data: {}
        },
        inputs: {...inputs},
        form: false,
        products: {
            loading: false,
            data: []
        },
        search: null,
        sort: 'asc',
        inactive: false,
        mounted: false,
        moveProductIndex: null,
        //page: 1
        pagination: {
            page: 1,
            limit: 10,
            metas: null
        },
        pcolors: {
            data: [],
            loading: false,
            input: {
                product_id: null,
                image: [],
                imgformkey: null,
                colorhex: null,
                colorname: null,
                priority: 1,
                index: null,
                iscolorimage: 0,
                colorimageurl: null
            }
        }
    } },
    async mounted() {
        await this.loadCategories();
        if( this.filter.category ) {
            await this.loadSubcategories( this.filter.category );
            this.filter.subcategory = SUBCATEGORY_ID;
        }
        await this.getProductFilters();
    },
    watch: {
        'filter.category': function($category) {
            this.products.data = [];
            $route_change(["category", "subcategory"], "category=" + $category);
            this.pagination.metas = null;
            this.loadSubcategories($category);
        },
        'filter.subcategory': async function($value) {
            $route_change(["subcategory"], $value ? "subcategory=" + $value : '');
            this.pagination.page = 1;
            await this.fetchProducts();
            await this.getProductFilters();
        },
        'materialFilter': async function($value) {
            this.pagination.page = 1;
            await this.fetchProducts();
        },
        'pcolors.input.product_id': async function(val) {
            val && await this.getColorByProductId(val);
        }
    },
    computed: {
        checkInformation() {
            return this.requiredChecker([
                'product_name', 'priority', 'product_slug', 'product_description'
            ]);
        },
        checkCase() {
            return this.requiredChecker([
                'case_quantity', 'case_weight', 'case_dim_weight', 'case_length', 'case_width', 'case_height'
            ]);
        },
        checkPallet() {
            return this.requiredChecker([
                'pallet_quantity', 'pallet_length', 'pallet_width', 'pallet_height', 'pallet_weight'
            ]);
        },
        defaultValue() {
            return {...backupInputs};
        },
        getMovableCategories() {
            // if( this.filter.category ) {
            //     return this.categories.data.filter( row => row.hid != this.filter.category );
            // }
            // return [];
            return this.categories.data
        },
        getPendingRemoveProduct() {
            if( this.moveProductIndex != null ) {
                return this.products.data[this.moveProductIndex];
            }
            return null
        },
        getPaginationCount() {
            return Math.ceil( this.pagination.metas.total/ this.pagination.limit );
        },
        productcolorarrangement() {
            return this.pcolors.data.map((row, index) => {
                row.dataIndexer = index;
                row.strimage = row.image;
                row.objimage = row.image ? this._toJson( row.image ) : [];
                row.datakeyfinal = row.datakey ? row.datakey : row.hid;
                return row;
            }).sort((x,y) => {
                return x.priority - y.priority;
            })
        },
    },
    methods: {
        async loadCategories() {
            try {
                this.categories.loading = true;
                const res = await api.get(`/categories?tree=1`);
                this.categories.data = res.data;
                this.categories.loading = false;
            } catch( $e ) { 
                this.categories.loading = false;
                return; 
            }
        },
        async loadSubcategories($categoryId) {
            try {
                this.subcategories.loading = true;
                this.filter.subcategory = null;
                const res = await api.get(`/subcategories?product_category_id=${$categoryId}&orderBy=priority+asc`);
                this.subcategories.data = res.data.data;
                this.subcategories.loading = false;
            } catch( $e ) {
                this.subcategories.loading = false;
                return;
            }
        },
        async getProductFilters() {
            try {
                this.pfilters.loading = true;
                const res = await api.get(`/products/filters?category=${this.filter.category}&subcategory=${this.filter.subcategory}`);
                this.pfilters.data = res.data;
                this.pfilters.loading = false;
            } catch($e) {
                this.pfilters.loading = false;
                return;
            }
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
        async saveProduct() {
            var valid = await this.$validator.validate();
            if(!valid) {
                swal('Please check the highlighted fields.', { icon: 'error' });
                return;
            }

            if( this.inputs.hid ) {
                await this.updateData();
            } else {
                await this.insertData();
            }
        },
        async updateData() {
            try {
                await this.showLoading();
                const res = await api.put(`/products`, { 
                    ...this.inputs,
                    product_category_id: this.filter.category,
                    product_subcategory_id: this.filter.subcategory,
                    id: this.inputs.hid,
                    specs_json: this.apiSpecJSON
                });
                swal('Product has been saved.', { icon: 'success' });
                this.products.data[this.inputs.index]  = res.data;
                this.formInputs(false, this.defaultValue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async insertData() {
            try {
                await this.showLoading();
                const res = await api.post(`/products`, { 
                    ...this.inputs,
                    product_category_id: this.filter.category,
                    product_subcategory_id: this.filter.subcategory,
                    specs_json: this.apiSpecJSON
                });
                swal('New Product has been added.', { icon: 'success' });
                this.products.data.push(res.data);
                this.formInputs(false, this.defaultValue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async updateStatus( $id, $index, status ) {
            var confirm = await swal('Are you sure you want to remove this?', {
                buttons: true,
                dangerMode: true
            });
            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.put('/products', {
                        id: $id,
                        active: status
                    });
                    this.products.data.splice( $index, 1 );
                    if( status ) {
                        swal('Product has been moved to active products.', { icon: 'success' });
                    } else {
                        swal('Product has been moved to inactive products.', { icon: 'success' });
                    }
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        },
        toggleActive() {
            this.inactive=!this.inactive;
            this.pagination.page = 1;
            this.fetchProducts();
        },
        sorting() {
            this.sort = this.sort == 'asc' ? 'desc' : 'asc';
            this.pagination.page = 1;
            this.fetchProducts();
        },
        async fetchProducts($args) {
            if(!this.filter.category || !this.filter.subcategory) return;
            try {
                this.products.loading = true;
                const res = await api.get('/products', {
                    params: {
                        search: this.search, 
                        orderBy: `priority ${this.sort}`, 
                        inactive: this.inactive ? 1 : null,
                        subcategory_id: this.filter.subcategory,
                        category_id: this.filter.category,
                        ...$args,
                        pagination: true,
                        limit: this.pagination.limit,
                        page: this.pagination.page,
                        material_type: this.materialFilter
                    }
                });
                this.products.data = res.data.data;
                this.pagination.metas = { ...res.data, data: null };
                this.products.loading = false;
            } catch($e) {
                this.products.loading = false;
                return;
            }
        },
        formInputs(form = false, inputs = {...inputs}, appendUrl = "", loadFilter = true) {
            this.form = form;
            this.inputs = inputs;
            $route_change(["_id"], appendUrl);
            accordioJS.state = null;
            this.fixValidator();
            if(loadFilter) { this.getProductFilters(); }
        },
        toggleSubcat(index) {
            jQuery(`.subcat-move-${index}`).slideToggle('fast');
        },
        async moveProduct(cat, sub) {
            var confirm = await swal('Are you sure you want to move this product?', {
                buttons: true,
                dangerMode: true
            });
            if( !confirm ) { return; }
            try {
                await this.showLoading();
                const res = await api.put('/products/move', {
                    id: this.getPendingRemoveProduct.hid,
                    product_category_id: cat,
                    product_subcategory_id: sub
                });
                this.products.data.splice( this.moveProductIndex, 1 );
                this.moveProductIndex = null;
                swal('Product has been moved.', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async paginateProducts(page) {
            this.pagination.page = page;
            await this.fetchProducts();
        },
        chooseProductImageColor() {
            var $e = this;
            this.chooseLibrary( `Select Color Image`, (url) => {
                $e.pcolors.input.colorimageurl = url;
            });
        },
        async forceDeleteProduct( $id, $index ) {
            var confirm = await swal(this.langs.removeNote, {
                buttons: true,
                dangerMode: true
            });

            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.delete(`/products?id=${$id}`);
                    this.products.data.splice( $index, 1 );
                    swal('Product has been completely removed.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        }
    }
})