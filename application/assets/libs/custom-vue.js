let uuid = 0;

// custom dropdown
Object.defineProperty(Vue.prototype, '$_', { value: _ });

Vue.directive('click-outside', {
    bind: function(el, binding, vnode) {
        const ourClickEventHandler = event => {
            if (!el.contains(event.target) && el !== event.target) {
                binding.value.bind(vnode.context)(event)
            }
        };
        el.__vueClickEventHandler__ = ourClickEventHandler;
        document.addEventListener("click", ourClickEventHandler);
    },
    unbind: function(el) {
        document.removeEventListener("click", el.__vueClickEventHandler__);
    }
})

Vue.component('dropdown-vue', {
    template: /*html */`
        <div v-click-outside="closedd" :class="\`vue-component-dd \${show?'active':''}\`">
            <button :class="\`vue-component-dd-btn \${show?'active':''}\`" @click.stop="show=!show">
                <span class="label" v-html="getSelected.text"></span>
                <span :class="\`icon-wrap \${show?'active':''}\`">
                    <span class="icon icon-arrow-right"></span>
                </span>
            </button>
            
            <transition enter-active-class="animate__animated animated__growDown" leave-active-class="animate__animated animated__growUp" model="out-in">
            <div v-if="show" class="vue-component-the-wrap">

                <div class="vue-component-dd-input-wrap">
                    <input :placeholder="placeholder" class="search" v-model="search">
                </div>

                <div class="vue-component-dd-lists-float f-scroll-custom thin">
                    <div class="vue-component-dd-lists-wrap">
                        <div class="vue-component-dd-result-wrap">
                            <ul>
                                <li v-for="(list, listindex) in searchLists" :key="\`vue-component-dd-key-\${listindex}\`">
                                    <a href="javascript:void(0)" rel="nofollow" @click.stop="setValue(list)" v-html="list.text"></a>
                                </li>

                                <li v-if="!searchLists.length" class="text-center"><small>Empty stock shape.</small></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            </transition>

        </div>
    `,
    props: ['lists', 'value', 'placeholder'],
    data() {
        return {
            search: null,
            show: false
        }
    },
    computed: {
        searchLists() {
            const e = this;
            if( !e.search ) { return e.lists };
            return this.lists.filter(item => {
                return e.search.toLowerCase().split(' ').every(v => item.reference.toLowerCase().includes(v));
            });
        },
        getSelected() {
            if( this.lists.length ) {
                var selected = this.lists.find( row => row.value == this.value );
                return selected ? selected : this.lists[0];
            }
            return [];
        }
    },
    methods: {
        closedd() {
            this.show=false;
            this.search=null;
        },
        setValue(param) {
            this.$emit('input', param.value);
            this.closedd();
        }
    }
});



Vue.directive('hscroll', function(el, binding) {
    try {
        jQuery(el).hscroll(binding.value);
    } catch($e) {
        jQuery(document).ready( function() {
            jQuery(el).hScroll(binding.value);
        });
    }
});

function getContrastYIQ(hexcolor){
    hexcolor = hexcolor.replace("#", "");
    var r = parseInt(hexcolor.substr(0,2),16);
    var g = parseInt(hexcolor.substr(2,2),16);
    var b = parseInt(hexcolor.substr(4,2),16);
    var yiq = ((r*299)+(g*587)+(b*114))/1000;
    return (yiq >= 200) ? 'black' : 'white';
}

Vue.directive('autocolor', function(el, binding) {
    var colorDetect = getContrastYIQ( binding.value );
    try {
        jQuery(el).css({ color: colorDetect });
    } catch($e) {
        jQuery(document).ready( function() {
            jQuery(el).css({ color: colorDetect });
        });
    }
});

