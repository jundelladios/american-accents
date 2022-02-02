var inputs = {
    id: null,
    index: null,
    title: null,
    collection: [],
    priority: 1
}

var stockShapeInstance = new Vue({
    el: '#stockshapeController',
    mixins: [mixVue],
    data: function() {
        return { 
            form: false,
            inputs: {...inputs},
            stockshape: {
                loading: false,
                data: []
            },
            search: null,
            inactive: false,
            sort: 'asc',
            dragGrid: false,
            pagination: {
                page: 1,
                limit: 10,
                metas: {}
            }
        }
    },
    computed: {
        defaultValue() {
            return {...inputs, collection: []};
        },
        inputParams() {
            return {
                ...this.inputs, 
                collection: this.jsonToString(this.inputs.collection.filter(row => row.image && row.code).map(row => {
                    row._isSelected = null;
                    return row;
                }))
            };
        },
        iterateData() {
            const e = this;
            return this.stockshape.data.map( row => {
                row.collection = e.inputJson(row.collection, 'collection');
                return row;
            });
        },
        getPaginationCount() {
            return Math.ceil( this.pagination.metas.total/ this.pagination.limit );
        },
        getIsSelectedStockshapes() {
            return this.inputs.collection.filter(row => row._isSelected == true);
        }
    },
    async mounted() {
        await this.loadCollections();
        // the_ID && await this.hasID();
    },
    methods: {
        selectAll_() {
            this.inputs.collection = this.inputs.collection.map(row => {
                row._isSelected = true;
                return row;
            });
        },
        unselectAll_() {
            this.inputs.collection = this.inputs.collection.map(row => {
                row._isSelected = false;
                return row;
            });
        },
        async removeCheckedItems_() {
            try {
                var confirm = await swal('Are you sure you want to remove selected stockshape?', {
                    buttons: true,
                    dangerMode: true
                });
                if(confirm) {
                    this.inputs.collection = this.inputs.collection.filter(row => !row._isSelected);
                    await this.updateData();
                    swal('Saved', { icon: 'success' });
                }
            } catch($e) {
                return;
            }
        },
        async loadCollections($args) {
            try {
                this.stockshape.loading = true;
                const res = await api.get(`collections/stock-shape`, {
                    params: { 
                        search: this.search, 
                        inactive: this.inactive ? 1 : null,
                        orderBy: `priority ${this.sort}`,
                        pagination: true,
                        limit: this.pagination.limit,
                        page: this.pagination.page,
                        ...$args
                    }
                });
                this.stockshape.data = res.data.data;
                this.pagination.metas = { ...res.data, data: null };
                this.stockshape.loading = false;
            } catch($e) {
                this.stockshape.loading = false;
                return;
            }
        },
        async paginateStockshapeCollection(page) {
            this.pagination.page = page;
            await this.loadCollections();
        },
        formInputs(form = false, inputs = {...inputs}, appendUrl = "") {
            this.form = form;
            this.inputs = inputs;
            $route_change(["_id"], appendUrl);
            this.fixValidator();
        },
        async saveCollection() {
            var valid = await this.$validator.validate();
            if(!valid) return;

            if( this.inputs.index!=null ) {
                this.updateData();
            } else {
                this.insertData();
            }
        },
        async updateData() {
            try {
                await this.showLoading();
                const res = await api.put(`/collections/stock-shape`, {
                    ...this.inputParams,
                    id: this.inputParams.hid
                });
                this.stockshape.data[this.inputs.index] = res.data;
                this.formInputs(false, this.defaultValue);
                swal('Saved', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
            }
        },
        async insertData() {
            try {
                await this.showLoading();
                const res = await api.post(`/collections/stock-shape`, {
                    ...this.inputParams
                });
                this.stockshape.data.push(res.data);
                this.formInputs(false, this.defaultValue);
                swal('New stock shape collections has been added.', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
            }
        },
        addCollection() {
            this.inputs.collection.push({
                image: ``,
                stockname: ``,
                code: ``
            });
        },
        removeStockShape(index) {
            this.inputs.collection.splice(index, 1);
        },
        chooseImage(index) {
            var $e = this;
            this.chooseLibrary( `Select Stock Shape Image`, (url) => {
                $e.inputs.collection[index].image = url;
                // use to refresh the missing key with the object.
            });
        },
        toggleActive() {
            this.inactive=!this.inactive;
            this.loadCollections();
        },
        sorting() {
            this.sort = this.sort == 'asc' ? 'desc' : 'asc';
            this.loadCollections();
        },
        async updateStatus( $id, $index, status ) {
            var confirm = await swal('Are you sure you want to remove this?', {
                buttons: true,
                dangerMode: true
            });
            if( confirm ) {
                try {
                    await this.showLoading();
                    var res = await api.put('/collections/stock-shape', {
                        id: $id,
                        active: status
                    });
                    this.stockshape.data.splice( $index, 1 );

                    if( status ) {
                        swal('Stock shape collection has been moved to active.', { icon: 'success' });
                    } else {
                        swal('Stock shape collection has been moved to inactive.', { icon: 'success' });
                    }
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
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
        async forceDelete( $id, $index ) {
            var confirm = await swal(this.langs.removeNote, {
                buttons: true,
                dangerMode: true
            });

            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.delete(`/collections/stock-shape?id=${$id}`);
                    this.stockshape.data.splice( $index, 1 );
                    swal('Stock shape collection has been completely removed.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        }
    }
});