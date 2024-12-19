

var inputs = {
    hid: null,
    title: '',
    priority: 1,
    customfield: [...productspecjson],
    //customfieldcombo: [...productcombospecsjson],
    specs: [],
    index: null,
    isspec: 0,
    keyret: null
};

var defaultvalue = {...inputs}

// Going to use this neat array function
Array.prototype.move = function(from, to) {
    this.splice(to, 0, this.splice(from, 1)[0]);
    return this;
};

var spectypesinstanceVue = new Vue({
    el: '#spectypesController',
    mixins: [mixVue],
    data: function() {
        return {
            loading: false,
            spectypes: [],
            search: null,
            form: false,
            input: {...inputs},
            specdrag: false,
            fielddrag: false,
            specbdrag: false,
            inactive: false,
            sort: 'asc',
            pagination: {
                page: 1,
                limit: 10,
                metas: {}
            }
        }
    },
    computed: {
        defaultValue() {
            return {...defaultvalue};
        },
        getFields() {
            const e = this;
            let res = [];
            e.input.customfield.map( row => {
                res = [...res, ...row.fields];
            });
            // e.input.customfieldcombo.map( row => {
            //     res = [...res, row.fields];
            // });
            return res;
        },
        inputParam() {
            return {
                ...this.input,
                customfield: JSON.stringify(this.input.customfield.filter( row => row.group && row.fields.filter( x => x.key ).length )),
                //customfieldcombo: JSON.stringify(this.input.customfieldcombo.filter( row => row.group && row.fields.filter( x => x.key ).length )),
                specs: JSON.stringify(this.input.specs.filter(row => row.label && row.filter))
            };
        },
        speciteration() {
            const e = this;
            return this.input.specs.map( row => {
                // const match = /\{[^{}]+\}/g;
                // const keys = row.filter.match(match);
                let output = row.filter;
                // if(keys && keys.length) {
                //     keys.map( keydata => {
                //         var nobracket = keydata.replace(/[\{\}]/g, "");
                //         var searchValue = e.getFields.find( x => x.key == nobracket );
                //         if( searchValue && searchValue.default ) {
                //             output = output.replace(keydata, searchValue.default);
                //         }
                //     });
                // }
                // row.regex = keys;
                row.output = output;
                return row;
            });
        },
        getNonOutputSpec() {
            return this.spectypes.filter( row => !row.isspec );
        },
        getSelectedKeysSpec() {
            const e = this;
            let res = [];
            const filtered = this.spectypes.filter( row => row.hid == this.input.keyret );
            if( filtered.length ) {
                filtered[0].cfield.map( row => {
                    res = [...res, ...row.fields];
                });
            }
            return res;
        },
        getPaginationCount() {
            return Math.ceil( this.pagination.metas.total/ this.pagination.limit );
        }
    },
    async mounted() {
        await this.init();
        // the_ID && await this.hasID();
    },
    methods: {
        async init($args) {
            try {
                this.loading = true;
                var res = await api.get(`/specification-types`, {
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
                this.spectypes = res.data.data;
                this.pagination.metas = { ...res.data, data: null };
                this.loading = false
            } catch(e) {
                this.loading = false;
                return;
            }
        },
        async paginateSpecs(page) {
            this.pagination.page = page;
            await this.init();
        },
        sorting() {
            this.sort = this.sort == 'asc' ? 'desc' : 'asc';
            this.init();
        },
        // async hasID() {
        //     try {
        //         var data = await api.get(`/specification-types`, {
        //             params: {
        //                 id: the_ID
        //             }
        //         })
        //         var res = data.data.data[0];
        //         var index = $indexer(this.spectypes, 'hid', res.hid);
        //         if(index>=0) {
        //             this.formInputs(true, {...res, index}, `_id=${res.hid}`);
        //         }
        //     } catch($e) { return; }
        // },
        async updateStatus( $id, $index, status ) {
            var confirm = await swal('Are you sure you want to remove this?', {
                buttons: true,
                dangerMode: true
            });
            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.put('/specification-types', {
                        id: $id,
                        active: status
                    });
                    this.spectypes.splice( $index, 1 );
                    if( status ) {
                        swal('Specification type has been moved to active codes.', { icon: 'success' });
                    } else {
                        swal('Specification type has been moved to inactive codes', { icon: 'success' });
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
                const res = await api.put('/specification-types', {
                    ...this.input, 
                    id: this.input.hid,
                    ...this.inputParam
                });
                this.spectypes[this.input.index] = res.data;
                swal('Changes has been saved.', { icon: 'success' });
                this.formInputs(false, defaultvalue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async insertData() { console.log(this.inputParam);
            try {
                await this.showLoading();
                const res = await api.post('/specification-types', {
                    ...this.input,
                    ...this.inputParam
                });
                this.spectypes.unshift(res.data);
                swal('New specification type has been added.', { icon: 'success' });
                this.formInputs(false, defaultvalue);
            } catch($e) {
                this.backEnd($e);
            }
        },
        async saveSpecs() {
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
        parserExcludes(value) {
            try {
                return JSON.parse(value);
            } catch($e) {
                return inputs;
            }
        },
        newCustomField() {
            this.input.customfield.push({
                group: '',
                uniqueKey: Date.now(),
                fields: []
            });
        },
        newCustomFieldCombo() {
            this.input.customfieldcombo.push({
                group: '',
                uniqueKey: Date.now(),
                fields: []
            });
        },
        newSpecification() {
            this.input.specs.push({
                label: '',
                filter: '',
                uniqueKey: Date.now(),
                isexec: false
            });
        },
        async duplicateEntry($id) {
            var confirm = await swal('Are you sure you want to duplicate this specification type?', {
                buttons: true,
                dangerMode: false
            });

            if( confirm ) {
                try {
                    await this.showLoading();
                    const res = await api.post('/specification-types/duplicate', {
                        id: $id
                    });
                    this.spectypes.unshift(res.data);
                    swal('New specification type has been added.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                }
            }
        },
        moveGroupSpec(from, to) {
            this.input.customfield.move(from, to);
        },
        // moveGroupSpecCombo(from, to) {
        //     this.input.customfieldcombo.move(from, to);
        // },
        moveFieldSpecProduct(group, from, to) {
            this.input.customfield[group].fields.move(from, to);
        },
        // moveFieldSpecCombo(group, from, to) {
        //     this.input.customfieldcombo[group].fields.move(from, to);
        // },
        moveSpecBuilder(from, to) {
            this.input.specs.move(from, to);
        },
        setGroupData($array) {
            return $array.map((row, index) => {
                row.uniqueKey = row.uniqueKey ? row.uniqueKey : Date.now() + index;
                row.type = row.type ? row.type : 'text';
                row.options = row.options ? row.options : '';
                row.fields.map((field, fieldkey) => {
                    field.uniqueKey = field.uniqueKey ? field.uniqueKey : Date.now() + index + fieldkey;
                });
                return row;
            });
        },
        setSpecFieldData($array) {
            return $array.map((row, index) => {
                row.uniqueKey = row.uniqueKey ? row.uniqueKey : Date.now() + index;
                row.isexec = row.isexec ? row.isexec : false;
                return row;
            });
        },
        highlighter(code) {
            // js highlight example
            return Prism.highlight(code, Prism.languages.js, "js");
        },
        async forceDelete( $id, $index ) {
            var confirm = await swal(this.langs.removeNote, {
                buttons: true,
                dangerMode: true
            });

            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.delete(`/specification-types?id=${$id}`);
                    this.spectypes.splice( $index, 1 );
                    swal('Specification Type has been completely removed.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        }
    }
});