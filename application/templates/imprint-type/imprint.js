const inputs = {
    title: null,
    body: null,
    priority: 1,
    index: null
};

var categoryinstanceVue = new Vue({
    el: '#imprintController',
    mixins: [mixVue],
    data: function() {
        return {
            loading: false,
            imprints: [],
            search: null,
            form: false,
            input: {...inputs},
            inactive: false,
            pagination: {
                page: 1,
                limit: 10,
                metas: {}
            }
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
                var res = await api.get(`/imprint-type`, {
                    params: { 
                        search: this.search, 
                        inactive: this.inactive ? 1 : null,
                        pagination: true,
                        limit: this.pagination.limit,
                        page: this.pagination.page,
                        ...$args
                    }
                });
                this.imprints = res.data.data;
                this.pagination.metas = { ...res.data, data: null };
                this.loading = false
            } catch(e) {
                this.loading = false;
                return;
            }
        },
        async paginateImprintTypes(page) {
            this.pagination.page = page;
            await this.init();
        },
        async hasID() {
            try {
                var data = await api.get(`/imprint-type`, {
                    params: {
                        id: the_ID
                    }
                })
                var res = data.data.data[0];
                var index = $indexer(this.imprints, 'hid', res.hid);
                if(index>=0) {
                    this.formInputs(true, {...res, index}, `_id=${res.hid}`);
                }
            } catch($e) { return; }
        },
        async updateStatus( $id, $index, status ) {
            var confirm = await swal('Are you sure you want to remove this?', {
                buttons: true,
                dangerMode: true
            });
            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.put('/imprint-type', {
                        id: $id,
                        active: status
                    });
                    this.imprints.splice( $index, 1 );
                    if( status ) {
                        swal('Imprint type has been moved to active codes.', { icon: 'success' });
                    } else {
                        swal('Imprint type has been moved to inactive codes.', { icon: 'success' });
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
                const res = await api.put('/imprint-type', {...this.input, id: this.input.hid});
                this.imprints[this.input.index] = res.data;
                swal('Imprint type has been saved.', { icon: 'success' });
                this.formInputs(false, this.defaultValue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async insertData() {
            try {
                await this.showLoading();
                const res = await api.post('/imprint-type', this.input);
                this.imprints.unshift(res.data);
                swal('New imprint type has been added.', { icon: 'success' });
                this.formInputs(false, this.defaultValue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async saveImprint() {
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
        formInputs(form = false, inputs = {...inputs}, appendUrl = "") {
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
                    await api.delete(`/imprint-type?id=${$id}`);
                    this.imprints.splice( $index, 1 );
                    swal('Imprint type has been completely removed.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        }
    }
});