Vue.directive('img', function(el, binding) {
    var imagecdnproxy = binding.value.replace(inventoryJSVars.baseURLnoSlash, inventoryJSVars.imageproxycdn);
    var srcset = `
        ${imagecdnproxy}?width=600 800w,
        ${imagecdnproxy}?width=800 1600w,
        ${imagecdnproxy}?width=1600 1900w,
        ${imagecdnproxy} 2050w
    `; 

    if( !inventoryJSVars.imageproxycdn ) {
        srcset = binding.value;
    }
    el.setAttribute(binding.arg, srcset);
    el.setAttribute('class', `${el.getAttribute('class')} has_background_image br-lazy`);
    el.setAttribute('data-brsizes', `
        (min-width: 991px) 1900px,
      (min-width: 768px) 1600px,
      (min-width: 576px) 800px,
      100vw
    `);
    el.setAttribute('loading', `lazy`);
});

Vue.component('v-img', {
    template: /*html */`
        <div v-if="loading" :class="\`ajax-loader img-preloader \${loadersize}\`">
            <div class="loaderWrap">
                <div>
                    <div class="loader" style="margin: 0 auto;"></div>
                </div>
            </div>
        </div>
        <img v-else-if="!loading && img" 
        :data-brsrcset="srcset" 
        :data-brsizes="srcsizes"
        :data-breeze="datasrc"
        :src="thesrc"
        class="br-lazy img-fit-center" 
        :fallback="getFallback" 
        v-bind="$attrs" 
        v-on="$listeners" 
        :width="width"
        :height="height"
        loading="lazy"
        >
        <img v-else-if="!loading && !img && !nofallback" :src="getFallback" v-bind="$attrs" class="br-lazy img-fit-center" :fallback="getFallback" alt="no-image" v-on="$listeners"
        :width="width"
        :height="height"
        >
    `,
    props: {
        img: String,
        landscape: Boolean,
        fallback: String,
        nofallback: Boolean,
        loadersize: {
            type: String,
            default: "md"
        },
        loading: {
            type: Boolean,
            default: false
        },
        width: Number,
        height: Number
    },
    computed: {
        srcset() {
            var srcset = `
                ${this.datasrc}?width=600 800w,
                ${this.datasrc}?width=800 1600w,
                ${this.datasrc}?width=1600 1900w,
                ${this.datasrc} 2050w
            `;
            return srcset;
        },
        srcsizes() {
            return AA_JS_OBJ.SRCSIZES();
        },
        thesrc() {
            return AA_JS_OBJ.IMG_PRELOADER;
        },
        datasrc() {
            if( !inventoryJSVars.imageproxycdn ) { return this.img; }
            var imagecdnproxy = this.img.replace(
                inventoryJSVars.baseURLnoSlash,
                inventoryJSVars.imageproxycdn
            );
            return imagecdnproxy;
        },
        getFallback() {
            let fallback = inventoryJSVars.fallbackImage;
            if( !this.fallback ) {
                this.fallback = fallback;
            }
            if( inventoryJSVars.imageproxycdn ) { 
                var imagecdnproxy = this.fallback.replace(inventoryJSVars.baseURLnoSlash, inventoryJSVars.imageproxycdn);
                return imagecdnproxy; 
            }
            return fallback;
        }
    }
});



Vue.component('v-modal', {
    template: /*html */`
    <div 
    :class="\`modal modal-dynamic-component component-id-\${uuid} \${dialogclass}\`" 
    tabindex="-1" 
    role="dialog"
    aria-hidden="true">
        <div :class="\`modal-dialog \${modalSize} \${modalClass}  modal-dialog-centered\`" :style="dialogStyle" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button @click="closeModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pt-3 pb-5">
                    <slot />
                </div>
            </div>
        </div>
    </div>
    `,
    props: {
        dialogclass: {
            type: String,
            default: ``
        },
        dialogStyle: {
            type: Object
        },
        modalClass: {
            type: String,
            default: ``
        },
        modalSize: {
            type: String,
            default: 'modal-xs'
        }
    },
    data() {
        return {
            uuid: uuid.toString()
        }
    },
    beforeCreate() {
        this.uuid = uuid.toString();
        uuid += 1;
    },
    methods: {
        openModal() {
            jQuery(`.modal-dynamic-component.component-id-${this.uuid}`).modal('show');
        },
        closeModal() {
            jQuery(`.modal-dynamic-component.component-id-${this.uuid}`).modal('hide');
        }
    }
});