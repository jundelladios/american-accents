var productStockShapes = {
    data: function() {
        return {
            stockshape: {
                data: [],
                loading: true,
                loadingNext: false,
                input: {
                    product_print_method_id: null,
                    image: [],
                    idea_galleries: [],
                    code: null,
                    stockname: null,
                    priority: 1,
                    index: null,
                    templates: [],
                    id: null,
                    autoassignimg: null,
                    autoassignidea: null,
                    vdsproductid: null,
                    vdsid: null,
                    in_stock: 1
                },
                query: null,
                pagination: {
                    page: 1,
                    limit: 60,
                    metas: {}
                }
            },
            stockshapegenerateCollection: null,
            stockshapeDrag: {
                templatedrag: false,
                imagedrag: false,
                ideagallerydrag: false,
            },
            stockshapecollections: {
                loading: false,
                data: []
            },
            pstockshapestate: 'lists',
            stockshapesQueue: []
        }
    },
    computed: {
        stockshapeselectedcombo() {
            if( this.stockshape.input.product_print_method_id ) {
                return this.combos.data.find(row => row.hid == this.stockshape.input.product_print_method_id );
            }
            return null;
        },
        stockshapeapiParams() {
            var templates = this.stockshape.input.templates.filter( row => row.link && row.preview ).map( row => {
                row.previewImage=null;
                return row;
            });
            var images = this.stockshape.input.image.filter( row => row.image ).map( row => {
                row.imageData=null;
                return row;
            });
            var idea_galleries = this.stockshape.input.idea_galleries.filter( row => row.image ).map( row => {
                row.imageData=null;
                row.finalLink=null;
                return row;
            });
            return {
                ...this.stockshape.input,
                templates: JSON.stringify(templates),
                image: JSON.stringify(images),
                idea_galleries: JSON.stringify(idea_galleries)
            }
        },
        getIsSelectedStockshape() {
            return this.stockshape.data.filter(row => row._isSelected == true);
        },
        pstockshape_getIsSelectedImage() {
            return this.stockshape.input.image.filter(row => row._isSelected == true);
        },
        pstockshape_getIsSelectedIdeaGallery() {
            return this.stockshape.input.idea_galleries.filter(row => row._isSelected == true);
        },
        pstockshape_getIsSelectedTemplates() {
            return this.stockshape.input.templates.filter(row => row._isSelected == true);
        },
    },
    watch: {
        stockshapegenerateCollection: async function(value) {
            var confirm = await swal('Generate stock shape from stock shape collection.', {
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
                    this.pstockshapeGenerateExecute(value);
                break;

            }

        }
    },
    async mounted() {
        await this.getStockShapeCollections();
    },
    methods: {
        async pstockshapeGenerateExecute(value) {
            try {
                const e = this;
                let collections = e.stockshapecollections.data.find(row => row.hid == value);
               if(collections) {
                   this.showLoading('Please do not close/refresh the page.');
                    e.stockshapesQueue = [...collections.collections];
                    while(e.stockshapesQueue.length) {
                        await e.executeStockshapeGenerate(e.stockshapesQueue[0]);
                    }
               }
            } catch($e) {
                console.log($e);
                return;
            }
        },
        async executeStockshapeGenerate(shape) {
            const e = this;
            try {
                $args = {
                    code: shape.code,
                    product_print_method_id: this.stockshape.input.product_print_method_id
                };

                var generateLabel = '';
                if(shape.stockname) {
                    $args.stockname = shape.stockname;
                    generateLabel += shape.stockname + ' - ';
                }

                generateLabel += shape.code;

                this.showLoading(`Generating ${generateLabel.toUpperCase()}...`);

                await api.post(`/product-stockshapes/generate`, $args);
                e.stockshapesQueue.splice(0, 1);

                if( !e.stockshapesQueue.length ) {
                    await swal('Stock shape has been completey generated.');
                    await this.getStockShapesByProductId(this.stockshape.input.product_print_method_id);
                }
            } catch($e) {
                console.log($e);
                return;
            }
        },
        pstockshape_toggleSelectKey($key, $toggle=true) {
            this.stockshape.input[$key] = this.stockshape.input[$key].map(row => {
                row._isSelected = $toggle;
                return row;
            });
        },
        async pstockshape_removeCheckedItems_($key, $message = 'Are you sure you want to remove these selected?') {
            try {
                var confirm = await swal($message , {
                    buttons: true,
                    dangerMode: true
                });
                if(confirm) {
                    this.stockshape.input[$key] = this.stockshape.input[$key].filter(row => !row._isSelected);
                    await this.updateProductStockShapeData();
                }
            } catch($e) {
                return;
            }
        },
        selectAll_stockshape_() {
            this.stockshape.data = this.stockshape.data.map(row => {
                row._isSelected = true;
                return row;
            });
        },
        unselectAll_stockshape_() {
            this.stockshape.data = this.stockshape.data.map(row => {
                row._isSelected = false;
                return row;
            });
        },
        async removeCheckedItems_stockshape_() {
            try {
                var confirm = await swal('Are you sure you want to remove selected stockshapes?', {
                    buttons: true,
                    dangerMode: true
                });
                if(confirm) {
                    await api.delete(`/product-stockshapes`, {
                        params: {
                            ids: this.getIsSelectedStockshape.map(row => row.id).join(',')
                        }
                    });
                    const e = this;
                    e.getIsSelectedStockshape.map((row) => {
                        const indexer = e.stockshape.data.findIndex(x => x.id == row.id);
                        e.stockshape.data.splice(indexer, 1);
                    });

                    await this.getStockShapesByProductId(this.stockshape.input.product_print_method_id);
                }
            } catch($e) {
                return;
            }
        },
        stockshapeaddcolorimage() {
            this.stockshape.input.image.push({
                image: ``,
                title: ``,
                type: ``
            });
        },
        stockshapeaddIdeaGallery() {
            this.stockshape.input.idea_galleries.push({
                text: ``,
                image: ``,
                downloadLink: ``,
                usecurfile: 1,
                type: ``
            });
        },
        stockshapeaddTemplates() {
            this.stockshape.input.templates.push({
                preview: ``,
                title: ``,
                link: ``
            });
        },
        async generateStockShapes($args) {
            try {
                await this.showLoading();
                const res = await api.post(`/product-stockshapes/generate`, {
                    product_print_method_id: this.stockshape.input.product_print_method_id,
                    ...$args
                });
                await swal('Product stock shape has been generated');
                await this.getStockShapesByProductId(this.stockshape.input.product_print_method_id);
            } catch($e) {
                this.backEnd($e);
            }
        },
        stockshapeselectTemplateLink(index) {
            var $e = this;
            this.chooseLibrary( `Choose PDF File`, async (url, obj) => {
                try {
                    await this.showLoading('Generating PDF to Image...');
                    // generate pdf to image
                    var generateToImagePDF = await api.post(`/pdftoimage`, {
                        id: obj.id
                    });

                    $e.stockshape.input.templates[index].title = obj.title;
                    $e.stockshape.input.templates[index].link = url;
                    $e.stockshape.input.templates[index].preview = generateToImagePDF.data.image;
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
        stockshapeideagallerydownloadfileselect(index) {
            var $e = this;
            this.chooseLibrary( `Select Idea gallery downloadable file`, url => {
                $e.stockshape.input.idea_galleries[index].downloadLink = url;
            });
        },
        resetStockShapesInputs($args) {
            this.stockshape.input = {
                product_print_method_id: null,
                image: [],
                stockname: null,
                code: null,
                priority: 1,
                index: null,
                templates: [],
                id: null,
                idea_galleries: [],
                autoassignimg: null,
                autoassignidea: null,
                ...$args
            }
            this.fixValidator();
        },
        stockshapechoosecolorimage(index) {
            var $e = this;
            this.chooseLibrary( `Choose Product Stock Shape Image`, (url, obj) => {
                $e.stockshape.input.image[index].image = url;
                $e.stockshape.input.image[index].title = obj.title;
                var fileExt = url.split('.').pop();
                $e.stockshape.input.image[index].type = fileExt;
            }, {
                library: {
                    type: ['image', 'text/html']
                },
            });
        },
        stockshapechooseideagalleryimage(index) {
            var $e = this;
            this.chooseLibrary( `Choose Idea gallery image`, (url, obj) => {
                $e.stockshape.input.idea_galleries[index].image = url;
                $e.stockshape.input.idea_galleries[index].text = obj.title;
                var fileExt = url.split('.').pop();
                $e.stockshape.input.idea_galleries[index].type = fileExt;
            }, {
                library: {
                    type: ['image', 'text/html']
                },
            });
        },
        async pullAnimatedMediasStockShape(param) {
            var confirm = await swal('Are you sure you want to pull animated medias?', {
                buttons: true,
            });
            if( confirm ) {
                const $e = this;
                await this.showLoading();
                const res = await api.post(`/animated-medias`, param);
                const pulltype = param?.type == 'ig' ? "idea_galleries": "image";
                res.data.map(x => {
                    const isExists = $e.stockshape.input[pulltype].find(pt => pt.image == x.meta_file);
                    if(!isExists) {
                        var fileExt = x.meta_file.split('.').pop();
                        $e.stockshape.input[pulltype].push({
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
            }
        },
        async saveProductStockShape() {
            var valid = await this.$validator.validateAll('productstockshape');
            if(!valid) return;

            if( this.stockshape.input.hid ) {
                await this.updateProductStockShapeData();
            } else {
                await this.addProductStockShapeData();
            }
        },
        async addProductStockShapeData() {
            try {
                await this.showLoading();
                const res = await api.post(`/product-stockshapes`, this.stockshapeapiParams);
                await swal('New stock shape has been added.', { icon: 'success' });

                this.stockshape.data.unshift(res.data);

                this.resetStockShapesInputs({product_print_method_id:this.stockshape.input.product_print_method_id});
                this.pstockshapestate = 'lists';
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async updateProductStockShapeData() {
            try {
                await this.showLoading();
                const res = await api.put(`/product-stockshapes`, this.stockshapeapiParams);
                await swal('Saved', { icon: 'success' });

                const updatedIndex = this.stockshape.data.findIndex(row => row.hid == res.data.hid);
                this.$set(
                    this.stockshape.data,
                    updatedIndex,
                    res.data
                );

                this.resetStockShapesInputs({product_print_method_id:this.stockshape.input.product_print_method_id});
                this.pstockshapestate = 'lists';
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async getStockShapesByProductId(id) {
            try {
                await this.showLoading('Loading Product Stock Shapes...');
                this.stockshape.loading = true;
                this.stockshape.pagination.page = 1;
                const res = await api.get(`/product-stockshapes`, {
                    params: {
                        product_print_method_id: id,
                        pagination: true,
                        limit: this.stockshape.pagination.limit,
                        page: this.stockshape.pagination.page,
                        query: this.stockshape.query
                    }
                });
                this.stockshape.input.product_print_method_id = id;
                await swal.close();
                this.pstockshapestate = 'lists';
                this.stockshape.data = res.data.data;
                this.stockshape.pagination.metas = { ...res.data, data: null };
                this.stockshape.loading = false;
            } catch($e) { 
                this.stockshape.loading = false;
                this.backEnd($e);    
                return; 
            }
        },
        async getStockshapeNextButton() {
            try {
                this.stockshape.pagination.page++;
                this.stockshape.loadingNext = true;
                const res = await api.get(`/product-stockshapes`, {
                    params: {
                        product_print_method_id: this.stockshape.input.product_print_method_id,
                        pagination: true,
                        limit: this.stockshape.pagination.limit,
                        page: this.stockshape.pagination.page,
                        query: this.stockshape.query
                    }
                });
                this.stockshape.data = [...this.stockshape.data, ...res.data.data];
                this.stockshape.pagination.metas = { ...res.data, data: null };
                this.stockshape.loadingNext = false;
            } catch($e) { 
                this.stockshape.loadingNext = false;
                this.backEnd($e);    
                return; 
            }
        },
        async getStockShapeCollections() {
            try {
                this.stockshapecollections.loading = true;
                const res = await api.get(`/collections/stock-shape`);
                this.stockshapecollections.data = res.data.data;
                this.stockshapecollections.loading = false;
            } catch($e) {
                this.stockshapecollections.loading = false;
                return;
            }
        },
        async removeproductStockShape(id, index) {
            var confirm = await swal('Are you sure you want to remove this stock shape?', {
                buttons: true,
                dangerMode: true
            });
            if( confirm ) {
                await this.showLoading();
                await api.delete(`/product-stockshapes?id=${id}`);
                this.stockshape.data.splice(index, 1);
                await swal('Sucessfully Removed');
            }
        },
        searchEntryStockshapeQueryFilter() {
            this.stockshape.data = [];
            this.getStockShapesByProductId(this.stockshape.input.product_print_method_id);
        }
    }
}