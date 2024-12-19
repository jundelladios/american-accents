var defaultVal = {
    step: 1,
    pline: null
}

var chargesInputs = {
    charge_type_id: null,
    priority: 1,
    pvalues: [],
    is_additional_spot: 0,
    per_color: 0,
    per_piece: 0,
    per_side: 0,
    per_thousand: 0,
    note_value: null,
    auto_format: 0,
    spot_color_value: null,
    per_color_value: null,
    per_piece_value: null,
    per_side_value: null,
    per_thousand_value: null
}

var chargesValuesInputs = {
    hid: null,
    quantity: null,
    value: null,
    index: null,
    asterisk: 0,
    alternative_value: null,
    unit_value: null,
    decimal_value: 3,
    show_currency: false
}

var chargesInstance = new Vue({
    el: '#plinechargesController',
    mixins: [mixVue],
    data: function() {
        return {
            charges: {...defaultVal},
            chargeData: {
                loading: false,
                data: []
            },
            chargesInputs: {...chargesInputs},
            chargesValuesInputs: {...chargesValuesInputs},
            chargeTypes: {
                loading: false,
                data: []
            }
        }
    },
    computed: {
        pbreakdowns() {
            return this.chargesInputs.pvalues.sort((a, b) => a.quantity - b.quantity);
        }
    },
    watch: {
        'charges.pline': function(value) {
            if(!value) {
                this.resetState();
            } else {
                this.loadCharges();
                this.loadChargeTypes();
            }
        },
        'charges.step': function(value) {
            if( value === 1 ) {
                this.loadCharges();
            }
        }
    },
    methods: {
        resetState() {
            this.charges = {...defaultVal}
        },
        setChargeState($newState) {
            this.charges = {...this.charges, ...$newState};
        },
        async loadChargeTypes() {
            try {
                this.chargeTypes.loading = true;
                const res = await api.get( `/charges` );
                this.chargeTypes.data = res.data.data;
                this.chargeTypes.loading = false;
            } catch($e) {
                return;
            }
        },
        async loadCharges() {
            if( this.charges.pline ) {
                try {
                    this.chargeData.loading = true;
                    const res = await api.get( `/pricing-data?product_line_id=${this.charges.pline.hid}&orderBy=priority asc` );
                    this.chargeData.data = res.data.data.map( row => {
                        row.edit = false;
                        return row;
                    });
                    this.chargeData.loading = false;
                } catch($e) {
                    this.chargeData.loading = false;
                    return;
                }
            }
        },
        async saveCharge() {
            var valid = await this.$validator.validateAll('charge');
            if(!valid) return;
            
            if( this.chargesInputs.hid ) {
                await this.updateCharge();
            } else {
                await this.addCharge();
            }
        },
        async addCharge() {
            try {
                await this.showLoading();
                const res = await api.post(`/pricing-data`, {
                    ...this.chargesInputs,
                    product_line_id: this.charges.pline.hid
                });
                this.chargesInputs = {...res.data, charge_type_id: res.data.chargetypes.hid};
                swal('New charge on this product line has been added.', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async updateCharge() {
            try {
                await this.showLoading();
                const res = await api.put(`/pricing-data`, {
                    ...this.chargesInputs,
                    product_line_id: this.charges.pline.hid,
                    id: this.chargesInputs.hid
                });
                this.chargesInputs = {...res.data, charge_type_id: res.data.chargetypes.hid};
                swal('This charge has been saved.', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        cancelCharge() {
            this.chargesInputs = {...chargesInputs};
            this.chargesValuesInputs = {...chargesValuesInputs};
            this.charges.step = 1;
            this.fixValidator();
        },
        async removeCharge($id, $index) {
            var confirm = await swal('Are you sure you want to remove this?', {
                buttons: true,
                dangerMode: true
            });
            if(!confirm) { return; }
            try {
                await this.showLoading();
                await api.delete(`/pricing-data?id=${$id}`)
                this.chargeData.data.splice( $index, 1 );
                swal('Successfully removed.', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        editCharge($data) {
            this.charges.step = 2;
            this.chargesInputs = {...$data, charge_type_id: $data.chargetypes.hid};
        },
        closeModal() {
            this.charges.pline=null;
            this.cancelCharge();
        },
        cancelBreakdown() {
            this.chargesValuesInputs = {...chargesValuesInputs};
            this.fixValidator();
        },
        async saveBreakdown() {
            var valid = await this.$validator.validateAll('cval');
            if(!valid) return;

            if( this.chargesValuesInputs.hid ) { await this.editBreakdown(); }
            else { await this.addBreakdown(); }
        },
        async removeBreakdown(id, index) {
            var confirm = await swal('Are you sure you want to remove this breakdown?', {
                buttons: true,
                dangerMode: true
            });
            if(!confirm) { return; }
            try {
                await this.showLoading();
                const res = await api.delete(`/pricing-data/values?id=${id}`);
                this.chargesInputs.pvalues.splice(index, 1);
                swal('Successfully removed.', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async addBreakdown() {
            try {
                const res = await api.post(`/pricing-data/values`, { 
                    ...this.chargesValuesInputs, 
                    product_line_id: this.charges.pline.hid,
                    pricing_data_id: this.chargesInputs.hid,
                    value: this.chargesValuesInputs.value ? this.chargesValuesInputs.value : null
                });
                this.chargesInputs.pvalues.push( res.data );
                this.cancelBreakdown();
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async editBreakdown() {
            try {
                const res = await api.put(`/pricing-data/values`, {
                    ...this.chargesValuesInputs,
                    id: this.chargesValuesInputs.hid,
                    value: this.chargesValuesInputs.value ? this.chargesValuesInputs.value : null
                });
                this.chargesInputs.pvalues[this.chargesValuesInputs.index] = res.data;
                this.cancelBreakdown();
            } catch($e) {
                this.backEnd($e);
                return;
            }
        }
    }
});