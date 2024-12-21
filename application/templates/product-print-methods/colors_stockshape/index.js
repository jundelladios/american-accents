var printMethodColorStockshape = {
    data: function() {
        return {
            pcolorstockshape: {
                data: [],
                loading: true,
                loadingNext: false,
                input: {
                    product_print_method_id: null,
                    image: [],
                    idea_galleries: [],
                    imgformkey: null,
                    priority: 1,
                    index: null,
                    templates: [],
                    id: null,
                    autoassignimg: 1,
                    autoassignidea: 1,
                    product_color_id: null,
                    product_stockshape_id: null,
                    vdsproductid: null,
                    vdsid: null,
                },
                colors: {
                    data: [],
                    loading: false
                },
                stockshape: {
                    data: [],
                    loading: false
                },
                query: null,
                pagination: {
                    page: 1,
                    limit: 60,
                    metas: {}
                }
            },
            templatedrag: false,
            imagedrag: false,
            ideagallerydrag: false,
            pcolorstockshapestate: 'lists',
            generateStates: {
                colors: {
                    data: [],
                    loading: false,
                    next: true,
                    page: 1
                },
                stockshape: {
                    data: [],
                    loading: false,
                    next: true,
                    page: 1
                },
                data: []
            }
        }
    },
    computed: {
        pcolorstockselectedcombo() {
            if( this.pcolorstockshape.input.product_print_method_id ) {
                return this.combos.data.find(row => row.hid == this.pcolorstockshape.input.product_print_method_id );
            }
            return null;
        },
        stcclr_apiParams() {
            var templates = this.pcolorstockshape.input.templates.filter( row => row.link && row.preview ).map( row => {
                row.previewImage=null;
                return row;
            });
            var images = this.pcolorstockshape.input.image.filter( row => row.image ).map( row => {
                row.imageData=null;
                return row;
            });
            var idea_galleries = this.pcolorstockshape.input.idea_galleries.filter( row => row.image ).map( row => {
                row.imageData=null;
                row.finalLink=null;
                return row;
            });
            return {
                ...this.pcolorstockshape.input,
                templates: JSON.stringify(templates),
                image: JSON.stringify(images),
                idea_galleries: JSON.stringify(idea_galleries)
            }
        },
        stcclr_selectedColor() {
            if( this.pcolorstockshape.input.product_color_id ) { 
                const data = this.pcolorstockshape.colors.data.find(row => row.hid == this.pcolorstockshape.input.product_color_id);
                return data;
            }
            return null;
        },
        stcclr_selectedStockShape() {
            if( this.pcolorstockshape.input.product_stockshape_id ) { 
                const data = this.pcolorstockshape.stockshape.data.find(row => row.hid == this.pcolorstockshape.input.product_stockshape_id);
                return data;
            }
            return null;
        },
        getIsSelectedColorStockshape() {
            return this.pcolorstockshape.data.filter(row => row._isSelected == true);
        },
        pcolorstockshape_getIsSelectedImage() {
            return this.pcolorstockshape.input.image.filter(row => row._isSelected == true);
        },
        pcolorstockshape_getIsSelectedIdeaGallery() {
            return this.pcolorstockshape.input.idea_galleries.filter(row => row._isSelected == true);
        },
        pcolorstockshape_getIsSelectedTemplates() {
            return this.pcolorstockshape.input.templates.filter(row => row._isSelected == true);
        },
    },
    watch: {
        'pcolorstockshape.input.product_print_method_id': async function($id) {
            if(!$id) { return false; }
            await this.getComboStockShapes();
            await this.getComboColors();
        }
    },
    methods: {
        pcolorstockshape_toggleSelectKey($key, $toggle=true) {
            this.pcolorstockshape.input[$key] = this.pcolorstockshape.input[$key].map(row => {
                row._isSelected = $toggle;
                return row;
            });
        },
        async pcolorstockshape_removeCheckedItems_($key, $message = 'Are you sure you want to remove these selected?') {
            try {
                var confirm = await swal($message , {
                    buttons: true,
                    dangerMode: true
                });
                if(confirm) {
                    this.pcolorstockshape.input[$key] = this.pcolorstockshape.input[$key].filter(row => !row._isSelected);
                    await this.stcclr_updateProductColorStockshapeData();
                }
            } catch($e) {
                return;
            }
        },
        selectAll_color_stockshape_() {
            this.pcolorstockshape.data = this.pcolorstockshape.data.map(row => {
                row._isSelected = true;
                return row;
            });
        },
        unselectAll_color_stockshape_() {
            this.pcolorstockshape.data = this.pcolorstockshape.data.map(row => {
                row._isSelected = false;
                return row;
            });
        },
        async removeCheckedItems_color_stockshape_() {
            try {
                var confirm = await swal('Are you sure you want to remove selected color + stockshape?', {
                    buttons: true,
                    dangerMode: true
                });
                if(confirm) {
                    await api.delete(`/product_colors_stockshape`, {
                        params: {
                            ids: this.getIsSelectedColorStockshape.map(row => row.id).join(',')
                        }
                    });
                    await this.getColorStockShapesByProductId(this.pcolorstockshape.input.product_print_method_id);
                }
            } catch($e) {
                return;
            }
        },
        async getComboColors() {
            try {
                this.pcolorstockshape.colors.loading = true;
                const res = await api.get(`/product-colors?product_print_method_id=${this.pcolorstockshape.input.product_print_method_id}`);
                this.pcolorstockshape.colors.data = res.data.data;
                this.pcolorstockshape.colors.loading = false;
            } catch($e) {
                this.pcolorstockshape.colors.loading = false;
                return;
            }
        },
        async getComboStockShapes() {
            try {
                this.pcolorstockshape.stockshape.loading = true;
                const res = await api.get(`/product-stockshapes?product_print_method_id=${this.pcolorstockshape.input.product_print_method_id}`);
                this.pcolorstockshape.stockshape.data = res.data.data;
                this.pcolorstockshape.stockshape.loading = false;
            } catch($e) {
                this.pcolorstockshape.stockshape.loading = false;
                return;
            }
        },
        stcclr_choosecolorstockshapeimage(index) {
            var $e = this;
            this.chooseLibrary( `Choose Product Color Image`, (url, obj) => {
                $e.pcolorstockshape.input.image[index].image = url;
                $e.pcolorstockshape.input.image[index].title = obj.title;
                var fileExt = url.split('.').pop();
                $e.pcolorstockshape.input.image[index].type = fileExt;
            }, {
                library: {
                    type: ['image', 'text/html']
                },
            });
        },
        // product colors
        stcclr_addcolorimage() {
            this.pcolorstockshape.input.image.push({
                image: ``,
                title: ``,
                type: ``
            });
        },
        stcclr_addIdeaGallery() {
            this.pcolorstockshape.input.idea_galleries.push({
                text: ``,
                image: ``,
                downloadLink: ``,
                usecurfile: 1,
                type: ``
            });
        },
        stcclr_addTemplates() {
            this.pcolorstockshape.input.templates.push({
                preview: ``,
                title: ``,
                link: ``
            });
        },
        async stcclr_generateColorStockshape() {
            try {

                this.showLoading('Please do not close/refresh the page.');

                await this.stcclr_getAllProductStockshapes();
                await this.stcclr_getAllProductColors();

                const e = this;
                e.generateStates.colors.data.map(colorrow => {
                    e.generateStates.stockshape.data.map(stockshaperow => {
                        e.generateStates.data.push({
                            product_print_method_id: e.pcolorstockshape.input.product_print_method_id,
                            product_color_id: colorrow.hid,
                            product_stockshape_id: stockshaperow.hid,
                            generateLabel: `${colorrow.colorname} ${stockshaperow.code}`.toUpperCase()
                        });
                    });
                });

                while(e.generateStates.data.length) {
                    await e.stcclr_executeColorStockshapeGenerate(e.generateStates.data[0]);
                }

            } catch($e) {
                console.log($e);
                return;
            }
        },
        async stcclr_executeColorStockshapeGenerate($param) {
            const e = this;
            try {

                e.showLoading(`Generating ${$param.generateLabel.toUpperCase()}...`);
                await api.post(`/product_colors_stockshape/generate`, $param);
                e.generateStates.data.splice(0, 1);

                if( !e.generateStates.data.length ) {
                    await swal('Color + Stock shape has been completey generated.');
                    await e.getColorStockShapesByProductId(e.pcolorstockshape.input.product_print_method_id);
                }

            } catch($e) {
                return;
            }
        },
        async stcclr_getAllProductColors() {
            try {
                while(this.generateStates.colors.next) {
                    const res = await api.get(`/product-colors/`, {
                        params: {
                            product_print_method_id: this.pcolorstockshape.input.product_print_method_id,
                            pagination: true,
                            limit: 20,
                            page: this.generateStates.colors.page,
                        }
                    });
                    this.generateStates.colors.page++;
                    this.generateStates.colors.data = [...this.generateStates.colors.data, ...res.data.data];
                    this.generateStates.colors.next = res.data.next_page_url;
                }
            } catch($e) {
                return;
            }
        },
        async stcclr_getAllProductStockshapes() {
            try {
                while(this.generateStates.stockshape.next) {
                    const res = await api.get(`/product-stockshapes/`, {
                        params: {
                            product_print_method_id: this.pcolorstockshape.input.product_print_method_id,
                            pagination: true,
                            limit: 20,
                            page: this.generateStates.stockshape.page,
                        }
                    });
                    this.generateStates.stockshape.page++;
                    this.generateStates.stockshape.data = [...this.generateStates.stockshape.data, ...res.data.data];
                    this.generateStates.stockshape.next = res.data.next_page_url;
                }
            } catch($e) {
                return;
            }
        },
        stcclr_selectTemplateLink(index) {
            var $e = this;
            this.chooseLibrary( `Choose PDF File`, async (url, obj) => {
                try {
                    await this.showLoading('Generating PDF to Image...');
                    // generate pdf to image
                    var generateToImagePDF = await api.post(`/pdftoimage`, {
                        id: obj.id
                    });

                    $e.pcolorstockshape.input.templates[index].title = obj.title;
                    $e.pcolorstockshape.input.templates[index].link = url;
                    $e.pcolorstockshape.input.templates[index].preview = generateToImagePDF.data.image;
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
        stcclr_ideagallerydownloadfileselect(index) {
            var $e = this;
            this.chooseLibrary( `Select Idea gallery downloadable file`, url => {
                $e.pcolorstockshape.input.idea_galleries[index].downloadLink = url;
            });
        },
        resetColorsStockshapeInputs($args) {
            this.pcolorstockshape.input = {
                product_print_method_id: null,
                image: [],
                idea_galleries: [],
                imgformkey: null,
                priority: 1,
                index: null,
                templates: [],
                id: null,
                autoassignimg: null,
                autoassignidea: null,
                product_color_id: null,
                product_stockshape_id: null,
                ...$args
            }
            this.fixValidator();
        },
        stcclr_chooseideagalleryimage(index) {
            var $e = this;
            this.chooseLibrary( `Choose Idea gallery image`, (url, obj) => {
                $e.pcolorstockshape.input.idea_galleries[index].image = url;
                $e.pcolorstockshape.input.idea_galleries[index].text = obj.title;
                var fileExt = url.split('.').pop();
                $e.pcolorstockshape.input.idea_galleries[index].type = fileExt;
            }, {
                library: {
                    type: ['image', 'text/html']
                },
            });
        },
        async stcclr_saveProductColorStockshape() {
            var valid = await this.$validator.validateAll('productcolorstockshape');
            if(!valid) return;

            if( this.pcolorstockshape.input.hid ) {
                await this.stcclr_updateProductColorStockshapeData();
            } else {
                await this.stcclr_addProductColorStockshapeData();
            }
        },
        async stcclr_addProductColorStockshapeData() {
            try {
                await this.showLoading();
                const res = await api.post(`/product_colors_stockshape`, this.stcclr_apiParams);
                await swal('New color has been added.', { icon: 'success' });

                this.pcolorstockshape.data.unshift(res.data);

                this.resetColorsStockshapeInputs({product_print_method_id:this.pcolorstockshape.input.product_print_method_id});
                this.pcolorstockshapestate = 'lists';
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async stcclr_updateProductColorStockshapeData() {
            try {
                await this.showLoading();
                const res = await api.put(`/product_colors_stockshape`, this.stcclr_apiParams);
                await swal('Saved', { icon: 'success' });

                const updatedIndex = this.pcolorstockshape.data.findIndex(row => row.hid == res.data.hid);
                this.$set(
                    this.pcolorstockshape.data,
                    updatedIndex,
                    res.data
                );

                this.resetColorsStockshapeInputs({product_print_method_id:this.pcolorstockshape.input.product_print_method_id});
                this.pcolorstockshapestate = 'lists';
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async getColorStockShapesByProductId(id) {
            try {
                await this.showLoading('Loading...');
                this.pcolorstockshape.pagination.page = 1;
                this.pcolorstockshape.loading = true;
                const res = await api.get(`/product_colors_stockshape`, {
                    params: {
                        product_print_method_id: id,
                        pagination: true,
                        limit: this.pcolorstockshape.pagination.limit,
                        page: this.pcolorstockshape.pagination.page,
                        orderBy: 'colorname ASC,code ASC',
                        query: this.pcolorstockshape.query
                    }
                });
                this.pcolorstockshape.input.product_print_method_id = id;
                await swal.close();
                this.pcolorstockshapestate = 'lists';
                this.pcolorstockshape.data = res.data.data;
                this.pcolorstockshape.pagination.metas = { ...res.data, data: null };
                this.pcolorstockshape.loading = false;
            } catch($e) { console.log($e);
                this.pcolorstockshape.loading = false;
                this.backEnd($e);    
                return; 
            }
        },
        async getColorStockshapeNextButton() {
            try {
                this.pcolorstockshape.pagination.page++;
                this.pcolorstockshape.loadingNext = true;
                const res = await api.get(`/product_colors_stockshape`, {
                    params: {
                        product_print_method_id: this.pcolorstockshape.input.product_print_method_id,
                        pagination: true,
                        limit: this.pcolorstockshape.pagination.limit,
                        page: this.pcolorstockshape.pagination.page,
                        orderBy: 'colorname ASC,code ASC',
                        query: this.pcolorstockshape.query
                    }
                });
                this.pcolorstockshape.data = [...this.pcolorstockshape.data, ...res.data.data];
                this.pcolorstockshape.pagination.metas = { ...res.data, data: null };
                this.pcolorstockshape.loadingNext = false;
            } catch($e) { 
                this.pcolorstockshape.loadingNext = false;
                this.backEnd($e);    
                return; 
            }
        },
        async stcclr_removeproductColorStockShape(id, index) {
            var confirm = await swal('Are you sure you want to remove this color + stockshape?', {
                buttons: true,
                dangerMode: true
            });
            if( confirm ) {
                await this.showLoading();
                await api.delete(`/product_colors_stockshape?id=${id}`);
                this.pcolorstockshape.data.splice(index, 1);
                await swal('Sucessfully Removed');
            }
        },
        searchEntryColorStockshapeQueryFilter() {
            this.pcolorstockshape.data=[];
            this.getColorStockShapesByProductId(this.pcolorstockshape.input.product_print_method_id);
        }
    }
}