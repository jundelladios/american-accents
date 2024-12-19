var inputs = {
    hid: null,
    code: '',
    priority: 1,
    index: null
};

var categoryinstanceVue = new Vue({
    el: '#couponController',
    mixins: [mixVue],
    data: function() {
        return {
            loading: false,
            coupons: [],
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
                var res = await api.get(`/coupon-codes`, {
                    params: { 
                        search: this.search, 
                        inactive: this.inactive ? 1 : null,
                        pagination: true,
                        limit: this.pagination.limit,
                        page: this.pagination.page,
                        ...$args
                    }
                });
                this.coupons = res.data.data;
                this.pagination.metas = { ...res.data, data: null };
                this.loading = false
            } catch(e) {
                this.loading = false;
                return;
            }
        },
        async paginateCoupons(page) {
            this.pagination.page = page;
            await this.init();
        },
        async hasID() {
            try {
                var data = await api.get(`/coupon-codes`, {
                    params: {
                        id: the_ID
                    }
                })
                var res = data.data.data[0];
                var index = $indexer(this.coupons, 'hid', res.hid);
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
                    await api.put('/coupon-codes', {
                        id: $id,
                        active: status
                    });
                    this.coupons.splice( $index, 1 );
                    if( status ) {
                        swal('Coupon code has been moved to active codes.', { icon: 'success' });
                    } else {
                        swal('Coupon code has been moved to inactive codes', { icon: 'success' });
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
                const res = await api.put('/coupon-codes', {...this.input, id: this.input.hid});
                this.coupons[this.input.index] = res.data;
                swal('Changes has been saved.', { icon: 'success' });
                this.formInputs(false, this.defaultValue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async insertData() {
            try {
                await this.showLoading();
                const res = await api.post('/coupon-codes', this.input);
                this.coupons.unshift(res.data);
                swal('New Code has been added.', { icon: 'success' });
                this.formInputs(false, this.defaultValue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async saveCode() {
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
                    await api.delete(`/coupon-codes?id=${$id}`);
                    this.coupons.splice( $index, 1 );
                    swal('Coupon code has been completely removed.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        }
    }
});