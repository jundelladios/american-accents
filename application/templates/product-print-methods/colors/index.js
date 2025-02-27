var printMethodColors = {
    data: function() {
        return {
            pcolors: {
                data: [],
                loading: true,
                loadingNext: false,
                input: {
                    product_print_method_id: null,
                    image: [],
                    idea_galleries: [],
                    imgformkey: null,
                    colorhex: null,
                    colorname: null,
                    priority: 1,
                    index: null,
                    iscolorimage: 0,
                    colorimageurl: null,
                    templates: [],
                    isavailable: 1,
                    id: null,
                    pantone: null,
                    autoassignimg: 1,
                    autoassignidea: 1,
                    vdsid: null,
                    vdsproductid: null,
                    in_stock: 1
                },
                query: null,
                pagination: {
                    page: 1,
                    limit: 60,
                    metas: {}
                }
            },
            generateCollection: null,
            templatedrag: false,
            imagedrag: false,
            ideagallerydrag: false,
            colors_collections: {
                loading: false,
                data: []
            },
            pcolorstate: 'lists',
            collectionsQueue: []
        }
    },
    computed: {
        pcolorselectedcombo() {
            if( this.pcolors.input.product_print_method_id ) {
                return this.combos.data.find(row => row.hid == this.pcolors.input.product_print_method_id );
            }
            return null;
        },
        colorapiParams() {
            var templates = this.pcolors.input.templates.filter( row => row.link && row.preview ).map( row => {
                row.previewImage=null;
                row._isSelected=null;
                return row;
            });
            var images = this.pcolors.input.image.filter( row => row.image ).map( row => {
                row.imageData=null;
                row._isSelected=null;
                return row;
            });
            var idea_galleries = this.pcolors.input.idea_galleries.filter( row => row.image ).map( row => {
                row.imageData=null;
                row.finalLink=null;
                row._isSelected=null;
                return row;
            });
            return {
                ...this.pcolors.input,
                templates: JSON.stringify(templates),
                image: JSON.stringify(images),
                idea_galleries: JSON.stringify(idea_galleries)
            }
        },
        pcolors_getIsSelectedImage() {
            return this.pcolors.input.image.filter(row => row._isSelected == true);
        },
        pcolors_getIsSelectedIdeaGallery() {
            return this.pcolors.input.idea_galleries.filter(row => row._isSelected == true);
        },
        pcolors_getIsSelectedTemplates() {
            return this.pcolors.input.templates.filter(row => row._isSelected == true);
        },
        getIsSelectedColor() {
            return this.pcolors.data.filter(row => row._isSelected == true);
        }
    },
    watch: {
        generateCollection: async function(value) {
            var confirm = await swal('Generate color from color collection.', {
                buttons: {
                    cancel: true,
                    generate: {
                        text: "Generate",
                        value: "generate"
                    }
                }
            });

            switch(confirm) {

                case 'generate':
                    this.pcolorsGenerateExecute(value);
                break;

            }

        }
    },
    async mounted() {
        await this.getColorCollections();
    },
    methods: {
        async pcolorsGenerateExecute(value) {
            try {
                const e = this;
                let collections = e.colors_collections.data.find(row => row.hid == value);
               if(collections) {
                   this.showLoading('Please do not close/refresh the page.');
                    e.collectionsQueue = [...collections.collections];
                    while(e.collectionsQueue.length) {
                        await e.executeColorGenerate(e.collectionsQueue[0]);
                    }
               }
            } catch($e) {
                console.log($e);
            }
        },
        async executeColorGenerate(color) {
            const e = this;
            try {
                $args = {
                    colorname: color.name,
                    colorhex: color.hex,
                    product_print_method_id: this.pcolors.input.product_print_method_id
                };

                if(color.isImage) {
                    $args.iscolorimage = 1;
                }

                if(color.image) {
                    $args.colorimageurl = color.image;
                }

                if(color.pantone) {
                    $args.pantone = color.pantone;
                }

                this.showLoading(`Generating ${color.name.toUpperCase()}...`);

                await api.post(`/product-colors/generate`, $args);
                e.collectionsQueue.splice(0, 1);

                if( !e.collectionsQueue.length ) {
                    await swal('Colors has been completey generated.');
                    await this.getColorByProductId(this.pcolors.input.product_print_method_id);
                }
            } catch($e) {
                console.log($e);
                return;
            }
        },
        pcolors_toggleSelectKey($key, $toggle=true) {
            this.pcolors.input[$key] = [...this.pcolors.input[$key].map(row => {
                row._isSelected = $toggle;
                return row;
            })];
        },
        async pcolors_removeCheckedItems_($key, $message = 'Are you sure you want to remove these selected?') {
            try {
                var confirm = await swal($message , {
                    buttons: true,
                    dangerMode: true
                });
                if(confirm) {
                    this.pcolors.input[$key] = this.pcolors.input[$key].filter(row => !row._isSelected);
                    await this.updateProductColorData();
                }
            } catch($e) {
                return;
            }
        },
        selectAll_() {
            this.pcolors.data = this.pcolors.data.map(row => {
                row._isSelected = true;
                return row;
            });
        },
        unselectAll_() {
            this.pcolors.data = this.pcolors.data.map(row => {
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
                    await api.delete(`/product-colors`, {
                        params: {
                            ids: this.getIsSelectedColor.map(row => row.id).join(',')
                        }
                    });
                    const e = this;
                    e.getIsSelectedColor.map((row) => {
                        const indexer = e.pcolors.data.findIndex(x => x.id == row.id);
                        e.pcolors.data.splice(indexer, 1);
                    });


                    await this.getColorByProductId(this.pcolors.input.product_print_method_id);
                }
            } catch($e) {
                return;
            }
        },
        // product colors
        addcolorimage() {
            this.pcolors.input.image.push({
                image: ``,
                title: ``,
                type: ``
            });
        },
        addIdeaGallery() {
            this.pcolors.input.idea_galleries.push({
                text: ``,
                image: ``,
                downloadLink: ``,
                usecurfile: 1,
                type: ``
            });
        },
        addTemplates() {
            this.pcolors.input.templates.push({
                preview: ``,
                title: ``,
                link: ``
            });
        },
        async generatecolors($args) {
            try {
                await this.showLoading();
                const res = await api.post(`/product-colors/generate`, {
                    product_print_method_id: this.pcolors.input.product_print_method_id,
                    ...$args
                });
                await swal('Product colors has been generated');
                await this.getColorByProductId(this.pcolors.input.product_print_method_id);
            } catch($e) {
                this.backEnd($e);
            }
        },
        selectTemplateLink(index) {
            var $e = this;
            this.chooseLibrary( `Choose PDF File`, async (url, obj) => {
                try {
                    await this.showLoading('Generating PDF to Image...');
                    // generate pdf to image
                    var generateToImagePDF = await api.post(`/pdftoimage`, {
                        id: obj.id
                    });

                    $e.pcolors.input.templates[index].title = obj.title;
                    $e.pcolors.input.templates[index].link = url;
                    $e.pcolors.input.templates[index].preview = generateToImagePDF.data.image;
                    await swal.close();
                } catch($e) {
                    this.backEnd($e);
                }
            }, {
                library: {
                    type: ['application/pdf']
                }
            });
        },
        selectTemplatePhoto(index) {
            var $e = this;
            this.chooseLibrary( `Select Template Image`, (url, obj) => {
                $e.pcolors.input.templates[index].preview = url;
                $e.pcolors.input.templates[index].title = obj.title;
            });
        },
        ideagallerydownloadfileselect(index) {
            var $e = this;
            this.chooseLibrary( `Select Idea gallery downloadable file`, url => {
                $e.pcolors.input.idea_galleries[index].downloadLink = url;
            });
        },
        resetColorsInputs($args) {
            this.pcolors.input = {
                product_print_method_id: null,
                image: [],
                imgformkey: null,
                colorhex: null,
                colorname: null,
                priority: 1,
                index: null,
                iscolorimage: 0,
                colorimageurl: null,
                templates: [],
                isavailable: 1,
                id: null,
                pantone: null,
                idea_galleries: [],
                autoassignimg: null,
                autoassignidea: null,
                ...$args
            }
            console.log($args);
            this.fixValidator();
        },
        choosecolorimage(index) {
            var $e = this;
            this.chooseLibrary( `Choose Product Color Image`, (url, obj) => {
                $e.pcolors.input.image[index].image = url;
                $e.pcolors.input.image[index].title = obj.title;
                var fileExt = url.split('.').pop();
                $e.pcolors.input.image[index].type = fileExt;
            }, {
                library: {
                    type: ['image', 'text/html']
                },
            });
        },
        async pullAnimatedMediasColors(param) {
            var confirm = await swal('Are you sure you want to pull animated medias?', {
                buttons: true,
            });
            if( confirm ) {
                const $e = this;
                await this.showLoading();
                const res = await api.post(`/animated-medias`, param);
                const pulltype = param?.type == 'ig' ? "idea_galleries": "image";
                res.data.map(x => {
                    const isExists = $e.pcolors.input[pulltype].find(pt => pt.image == x.meta_file);
                    if(!isExists) {
                        var fileExt = x.meta_file.split('.').pop();
                        $e.pcolors.input[pulltype].push({
                            ...pulltype == "idea_galleries" && {
                                image: x.meta_file,
                                text: x.post_title,
                                type: fileExt,
                                downloadLink: "",
                                usecurfile: 0
                            },
                            ...pulltype == "image" && {
                                image: x.meta_file,
                                title: x.post_title,
                                type: fileExt
                            }
                        });
                    }
                })
                await swal('Animated medias has been pulled, please review the lists.', { icon: 'success' });
                console.log($e.pcolors.input[pulltype])
            }
        },
        chooseideagalleryimage(index) {
            var $e = this;
            this.chooseLibrary( `Choose Idea gallery image`, (url, obj) => {
                $e.pcolors.input.idea_galleries[index].image = url;
                $e.pcolors.input.idea_galleries[index].text = obj.title;
                var fileExt = url.split('.').pop();
                $e.pcolors.input.idea_galleries[index].type = fileExt;
            }, {
                library: {
                    type: ['image', 'text/html']
                },
            });
        },
        async saveProductColor() {
            var valid = await this.$validator.validateAll('productcolor');
            if(!valid) return;

            if( this.pcolors.input.hid ) {
                await this.updateProductColorData();
            } else {
                await this.addProductColorData();
            }
        },
        async addProductColorData() {
            try {
                await this.showLoading();
                const res = await api.post(`/product-colors`, this.colorapiParams);
                await swal('New color has been added.', { icon: 'success' });

                this.pcolors.data.unshift(res.data);
                
                this.resetColorsInputs({product_print_method_id:this.pcolors.input.product_print_method_id});
                this.pcolorstate = 'lists';
            } catch($e) {
                console.log($e);
                this.backEnd($e);
                return;
            }
        },
        async updateProductColorData() {
            try {
                await this.showLoading();
                const res = await api.put(`/product-colors`, this.colorapiParams);
                await swal('Saved', { icon: 'success' });

                const updatedIndex = this.pcolors.data.findIndex(row => row.hid == res.data.hid);
                this.$set(
                    this.pcolors.data,
                    updatedIndex,
                    res.data
                );

                this.resetColorsInputs({product_print_method_id:this.pcolors.input.product_print_method_id});
                this.pcolorstate = 'lists';
            } catch($e) {
                console.log($e);
                this.backEnd($e);
                return;
            }
        },
        async getColorByProductId(id) {
            try {
                await this.showLoading('Loading Product Colors...');
                this.pcolors.pagination.page = 1;
                this.pcolors.loading = true;
                const res = await api.get(`/product-colors`, {
                    params: {
                        product_print_method_id: id,
                        pagination: true,
                        limit: this.pcolors.pagination.limit,
                        page: this.pcolors.pagination.page,
                        query: this.pcolors.query
                    }
                });
                this.pcolors.input.product_print_method_id = id;
                await swal.close();
                this.pcolorstate = 'lists';
                this.pcolors.data = res.data.data;
                this.pcolors.pagination.metas = { ...res.data, data: null };
                this.pcolors.loading = false;
            } catch($e) { 
                this.pcolors.loading = false;
                this.backEnd($e);    
                return; 
            }
        },
        async getColorNextButton() {
            try {
                this.pcolors.pagination.page++;
                this.pcolors.loadingNext = true;
                const res = await api.get(`/product-colors`, {
                    params: {
                        product_print_method_id: this.pcolors.input.product_print_method_id,
                        pagination: true,
                        limit: this.pcolors.pagination.limit,
                        page: this.pcolors.pagination.page,
                        query: this.pcolors.query
                    }
                });
                this.pcolors.data = [...this.pcolors.data, ...res.data.data];
                this.pcolors.pagination.metas = { ...res.data, data: null };
                this.pcolors.loadingNext = false;
            } catch($e) { 
                this.pcolors.loadingNext = false;
                this.backEnd($e);    
                return; 
            }
        },
        async getColorCollections() {
            try {
                this.colors_collections.loading = true;
                const res = await api.get(`/colors`);
                this.colors_collections.data = res.data.data;
                this.colors_collections.loading = false;
            } catch($e) {
                this.colors_collections.loading = false;
                return;
            }
        },
        async removeproductColor(id, index) {
            var confirm = await swal('Are you sure you want to remove this color?', {
                buttons: true,
                dangerMode: true
            });
            if( confirm ) {
                await this.showLoading();
                await api.delete(`/product-colors?id=${id}`);
                this.pcolors.data.splice(index, 1);
                await swal('Sucessfully Removed');
            }
        },
        choosecombocolorimage() {
            var $e = this;
            this.chooseLibrary( `Select combo color image`, (url) => {
                $e.pcolors.input.colorimageurl = url;
            });
        },
        searchEntryColorQueryFilter() {
            this.pcolors.data = [];
            this.getColorByProductId(this.pcolors.input.product_print_method_id);
        }
        // end of product colors
    }
}