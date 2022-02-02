var imprintProductLine = {
    data() {
        return {
            imprint: {
                imprint_type_id: null,
                productline_id: null,
                min_prod_days: null,
                imprint_charge: null,
                index: null,
                priority: 1,
                image: null
            },
            imprintTypesData: {
                data: [],
                loading: false
            },
            imprintProductLineData: {
                data: [],
                loading: false
            }
        }
    },
    watch: {
        'imprint.productline_id': async function(val) {
            if( val ) {
                // get imprint type product line
                await this.getImprintProductLine();
            }
        }
    },
    computed: {
        imprintorderbypriority() {
            return this.imprintProductLineData.data.sort((x,y) => {
                return x.priority - y.priority;
            });
        }
    },
    async mounted() {
        await this.getImprintTypes();
    },
    methods: {
        imprintInputs( $args ) {
            this.imprint = {
                imprint_type_id: null,
                productline_id: null,
                min_prod_days: null,
                imprint_charge: null,
                priority: 1,
                index: null,
                id: null,
                image: null,
                ...$args
            }
        },
        cancelImprintInputs() {
            this.imprintInputs({
                ...this.imprint,
                imprint_type_id: null,
                min_prod_days: null,
                imprint_charge: null,
                index: null,
                priority: 1,
                image: null
            });
            this.fixValidator();
        },
        async getImprintTypes() {
            try {
                this.imprintTypesData.loading = true;
                const res = await api.get(`/imprint-type`);
                this.imprintTypesData.data = res.data.data;
                this.imprintTypesData.loading = false;
            } catch($e) {
                this.imprintTypesData.loading = false;
                return;
            }
        },
        async getImprintProductLine() {
            try {
                this.imprintProductLineData.loading = true;
                const res = await api.get(`/imprint-type-product-line`, {
                    params: {
                        productline_id: this.imprint.productline_id
                    }
                });
                this.imprintProductLineData.data = res.data.data;
                this.imprintProductLineData.loading = false;
            } catch($e) {
                this.imprintProductLineData.loading = false;
                return false;
            }
        },
        chooseImprintImage() {
            var $e = this;
            this.chooseLibrary( `Select Imprint Type Image`, url => {
                $e.imprint.image = url;
            });
        },
        async updateDataImprint() {
            try {
                await this.showLoading();
                const res = await api.put('/imprint-type-product-line', {
                    ...this.imprint,
                    min_prod_days: this.imprint.min_prod_days != "" ? this.imprint.min_prod_days : null,
                    imprint_charge: this.imprint.imprint_charge != "" ? this.imprint.imprint_charge : null
                });
                this.imprintProductLineData.data[this.imprint.index] = res.data;
                swal('Changes has been saved.', { icon: 'success' });
                this.cancelImprintInputs();
            } catch($e) {
                this.backEnd($e);
            }
        },
        async insertDataImprint() {
            try {
                await this.showLoading();
                const res = await api.post('/imprint-type-product-line', this.imprint);
                this.imprintProductLineData.data.unshift(res.data);
                swal('New imprint type on this product line has been added.', { icon: 'success' });
                this.cancelImprintInputs();
            } catch($e) {
                this.backEnd($e);
            }
        },
        async saveImprintProductLine() {
            var valid = await this.$validator.validate();
            if(!valid) return;

            if( this.imprint.index!=null ) {
                this.updateDataImprint();
            } else {
                this.insertDataImprint();
            }
        },
        async removeImprintPline($id, $index) {
            try {
                var confirm = await swal('Are you sure you want to remove this imprint type?', {
                    buttons: true,
                    dangerMode: true
                });
                if( !confirm ) { return; }
                await this.showLoading();
                const res = await api.delete(`/imprint-type-product-line`, {
                    params: {
                        id: $id
                    }
                });
                this.imprintProductLineData.data.splice( $index, 1 );
                swal('Imprint type has been removed.', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
                return;
            }
        }
    }
}