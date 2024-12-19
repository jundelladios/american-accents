var inputs = {
    id: null,
    index: null,
    charge_name: null,
    icon: null
}

var chargesVueInstance = new Vue({
    el: '#chargesController',
    mixins: [mixVue],
    data: function() {
        return { 
            form: false,
            inputs: {...inputs},
            charges: {
                loading: false,
                data: []
            },
            pagination: {
                page: 1,
                limit: 10,
                metas: {}
            },
            inactive: false,
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
    mounted() {
        this.init();
    },
    methods: {
        init: async function($args) {
            try {
                this.charges.loading = true;
                const res = await api.get(`/charges`, {
                    params: {
                        pagination: true,
                        limit: this.pagination.limit,
                        page: this.pagination.page,
                        inactive: this.inactive ? 1 : null,
                        ...$args
                    }
                });
                this.charges.data = res.data.data;
                this.pagination.metas = { ...res.data, data: null };
                this.charges.loading = false;
            } catch($e) {
                return;
            }
        },
        async paginateCharges(page) {
            this.pagination.page = page;
            await this.init();
        }, 
        formInputs(form = false, inputs = {...inputs}, appendUrl = "") {
            this.form = form;
            this.inputs = inputs;
            $route_change(["_id"], appendUrl);
            this.fixValidator();
        },
        async saveCharge() {
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
                const res = await api.put(`/charges`, {
                    ...this.inputs,
                    id: this.inputs.hid
                });
                this.charges.data[this.inputs.index] = res.data;
                this.formInputs(false, this.defaultValue);
                swal('Saved', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
            }
        },
        async insertData() {
            try {
                await this.showLoading();
                const res = await api.post(`/charges`, {
                    ...this.inputs
                });
                this.charges.data.push(res.data);
                this.formInputs(false, this.defaultValue);
                swal('New Charge has been added.', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
            }
        },
        toggleActive() {
            this.inactive=!this.inactive;
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
                    await api.put('/charges', {
                        id: $id,
                        active: status
                    });
                    this.charges.data.splice( $index, 1 );
                    if( status ) {
                        swal('Charge type has been moved to active codes.', { icon: 'success' });
                    } else {
                        swal('Charge type has been moved to inactive codes', { icon: 'success' });
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
                    await api.delete(`/charges?id=${$id}`);
                    this.charges.data.splice( $index, 1 );
                    swal('Charge type has been completely removed.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        }
    }
})