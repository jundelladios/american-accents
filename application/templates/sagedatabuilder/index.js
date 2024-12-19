var productsInstanceVue = new Vue({
    el: '#sageBuilderController',
    mixins: [mixVue],
    data() {
        return {
            product: {
                loading: false,
                data: {}
            },
            vdssage: {
                loading: false,
                data: {}
            },
            sageproduct: {
                reference: null,
                selected: null,
                loading: false,
                productAllIndexer: 0,
                data: {},
                datacopy: {},
                submit: {
                    res: [],
                    loading: false
                }
            }
        }
    },
    watch: {
        'sageproduct.data.updateType': function(val) {
            if(val == 1) {
                var prop = ['quantities', 'prices', 'prCode', 'piecesPerUnit', 'quoteUponRequest', 'updateType', 'productId', 'suppId'];
                const proddata = {...this.sageproduct.data};
                for (var k in proddata) {
                    if (prop.indexOf(k) < 0) {
                        delete proddata[k];
                    }
                }
                this.sageproduct.data = {...proddata}
            } else {
                this.sageproduct.data = {...this.sageproduct.datacopy, updateType: val};
            }
        }
    },
    computed: {
        productIDSage() {
            return productIDSage;
        },
        sageProducts() {
            let res = [];
            for (var key in this.vdssage.data) {
                res = [...res, ...this.vdssage.data[key]];
            }
            return res;
        },
        sageConnect() {
            return `/vdsitems/connect`;
        },
        getProductsDataToSage() {
            const e = this;
            const productdata = {...e.sageproduct.data};

            const keyremoved = ['itemNum', 'cat1Id', 'cat1Name', 'cat2Id', 'cat2Name', 'page1', 'page2', 'imprintArea', 'secondImprintArea'];
            keyremoved.forEach(e => delete productdata[e]);

            if(this.sageproduct.selected) {
                return [{...productdata}];
            }
            const res = [];

            delete productdata.name;
            this.sageProducts.map(row => {
                res.push({
                    ...productdata,
                    productId: row.vdsproductid
                });
            });

            return res;
        },
        getResSubmitted() {
            const e = this;
            return e.sageproduct.submit.res.map(row => {
                const theprod = e.sageProducts.find(x => x.vdsproductid == row.productId);
                row.itemNum = theprod ? theprod.vdsid : null;
                return row;
            });
        }
    },
    async mounted() {
        this.getProduct();
        await this.getSageProduct();
        await this.fetchSageInitProduct();
    },
    methods: {
        async getProduct() {
            try {
                this.product.loading = true;
                const res = await api.get(`/public/getProduct`, {
                    params: {
                        id: this.productIDSage
                    }
                });
                this.product.data = res.data;
                this.product.loading = false;
            } catch($e) {
                this.product.loading = false;
            }
        },
        async getSageProduct() {
            try {
                this.vdssage.loading = true;
                const res = await api.get(`/vdsitems`, {
                    params: {
                        id: this.productIDSage
                    }
                });
                this.vdssage.data = res.data;
                this.vdssage.loading = false;
            } catch($e) {
                this.vdssage.loading = false;
            }
        },
        getSearchedProductFeed() {
            const e = this;
            const indexer = e.sageProducts.findIndex(x => x.vdsid == e.sageproduct.reference);
            e.sageproduct.productAllIndexer = indexer >= 0 ? indexer : 0;
            this.fetchSageInitProduct();
        },
        async fetchSageInitProduct(sageproductid) {
            let sageprodid = sageproductid
            if(!sageprodid) {
                sageprodid = this.sageProducts.length ? this.sageProducts[this.sageproduct.productAllIndexer].vdsproductid : null;
            }

            if(!sageprodid) { 
                return false; 
            }

            try {
                this.sageproduct.loading = true;
                const res = await api.post(this.sageConnect, {
                    context: {
                        serviceId: 108,
                        productId: sageprodid
                    }
                });
                if(res.data.products.length) {
                    this.sageproduct.data = {...res.data.products[0], updateType: 0};
                    this.sageproduct.datacopy = {...res.data.products[0], updateType: 0}
                }
                this.sageproduct.loading = false;
            } catch($e) {
                console.log($e);
                this.sageproduct.loading = false;
            }
        },
        removeUpdateField(key) {
            const data = {...this.sageproduct.data};
            delete data[key];
            this.sageproduct.data = data;
        },
        choosePhoto(index) {
            const e = this;
            this.chooseLibrary( `Product Combination Image`, (url, obj) => {
                e.sageproduct.data.pics[index].url = url;
                e.sageproduct.data.pics[index].caption = obj.caption;
            });
        },
        async saveSageProduct() {
            this.sageproduct.submit.res = [];
            try {
                this.sageproduct.submit.loading = true;
                const res = await api.post(this.sageConnect, {
                    context: {
                        serviceId: 109,
                        products: this.getProductsDataToSage
                    }
                });

                this.sageproduct.submit.loading = false;

                if(!res.data.responses && res.data.errMsg) {
                    swal(`${res.data.errNum} - ${res.data.errMsg}`, { icon: 'warning' });
                    return;
                }

                this.sageproduct.submit.res = res.data.responses;

                var confirm = await swal('SAGE PRODUCT FEED has been executed.', {
                    buttons: {
                        cancel: true,
                        generate: {
                            text: "View SAGE LOGS",
                            value: "sagelogs"
                        }
                    }
                });
    
                switch(confirm) {
                    case 'sagelogs':
                        window.scrollTo(0, document.body.scrollHeight);
                    break;
                }

            } catch($e) {
                console.log($e);
                this.sageproduct.submit.loading = false;
            }
        }
    }
});