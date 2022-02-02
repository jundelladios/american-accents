var inputs = {
    id: null,
    index: null,
    clipartcategory: null,
    clipartdata: [],
    priority: 1
}

var clipartsVueInstance = new Vue({
    el: '#clipArtsController',
    mixins: [mixVue],
    data: function() {
        return { 
            form: false,
            inputs: {...inputs},
            clipart: {
                loading: false,
                data: []
            },
            search: null,
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
            return {...inputs};
        },
        inputParams() {
            return {...this.inputs, clipartdata: this.jsonToString(this.inputs.clipartdata.filter(row => row.image))};
        },
        getPaginationCount() {
            return Math.ceil( this.pagination.metas.total/ this.pagination.limit );
        }
    },
    async mounted() {
        this.loadClipArts();
    },
    methods: {
        async loadClipArts($args) {
            try {
                this.clipart.loading = true;
                const res = await api.get(`/clip-arts`, {
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
                this.clipart.data = res.data.data;
                this.pagination.metas = { ...res.data, data: null };
                this.clipart.loading = false;
            } catch($e) {
                this.clipart.loading = false;
                return;
            }
        },
        async paginateClipArts(page) {
            this.pagination.page = page;
            await this.loadClipArts();
        },
        formInputs(form = false, inputs = {...inputs}, appendUrl = "") {
            this.form = form;
            this.inputs = inputs;
            $route_change(["_id"], appendUrl);
            this.fixValidator();
        },
        async saveClipArt() {
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
                const res = await api.put(`/clip-arts`, {
                    ...this.inputParams,
                    id: this.inputParams.hid
                });
                this.clipart.data[this.inputs.index] = res.data;
                this.formInputs(false, this.defaultValue);
                swal('Saved', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
            }
        },
        async insertData() {
            try {
                await this.showLoading();
                const res = await api.post(`/clip-arts`, {
                    ...this.inputParams
                });
                this.clipart.data.push(res.data);
                this.formInputs(false, this.defaultValue);
                swal('New Clipart has been added.', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
            }
        },
        addClipArtIcon() {
            this.inputs.clipartdata.push({
                title: ``,
                image: ``
            });
        },
        selectclipimage(index) {
            var $e = this;
            this.chooseLibrary( `Choose Clip Art Image`, (url) => {
                $e.inputs.clipartdata[index].image = url;
            });
        },
        removeclipart(index) {
            this.inputs.clipartdata.splice(index, 1);
        },
        toggleActive() {
            this.inactive=!this.inactive;
            this.loadClipArts();
        },
        sorting() {
            this.sort = this.sort == 'asc' ? 'desc' : 'asc';
            this.loadClipArts();
        },
        async updateStatus( $id, $index, status ) {
            var confirm = await swal('Are you sure you want to remove this?', {
                buttons: true,
                dangerMode: true
            });
            if( confirm ) {
                try {
                    await this.showLoading();
                    var res = await api.put('/clip-arts', {
                        id: $id,
                        active: status
                    });
                    this.clipart.data.splice( $index, 1 );

                    if( status ) {
                        swal('Clip art has been moved to active.', { icon: 'success' });
                    } else {
                        swal('Clip art has been moved to inactive.', { icon: 'success' });
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
                    await api.delete(`/clip-arts?id=${$id}`);
                    this.clipart.data.splice( $index, 1 );
                    swal('Clip art has been completely removed.', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        }
    }
});