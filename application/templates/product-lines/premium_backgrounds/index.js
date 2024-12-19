var premiumBackgroundsVue = {
    data() {
        return {
            premiumBgInputs: {
                plineindex: null,
                index: null,
                data: [],
                loading: false,
                input: {
                    priority: 1,
                    title: null,
                    collection_premium_backgrounds_id: null,
                    index: null
                }
            }
        }
    },
    computed: {
        premiumbgpline() {
            return this.subcatMethods.data[this.premiumBgInputs.plineindex];
        }
    },
    methods: {
        setEditplinePremiumBG(data, index) {
            this.premiumBgInputs.input = {
                priority: data.priority,
                title: data.title,
                collection_premium_backgrounds_id: data.collection_premium_backgrounds_id_hash,
                index,
                id: data.hid
            }
        },
        resetInputpremiumBG($args) {
            this.premiumBgInputs.input = {
                priority: 1,
                title: null,
                collection_premium_backgrounds_id: null,
                index: null,
                ...$args
            }
            this.fixValidator();
        },
        async getPremiumBGCollection() {
            try {
                this.premiumBgInputs.loading = true;
                const res = await api.get(`/collections/premium-backgrounds?orderBy=priority+asc`);
                this.premiumBgInputs.data = res.data.data;
                this.premiumBgInputs.loading = false;
            } catch($e) {
                this.premiumBgInputs.loading = false;
                return;
            }
        },
        async plinePremiumBGSave() {
            var valid = await this.$validator.validate();
            if(!valid) return;

            if( !this.premiumBgInputs.input.id ) {
                await this.plinePremiumBGadd();
            } else {
                await this.plinePremiumBGupdate();
            }
        },
        async plinePremiumBGadd() {
            try {
                await this.showLoading();
                const res = await api.post('product-line/premiumBG', {
                    ...this.premiumBgInputs.input, 
                    product_line_id: this.subcatMethods.data[this.premiumBgInputs.plineindex].hid
                });
                swal('New product line premium background collection has been added.', { icon: 'success' });
                this.subcatMethods.data[this.premiumBgInputs.plineindex].premiumbg = res.data.premiumbg;
                this.resetInputpremiumBG();
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async plinePremiumBGupdate() {
            try {
                await this.showLoading();
                const res = await api.put('product-line/premiumBG', {
                    ...this.premiumBgInputs.input, 
                    product_line_id: this.subcatMethods.data[this.premiumBgInputs.plineindex].hid
                });
                swal('Product line premium background collection has been updated.', { icon: 'success' });
                this.subcatMethods.data[this.premiumBgInputs.plineindex].premiumbg = res.data.premiumbg;
                this.resetInputpremiumBG();
            } catch($e) {
                this.backEnd($e);
                return;
            }
        },
        async plinePremiumBGDelete(index, id) {
            var confirm = await swal('Are you sure you want to remove this premium background collection?', {
                buttons: true,
                dangerMode: true
            });
            var e = this;
            if( confirm ) {
                try {
                    await this.showLoading();
                    await api.delete(`product-line/premiumBG?id=${id}`);
                    e.subcatMethods.data[e.premiumBgInputs.plineindex].premiumbg.splice(index, 1);
                    swal('Removed', { icon: 'success' });
                } catch($e) {
                    this.backEnd($e);
                    return;
                }
            }
        }
    },
    async mounted() {
        await this.getPremiumBGCollection();
    },
}