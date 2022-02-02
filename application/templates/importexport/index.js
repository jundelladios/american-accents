var importexportInstanceVue = new Vue({
    el: '#importExportVue',
    mixins: [mixVue],
    data() {
        return {
            export: {
                products: [],
                page: 1,
                limit: 50,
                next: 1,
                imports: []
            },
            pcomboexport: {
                products: [],
                page: 1,
                limit: 50,
                next: 1,
                imports: []
            },
            plineexport: {
                entries: [],
                page: 1,
                limit: 50,
                next: 1,
                imports: []
            },
            plinepricingdata: {
                entries: [],
                page: 1,
                limit: 50,
                next: 1,
                imports: []
            }
        }
    },
    computed: {
        exportedFieldsLists() {
            return [
                'id', 
                'product_name', 
                'product_description', 
                'priority', 
                'active', 
                'product_size', 
                'product_size_details',
                'product_slug',
                'material_type',
                'product_thickness',
                'product_tickness_details',
                'specs_json',
            ];
        },
        exportedFieldComboLists() {
            return [
                'id',
                'product_method_combination_name',
                'features_options2',
                'feature_img',
                'pcombodescription',
                'pcombopriority',
                'pcomboactive',
                'specificationjsoncombo'
            ];
        },
        exportedPricingFieldsLists() {
            return [
                'quantity',
                'value',
                'asterisk',
                'alternative_value',
                'unit_value',
                'decimal_value'
            ];
        },
        exportedProductLineFieldLists() {
            return [
                'image',
                'banner_img',
                'price_tagline',
                'second_side',
                'wrap',
                'multicolor',
                'per_thousand',
                'per_item',
                'setup_charge',
                'priority',
                'active',
                'compliances'
            ];
        },
        exportedProductLinePricingDataFieldLists() {
            return [
                'priority',
                'pricing_data_sub',
                'is_additional_spot',
                'per_color',
                'per_piece',
                'per_side',
                'per_thousand',
                'note_value',
                'auto_format'
            ];
        },
        csvErrorMessage() {
            return `There is invalid data in your csv file, please check your data and try again.`;
        }
    },
    methods: {
        importProducts() {
            jQuery('#importfile').click();
        },
        importProductCombo() {
            jQuery('#importfilecombo').click();
        },
        importProductComboPricing() {
            jQuery('#importfilepricing').click();
        },
        importProductLine() {
            jQuery('#importfilepline').click();
        },
        importProductLinePricing() {
            jQuery('#importfileplinepricing').click();
        },
        importProductLinePricingData() {
            jQuery('#importfileplinepricingdata').click();
        },
        async executeImport() {
            this.export.imports = [];
            let file = document.getElementById("importfile").files[0]
            const e = this;
            Papa.parse(file, {
                download: true,
                dynamicTyping: true,
                header: true,
                complete: async function(result) {
                    e.export.imports = result.data;
                    await e.executeImportProductApi();
                }
            });
        },
        async executeImportProductApi() {
            try {
                var confirm = await swal(`Are you sure you want to import ${this.export.imports.length} of products?`, {
                    buttons: true,
                    dangerMode: false
                });

                if( !confirm ) { 
                    document.getElementById("importfile").value = "";
                    return; 
                }

                this.showLoading(`Preparing to import`);
                while( this.export.imports.length ) {
                    const imported = this.export.imports[0];
                    if( imported.id ) { 
                        this.showLoading(`Importing ${imported.product_name}...\nPlease do not close/refresh the browser.`);
                        await api.put(`/products`, imported);
                    }
                    
                    this.export.imports.splice(0, 1);

                    if( !this.export.imports.length ) {
                        await swal(`Products has been imported.`);
                        window.location.reload();
                        return;
                    }
                }
            } catch($e) {
                await swal(this.csvErrorMessage);
                window.location.reload();
                return;
            }
        },
        executeImportCombo() {
            this.pcomboexport.imports = [];
            let file = document.getElementById("importfilecombo").files[0]
            const e = this;
            Papa.parse(file, {
                download: true,
                dynamicTyping: true,
                header: true,
                complete: async function(result) {
                    e.pcomboexport.imports = result.data;
                    await e.executeImportProductComboApi();
                }
            });
        },
        async executeImportProductComboApi() {
            try {
                var confirm = await swal(`Are you sure you want to import ${this.pcomboexport.imports.length} of product combinations?`, {
                    buttons: true,
                    dangerMode: false
                });

                if( !confirm ) { 
                    document.getElementById("importfilecombo").value = "";
                    return; 
                }

                this.showLoading(`Preparing to import`);
                while( this.pcomboexport.imports.length ) {
                    const imported = this.pcomboexport.imports[0];
                    if( imported.id ) { 
                        this.showLoading(`Importing ${imported.product_method_combination_name}...\nPlease do not close/refresh the browser.`);
                        await api.put(`/products-combo`, {
                            ...imported,
                            description: imported.pcombodescription,
                            priority: imported.pcombopriority,
                            active: imported.pcomboactive,
                            specs_json: imported.specificationjsoncombo
                        });
                    }
                    
                    this.pcomboexport.imports.splice(0, 1);

                    if( !this.pcomboexport.imports.length ) {
                        await swal(`Products Combo has been imported.`);
                        window.location.reload();
                        return;
                    }
                }
            } catch($e) {
                await swal(this.csvErrorMessage);
                window.location.reload();
                return;
            }
        },
        executeImportPricing() {
            this.pcomboexport.imports = [];
            let file = document.getElementById("importfilepricing").files[0]
            const e = this;
            Papa.parse(file, {
                download: true,
                dynamicTyping: true,
                header: true,
                complete: async function(result) {
                    e.pcomboexport.imports = result.data;
                    await e.executeImportProductPricingApi();
                }
            });
        },
        async executeImportProductPricingApi() {
            try {
                var confirm = await swal(`Are you sure you want to import these pricings from product combinations?`, {
                    buttons: true,
                    dangerMode: false
                });

                if( !confirm ) { 
                    document.getElementById("importfilepricing").value = "";
                    return; 
                }

                this.showLoading(`Preparing to import`);
                while( this.pcomboexport.imports.length ) {
                    const imported = this.pcomboexport.imports[0];
                    if( imported.product_method_combination_name ) { 
                        this.showLoading(`Importing ${imported.product_method_combination_name} pricing...\nPlease do not close/refresh the browser.`);
                        await api.put(`/pricing-data/import/combo`, {
                            ...imported,
                            combination_name: imported.product_method_combination_name
                        });
                    }
                    
                    this.pcomboexport.imports.splice(0, 1);

                    if( !this.pcomboexport.imports.length ) {
                        await swal(`Products Combo Pricings has been imported.`);
                        window.location.reload();
                        return;
                    }
                }
            } catch($e) {
                await swal(this.csvErrorMessage);
                window.location.reload();
                return;
            }
        },
        async getExportedProducts() {
            try {
                const res = await api.get(`/products`, {
                    params: {
                        limit: this.export.limit,
                        pagination: true,
                        page: this.export.page
                    }
                });
                this.export.page++;
                this.export.products = [...this.export.products, ...res.data.data.map(row => {
                    const objrow = _.pick(row, this.exportedFieldsLists);
                    const ret = {
                        id: row.id,
                        ...objrow
                    };
                    return ret;
                })];
                this.export.next = res.data.next_page_url;
            } catch($e) {
                return;
            }
        },
        async exportProducts() {

            this.showLoading('Exporting...\nplease do not close/refresh the page.')

            this.export.next = 1;

            this.export.page = 1;

            this.export.products = [];

            while( this.export.next ) {
                await this.getExportedProducts();
            }

            const csvExport = Papa.unparse(this.export.products);

            await swal('Products has been exported.');

            this.exportedAutoDownload(csvExport, `products`);
        },

        async exportProductCombo(ispricing) {

            let loading = `Exporting product combinations...\nplease do not close/refresh the page`;

            if(ispricing) {

                loading = `Exporting product pricings...\nplease do not close/refresh the page`;

            }

            this.showLoading(loading);
    
            this.pcomboexport.next = 1;
    
            this.pcomboexport.page = 1;
    
            while( this.pcomboexport.next ) {
    
                await this.getExportedProductCombo();
            }

            let filename = `product-combinations`;

            let csvExport = this.pcomboexport.products.map(row => {
                const objrow = _.pick(row, this.exportedFieldComboLists);
                return objrow;
            });

            if(ispricing) {

                const pricings = [];

                csvExport = this.pcomboexport.products.map(row => {
                    row.pricings.map(pr => {
                        const pricingobj = _.pick(pr, this.exportedPricingFieldsLists);
                        pricings.push({
                            id: pr.id,
                            product_method_combination_name: row.product_method_combination_name,
                            ...pricingobj
                        });
                    });
                });

                csvExport = pricings;

                filename = `product-pricings`;

            }

            csvExport = Papa.unparse(csvExport);

            let message = `Product combo has been exported.`;

            if(ispricing) {
                
                message = `Product Pricing has been exported.`;

            }
    
            await swal(message);

            this.exportedAutoDownload(csvExport, filename);
    
    
        },
        async getExportedProductCombo() {
    
            try {
                const res = await api.get(`/public/getProducts`, {
                    params: {
                        paginate: this.pcomboexport.limit,
                        page: this.pcomboexport.page,
                        pricings: 1,
                        fields: `features_options2, product_print_method.specs_json as specificationjsoncombo, product_print_method.description as pcombodescription, product_print_method.priority as pcombopriority, product_print_method.active as pcomboactive`
                    }
                });
                this.pcomboexport.page++;
                this.pcomboexport.products = [...this.pcomboexport.products, ...res.data.data];
                console.log(this.pcomboexport.products);
                this.pcomboexport.next = res.data.next_page_url;
            } catch($e) {
                
                return;

            }

        },
        exportedAutoDownload(csvExport, filename = `exported`) {
    
            var csvdata = new Blob([csvExport], { type: 'text/csv;charset=utf-8;' })
        
            var csvurl = window.URL.createObjectURL(csvdata);
    
            var downloadlink = document.createElement('a');
    
            downloadlink.href = csvurl;
    
            downloadlink.setAttribute('download', `${filename}.csv`);
    
            downloadlink.setAttribute('style', 'display: none;');
    
            downloadlink.click();
    
            downloadlink.remove();
    
        },
        async executeImportProductLine() {
            
            this.plineexport.imports = [];
            let file = document.getElementById("importfilepline").files[0]
            const e = this;
            Papa.parse(file, {
                download: true,
                dynamicTyping: true,
                header: true,
                complete: async function(result) {
                    e.plineexport.imports = result.data;
                    await e.executeImportProductLineApi();
                }
            });

        },
        async executeImportProductLineApi() {
            try {
                var confirm = await swal(`Are you sure you want to import productlines?`, {
                    buttons: true,
                    dangerMode: false
                });

                if( !confirm ) { 
                    document.getElementById("importfilepline").value = "";
                    return; 
                }

                this.showLoading(`Preparing to import`);
                while( this.plineexport.imports.length ) {
                    const imported = this.plineexport.imports[0];
                    if( imported.subcategory && imported.printmethod ) { 
                        this.showLoading(`Importing ${imported.subcategory} ${imported.printmethod}...\nPlease do not close/refresh the browser.`);
                        await api.put(`/product-lines/import`, {
                            ...imported,
                            pnotes: imported.pricing_notes,
                            pnotes2: imported.specification_notes,
                            features_pivot: imported.properties
                        });
                    }
                    
                    this.plineexport.imports.splice(0, 1);

                    if( !this.plineexport.imports.length ) {
                        await swal(`Product Lines has been imported.`);
                        window.location.reload();
                        return;
                    }
                }
            } catch($e) {
                await swal(this.csvErrorMessage);
                window.location.reload();
                return;
            }
        },
        async executeImportProductLinePricingApi() {

            try {
                var confirm = await swal(`Are you sure you want to import productline pricings?`, {
                    buttons: true,
                    dangerMode: false
                });

                if( !confirm ) { 
                    document.getElementById("importfileplinepricing").value = "";
                    return; 
                }

                this.showLoading(`Preparing to import`);
                while( this.plinepricingdata.imports.length ) {
                    const imported = this.plinepricingdata.imports[0];
                    if( imported.subcategory && imported.printmethod && imported.chargetype ) { 
                        this.showLoading(`Importing ${imported.subcategory} ${imported.printmethod} - ${imported.chargetype} pricings...\nPlease do not close/refresh the browser.`);
                        await api.put(`/pricing-data/import/productline`, {
                            ...imported
                        });
                    }
                    
                    this.plinepricingdata.imports.splice(0, 1);

                    if( !this.plinepricingdata.imports.length ) {
                        await swal(`Product Line Pricings has been imported.`);
                        window.location.reload();
                        return;
                    }
                }
            } catch($e) {
                await swal(this.csvErrorMessage);
                window.location.reload();
                return;
            }

        },
        async executeImportProductLinePricing() {
            this.plinepricingdata.imports = [];
            let file = document.getElementById("importfileplinepricing").files[0]
            const e = this;
            Papa.parse(file, {
                download: true,
                dynamicTyping: true,
                header: true,
                complete: async function(result) {
                    e.plinepricingdata.imports = result.data;
                    console.log(result.data);
                    await e.executeImportProductLinePricingApi();
                }
            });
        },
        async executeImportProductLinePricingDataApi() {

            try {
                var confirm = await swal(`Are you sure you want to import productline pricing data?`, {
                    buttons: true,
                    dangerMode: false
                });

                if( !confirm ) { 
                    document.getElementById("importfileplinepricingdata").value = "";
                    return; 
                }

                this.showLoading(`Preparing to import`);
                while( this.plinepricingdata.imports.length ) {
                    const imported = this.plinepricingdata.imports[0];
                    if( imported.subcategory && imported.printmethod && imported.chargetype ) { 
                        this.showLoading(`Importing ${imported.subcategory} ${imported.printmethod} - ${imported.chargetype}...\nPlease do not close/refresh the browser.`);
                        await api.put(`/pricing-data/import`, {
                            ...imported
                        });
                    }
                    
                    this.plinepricingdata.imports.splice(0, 1);

                    if( !this.plinepricingdata.imports.length ) {
                        await swal(`Product Line Pricing Data's has been imported.`);
                        window.location.reload();
                        return;
                    }
                }
            } catch($e) {
                await swal(this.csvErrorMessage);
                window.location.reload();
                return;
            }

        },
        async executeImportProductLinePricingData() {

            this.plinepricingdata.imports = [];
            let file = document.getElementById("importfileplinepricingdata").files[0]
            const e = this;
            Papa.parse(file, {
                download: true,
                dynamicTyping: true,
                header: true,
                complete: async function(result) {
                    e.plinepricingdata.imports = result.data;
                    await e.executeImportProductLinePricingDataApi();
                }
            });
            
        },
        async exportProductLine() {
            this.showLoading('Exporting...\nplease do not close/refresh the page.')

            this.plineexport.next = 1;

            this.plineexport.page = 1;

            this.plineexport.entries = [];

            while( this.plineexport.next ) {
                await this.getExportedProductLines();
            }

            const csvExport = Papa.unparse(this.plineexport.entries);

            await swal('Product Lines has been exported.');

            this.exportedAutoDownload(csvExport, `product-lines`);
        },
        async getExportedProductLines() {
            try {
                const res = await api.get(`/public/getProductLines`, {
                    params: {
                        paginate: this.plineexport.limit,
                        page: this.plineexport.page,
                        pricings: 1,
                        orderby: `sub_name asc`,
                        couponcode: 1
                    }
                });
                this.plineexport.page++;
                this.plineexport.entries = [...this.plineexport.entries, ...res.data.data.map(row => {
                    const objrow = _.pick(row, this.exportedProductLineFieldLists);
                    const ret = {
                        subcategory: row.sub_name,
                        printmethod: row.pline_method_fullname,
                        ...objrow,
                        coupon: row.couponcode ? row.couponcode.code : null,
                        specification_notes: row.pnotes2,
                        pricing_notes: row.pnotes,
                        properties: row.features_pivot
                    };
                    return ret;
                })];
                this.plineexport.next = res.data.next_page_url;
            } catch($e) {
                return;
            }
        },
        async exportProductLinePricingData() {
            this.showLoading('Exporting...\nplease do not close/refresh the page.')

            this.plinepricingdata.next = 1;

            this.plinepricingdata.page = 1;

            this.plinepricingdata.entries = [];

            while( this.plinepricingdata.next ) {
                await this.getExportedProductLinePricingData();
            }

            const csvExport = Papa.unparse(this.plinepricingdata.entries);

            await swal('Product Line Pricing Data has been exported.');

            this.exportedAutoDownload(csvExport, `product-lines-pricing-data`);
        },
        async getExportedProductLinePricingData() {
            try {
                const res = await api.get(`/pricing-data`, {
                    params: {
                        paginate: this.plinepricingdata.limit,
                        page: this.plinepricingdata.page,
                        pricings: 1,
                        orderBy: `sub_name asc`,
                        productline: 1,
                        plinejoin: 1
                    }
                });
                this.plinepricingdata.page++;
                this.plinepricingdata.entries = [...this.plinepricingdata.entries, ...res.data.data.map(row => {
                    const objrow = _.pick(row, this.exportedProductLinePricingDataFieldLists);
                    const ret = {
                        subcategory: row.productline.sub_name,
                        printmethod: row.productline.pline_method_fullname,
                        chargetype: row.chargetypes.charge_name,
                        ...objrow
                    };
                    return ret;
                })];
                this.plinepricingdata.next = res.data.next_page_url;
            } catch($e) {
                return;
            }
        },
        async exportProductLinePricing() {
            this.showLoading('Exporting...\nplease do not close/refresh the page.')

            this.plinepricingdata.next = 1;

            this.plinepricingdata.page = 1;

            this.plinepricingdata.entries = [];

            while( this.plinepricingdata.next ) {
                await this.getExportedProductLinePricings();
            }

            const csvExport = Papa.unparse(this.plinepricingdata.entries);

            await swal('Product Line Pricing has been exported.');

            this.exportedAutoDownload(csvExport, `product-lines-pricing`);
        },
        async getExportedProductLinePricings() {
            try {
                const res = await api.get(`/pricing-data`, {
                    params: {
                        paginate: this.plinepricingdata.limit,
                        page: this.plinepricingdata.page,
                        pricings: 1,
                        orderBy: `sub_name asc`,
                        productline: 1,
                        plinejoin: 1
                    }
                });
                this.plinepricingdata.page++;


                const e = this;
                const pricing = [];
                res.data.data.map(row => {
                    row.pvalues.map(price => {

                        const pricingobj = _.pick(price, e.exportedPricingFieldsLists);
                        pricing.push({
                            subcategory: row.productline.sub_name,
                            printmethod: row.productline.pline_method_fullname,
                            chargetype: row.chargetypes.charge_name,
                            ...pricingobj
                        });
                    });
                });

                this.plinepricingdata.entries = pricing;

                this.plinepricingdata.next = res.data.next_page_url;
            } catch($e) {
                return;
            }
        }
    }
});