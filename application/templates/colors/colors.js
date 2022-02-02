var inputs = {
    id: null,
    index: null,
    title: null,
    colorjson: [],
    priority: 1
}

var colorssVueInstance = new Vue({
    el: '#colorsController',
    mixins: [mixVue],
    data: function() {
        return { 
            form: false,
            inputs: {...inputs},
            colors: {
                loading: false,
                data: []
            },
            search: null,
            inactive: false,
            sort: 'asc',
            colordrag: false,
            pagination: {
                page: 1,
                limit: 10,
                metas: {}
            }
        }
    },
    computed: {
        defaultValue() {
            return {...inputs, colorjson: []};
        },
        inputParams() {
            return {
                ...this.inputs, 
                colorjson: this.jsonToString(this.inputs.colorjson.filter(row => row.name && row.hex).map(row => {
                    row._isSelected = null;
                    return row;
                }))
            };
        },
        iterateData() {
            const e = this;
            return this.colors.data.map( row => {
                row.colorjson = e.inputJson(row.colorjson, 'colorjson');
                return row;
            });
        },
        getPaginationCount() {
            return Math.ceil( this.pagination.metas.total/ this.pagination.limit );
        },
        getIsSelectedColors() {
            return this.inputs.colorjson.filter(row => row._isSelected == true);
        }
    },
    async mounted() {
        await this.loadColors();
        // the_ID && await this.hasID();
    },
    methods: {
        selectAll_() {
            this.inputs.colorjson = this.inputs.colorjson.map(row => {
                row._isSelected = true;
                return row;
            });
        },
        unselectAll_() {
            this.inputs.colorjson = this.inputs.colorjson.map(row => {
                row._isSelected = false;
                return row;
            });
        },
        async removeCheckedItems_() {
            try {
                var confirm = await swal('Are you sure you want to remove selected colors?', {
                    buttons: true,
                    dangerMode: true
                });
                if(confirm) {
                    this.inputs.colorjson = this.inputs.colorjson.filter(row => !row._isSelected);
                    await this.updateData();
                }
            } catch($e) {
                return;
            }
        },
        async loadColors($args) {
            try {
                this.colors.loading = true;
                const res = await api.get(`/colors`, {
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
                this.colors.data = res.data.data;
                this.pagination.metas = { ...res.data, data: null };
                this.colors.loading = false;
            } catch($e) {
                this.colors.loading = false;
                return;
            }
        },
        async paginateColors(page) {
            this.pagination.page = page;
            await this.loadColors();
        },
        formInputs(form = false, inputs = {...inputs}, appendUrl = "") {
            this.form = form;
            this.inputs = inputs;
            $route_change(["_id"], appendUrl);
            this.fixValidator();
        },
        async saveColors() {
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
                const res = await api.put(`/colors`, {
                    ...this.inputParams,
                    id: this.inputParams.hid
                });
                this.colors.data[this.inputs.index] = res.data;
                this.formInputs(false, this.defaultValue);
                swal('Saved', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
            }
        },
        async insertData() {
            try {
                await this.showLoading();
                const res = await api.post(`/colors`, {
                    ...this.inputParams
                });
                this.colors.data.push(res.data);
                this.formInputs(false, this.defaultValue);
                swal('New color collections has been added.', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
            }
        },
        addCollection() {
            this.inputs.colorjson.push({
                name: ``,
                hex: ``,
                pantone: ``,
                isImage: false,
                image: null
            });
        },
        removeColor(index) {
            this.inputs.colorjson.splice(index, 1);
        },
        chooseImage(index) {
            var $e = this;
            this.chooseLibrary( `Select Background Color`, (url) => {
                $e.inputs.colorjson[index].image = url;
                // use to refresh the missing key with the object.
                $e.inputs.colorjson[index].isImage = false;
                $e.inputs.colorjson[index].isImage = true;
            });
        },
        toggleActive() {
            this.inactive=!this.inactive;
            this.loadColors();
        },
        sorting() {
            this.sort = this.sort == 'asc' ? 'desc' : 'asc';
            this.loadColors();
        },
        async updateStatus( $id, $index, status ) {
            var confirm = await swal('Are you sure you want to remove this?', {
                buttons: true,
                dangerMode: true
            });
            if( confirm ) {
                try {
                    await this.showLoading();
                    var res = await api.put('/colors', {
                        id: $id,
                        active: status
                    });
                    this.colors.data.splice( $index, 1 );

                    if( status ) {
                        swal('Color collection has been moved to active.', { icon: 'success' });
                    } else {
                        swal('Color collection has been moved to inactive.', { icon: 'success' });
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
                    await api.delete(`/colors?id=${$id}`);
                    this.colors.data.splice( $index, 1 );
                    swal('Color collection has been completely removed.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        }
    }
});