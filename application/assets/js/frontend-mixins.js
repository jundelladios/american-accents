var frontMixins = {
    data() {
        return {
            isMobile: window.matchMedia(`(max-width: ${this.mobileBreakpoint}px)`).matches
        }
    },
    mounted() {
        const e = this;
        jQuery(window).on( 'load resize', function() {
            var updateMedia = window.matchMedia(`(max-width: ${e.mobileBreakpoint}px)`).matches;
            if( e.isMobile != updateMedia ) {
                e.isMobile = updateMedia;
            }
        });
    },
    computed: {
        mobileBreakpoint() {
            return 992;
        },
        currency() {
            return '&#36;';
        },
        paginationArrows() {
            return {
                prev: '<span class="icon prevnext icon-arrow-right-circle rotate-right"></span>',
                next: '<span class="icon prevnext icon-arrow-right-circle"></span>',
                first: '<span class="icon firstlast icon-arrow-right-double rotate-right"></span>',
                last: '<span class="icon firstlast icon-arrow-right-double"></span>'
            }
        },
        getImageFallback() {
            return {
                normal: `${inventoryJSVars.pluginURL}application/assets/img/square-placeholder.png`,
                banner: `${inventoryJSVars.pluginURL}application/assets/img/banner-placeholder.png`,
            };
        }
    },
    methods: {
        toMoney(value, precision = 3, cur = "$") {
            return currency( value, {
                symbol: cur,
                precision
            }).format();
        },
        toJson(json, key) {
            try {
                if( key ) {
                    return JSON.parse(json)[key];
                }
                return JSON.parse(json);
            } catch($e) {
                return null;
            }
        },
        setNextPage(page) {
            return page ? page.split('?page=')[1] : null;
        },
        moneyFormat(val) {
            const value = parseFloat(Number(val));
            return isNaN(value) ? 0 : value;
        }
    }
}