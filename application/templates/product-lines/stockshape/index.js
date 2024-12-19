var stockShapeVue = {
    data() {
        return {
            stockShapesInputs: {
                plineindex: null,
                index: null,
                data: [],
                loading: false,
                input: {
                    priority: 1,
                    title: null,
                    collection_stockshape_id: null,
                    index: null
                }
            }
        }
    },
    computed: {
        stockshapepline() {
            return this.subcatMethods.data[this.stockShapesInputs.plineindex];
        }
    },
    methods: {
        setEditplineStockShape(data, index) {
            this.stockShapesInputs.input = {
                priority: data.priority,
                title: data.title,
                collection_stockshape_id: data.collection_stockshape_id_hash,
                index,
                id: data.hid
            }
        },
        resetInputStockShape($args) {
            this.stockShapesInputs.input = {
                priority: 1,
                title: null,
                collection_stockshape_id: null,
                index: null,
                ...$args
            }
            this.fixValidator();
        },
        async getStocksShapeCollection() {
            try {
                this.stockShapesInputs.loading = true;
                const res = await api.get(`/collections/stock-shape?orderBy=priority+asc`);
                this.stockShapesInputs.data = res.data.data;
                this.stockShapesInputs.loading = false;
            } catch($e) {
                this.stockShapesInputs.loading = false;
                return;
            }
        },
        async plineStockShapeSave() {
            var valid = await this.$validator.validate();
            if(!valid) return;

            if( !this.stockShapesInputs.input.id ) {
                await this.plineStockShapeadd();
            } else {
                await this.plineStockShapeupdate();
            }
        },
        async plineStockShapeadd() {
            try {
                await this.showLoading();
                const res = await api.post('product-line/stockshape', {
                    ...this.stockShapesInputs.input, 
                    product_line_id: this.subcatMethods.data[this.stockShapesInputs.plineindex].hid
                });
                swal('New product line stock shape collection has been added.', { icon: 'success' });
                this.subcatMethods.data[this.stockShapesInputs.plineindex].stockshapes = res.data.stockshapes;
                this.resetInputStockShape();
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async plineStockShapeupdate() {
            try {
                await this.showLoading();
                const res = await api.put('product-line/stockshape', {
                    ...this.stockShapesInputs.input, 
                    product_line_id: this.subcatMethods.data[this.stockShapesInputs.plineindex].hid
                });
                swal('Product line stock shape collection has been updated.', { icon: 'success' });
                this.subcatMethods.data[this.stockShapesInputs.plineindex].stockshapes = res.data.stockshapes;
                this.resetInputStockShape();
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async plineStockShapeDelete(index, id) {
            var confirm = await swal('Are you sure you want to remove this stock shape collection?', {
                buttons: true,
                dangerMode: true
            });
            var e = this;
            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.delete(`product-line/stockshape?id=${id}`);
                    e.subcatMethods.data[e.stockShapesInputs.plineindex].stockshapes.splice(index, 1);
                    swal('Removed', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        }
    },
    async mounted() {
        await this.getStocksShapeCollection();
    },
}