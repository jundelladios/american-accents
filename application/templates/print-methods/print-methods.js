var methodInstanceVue = new Vue({
    el: '#printMethodsControllerVue',
    mixins: [mixVue],
    data: function() {
        return {
            loading: false,
            methods: [],
            search: null,
            sort: 'asc',
            form: false,
            input: this.defaultValue,
            inactive: false,
            pagination: {
                page: 1,
                limit: 10,
                metas: {}
            }
        }
    },
    async mounted() {
        await this.init();
        the_ID && await this.hasID();
    },
    computed: {
        defaultValue() {
            return {
                hid: null,
                method_name: '',
                method_name2: '',
                method_slug: '',
                method_prefix: '',
                method_desc: '',
                method_desc_short: '',
                method_hex: '#222222',
                priority: 1,
                index: null,
                is_unprinted: 0,
                keyfeatures: [
                    {
                        image: "",
                        text: ""
                    }
                ]
            };
        },
        getPaginationCount() {
            return Math.ceil( this.pagination.metas.total/ this.pagination.limit );
        }
    },
    methods: {
        async init($args) {
            try {
                this.loading = true;
                var res = await api.get(`/print-methods`, {
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
                this.methods = res.data.data;
                this.pagination.metas = { ...res.data, data: null };
                this.loading = false
            } catch( $e ) {
                this.loading = false;
            }
        },
        async paginatePrintMethods(page) {
            this.pagination.page = page;
            await this.init();
        },
        async hasID() {
            try {
                var data = await api.get(`/print-methods`, {
                    params: {
                        id: the_ID
                    }
                })
                var res = data.data.data[0];
                var index = $indexer(this.methods, 'hid', res.hid);
                if(index>=0) {
                    this.formInputs(true, {...res, keyfeatures: this.setDefault(res.keyfeatures, [{ image: '', text: '' }], true), index}, `_id=${res.hid}`);
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
                    var res = await api.put('/print-methods', {
                        id: $id,
                        active: status
                    });
                    this.methods.splice( $index, 1 );

                    if( status ) {
                        swal('Printing Method has been moved to active categories.', { icon: 'success' });
                    } else {
                        swal('Printing Method has been moved to inactive categories.', { icon: 'success' });
                    }
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        },
        async updateData() {
            try {
                await this.showLoading();
                const res = await api.put('/print-methods', {
                    ...this.input, 
                    id: this.input.hid,
                    keyfeatures: JSON.stringify(this.input.keyfeatures.filter(row => row.image && row.text))
                });
                this.methods[this.input.index] = res.data;
                swal('Changes has been saved.', { icon: 'success' });
                this.formInputs(false, {...res.data, keyfeatures: this.setDefault(res, 'keyfeatures')})
            } catch($e) {
                this.backEnd($e);
            }
        },
        async insertData() {
            try {
                await this.showLoading();
                const res = await api.post('/print-methods', {
                    ...this.input,
                    keyfeatures: JSON.stringify(this.input.keyfeatures.filter(row => row.image && row.text))
                });
                this.methods.unshift(res.data);
                swal('Printing Method has been added.', { icon: 'success' });
                this.formInputs(false, this.defaultValue)
            } catch($e) {
                this.backEnd($e);
            }
        },
        async saveMethod() {
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
        formInputs(form = false, inputs = this.defaultValue, appendUrl = "") {
            this.form = form;
            this.input = inputs;
            $route_change(["_id"], appendUrl);
            this.fixValidator();
        },
        async forceDelete( $id, $index ) {
            var confirm = await swal(this.langs.removeNote, {
                buttons: true,
                dangerMode: true
            });

            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.delete(`/print-methods?id=${$id}`);
                    this.methods.splice( $index, 1 );
                    swal('Print Method has been completely removed.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        }
    },
});