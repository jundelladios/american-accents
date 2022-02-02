var specificationVue = {
    data() {
        return {
            specification: {
                data: [],
                loading: false
            }
        }
    },
    computed: {
        getSelectedSpectype() {
            if( this.inputs.specification_id ) {
                const data = this.specification.data.find( row => row.hid == this.inputs.specification_id );
                return data;
            }
            return null;
        },
        apiSpecJSON() {
            let res = [];
            if( this.inputs.specs_json ) {
                res = this.inputs.specs_json;
            }
            return JSON.stringify(res);
        },
        getSpecFields() {
            let ret = [];
            this.inputs.spec_copy && this.inputs.spec_copy.map( row => {
                ret = [...ret, ...row.fields]
            });
            return ret;
        },
        getSpecData() {
            return this.specification.data.filter( row => !row.isspec );
        },
        getSpecOutputData() {
            return this.specification.data.filter( row => row.isspec );
        }
    },
    async mounted() {
        await this.loadSpecificationTypes();
    },
    watch: {
        'inputs.hid': async function() {
            await this.loadSpecificationTypes();
        }
    },
    methods: {
        // setSpecificationJson() {
        //     var value = this.product.data.spechandler.hid;
        //     if( !value ) { this.inputs.specs_json = this.product.data.spechandler.cfield; }

        //     const copydata = [...this.specification.data];
        //     const data = copydata.find( row => row.hid == value );
        //     if(!data) { return false; }

        //     this.inputs.specification_id = value;
        //     const e = this;
        //     e.inputs.specs_json = data.cfield.map(row => {
        //         row.groupid = row.group.replaceAll(/[^a-zA-Z0-9]/g,'_').toLowerCase();
        //         return {
        //             ...row,
        //             fields: row.fields.map(field => {
        //                 // set default value from spec
        //                 if( field.default ) {
        //                     field.value = field.default;
        //                 }
        //                 // existing value from default field
        //                 const existingValue = e.inputs[field.key];
        //                 if( existingValue ) {
        //                     field.value = existingValue;
        //                 }
        //                 // check existing value from spec json
        //                 const existingspec = e.getSpecFields.find(ex => ex.key == field.key);
        //                 if( existingspec && existingspec.value && e.getSpecFields.length ) {
        //                     field.value = existingspec.value;
        //                 }
        //                 return field;
        //             })
        //         };
        //     });
        // },
        setSpecificationJson(value) {
            if( !value ) { this.inputs.specs_json = []; }

            const copydata = [...this.specification.data];
            const data = copydata.find( row => row.hid == value );
            if(!data) { return false; }

            this.inputs.specification_id = value;
            const e = this;
            e.inputs.specs_json = [...data.cfield].map(row => {
                row.groupid = row.group.replaceAll(/[^a-zA-Z0-9]/g,'_').toLowerCase();
                return {
                    ...row,
                    fields: row.fields.map(field => {
                        // set default value from spec
                        if( field.default ) {
                            field.value = field.default;
                        }
                        // existing value from default field
                        const existingValue = e.inputs[field.key];
                        if( existingValue ) {
                            field.value = existingValue;
                        }
                        // check existing value from spec json
                        const existingspec = e.getSpecFields.find(ex => ex.key == field.key);
                        if( existingspec && existingspec.value && e.getSpecFields.length ) {
                            field.value = existingspec.value;
                        }

                        // set accessible keys from table
                        e.inputs[field.key] = field.value;

                        return field;
                    })
                };
            });
        },
        async loadSpecificationTypes() {
            try {
                this.specification.loading = true;
                var res = await api.get(`/specification-types?orderBy=priority asc`);
                this.specification.data = res.data.data;
                this.specification.loading = false
            } catch(e) {
                this.specification.loading = false;
                return;
            }
        },
        setSpecJson(key, value, groupindex, index) {
            this.inputs = {...this.inputs, [key]: value};
            const selectedField = this.inputs.specs_json[groupindex].fields[index].key;
            var finalVal = value;
            if(selectedField == 'number') {
                var finalVal = Number(value);
            }
            this.inputs.specs_json[groupindex].fields[index].value = finalVal;
        }
    },
}