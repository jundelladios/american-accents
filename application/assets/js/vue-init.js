Vue.component('validate', VeeValidate.ValidationProvider);
    
Vue.use(VeeValidate);

Vue.component('Editor', VueTrumbowyg.default);

Vue.component('VuePaginate', VuejsPaginate);

Vue.component('draggable', window['vuedraggable']);


var langs = {
    delete: "Make inactive",
    restore: "Make active",
    forceDelete: "Delete Permanently",
    removeNote: "Are you sure you want to permanently remove this? Note: This cannot be undone."
}

var mixVue = {
    data() {
        return {
            swalMessage: ''
        }
    },
    computed: {
        langs() { return langs; },
        paginationArrows() {
            return {
                prev: '<',
                next: '>',
                first: '<<',
                last: '>>'
            }
        },
        vueDragOptions() {
            return {
                animation: 200,
                group: "description",
                ghostClass: "ghost",
                disabled: false,
                filter: 'input, code, button, label, .nodrag',
                preventOnFilter: false
            };
        }
    },
    components: {
        VueSlickCarousel: window['vue-slick-carousel']
    },
    methods: {
        showLoading( message = "Loading..." ) {
            this.swalMessage = message;
            swal(this.swalMessage, { buttons: {}, closeOnClickOutside: false, closeOnEsc: false });
            return new Promise( resolve => setTimeout( resolve, 500 ) );
        },
        backEnd($errors) {
            try {
                var e = this;
                var messages = $errors.response.data.message;
                var str = '';
                if( typeof messages === 'object' ) {
                    for(var key in messages) {
                        if( messages.hasOwnProperty(key)) {
                            e.errors.add({
                                field: key,
                                msg: messages[key][0]
                            });
                            
                            str += messages[key][0] + '\n';
                        }
                    }
                    swal(str, { icon: 'error' });
                } else {
                    const wrapper = document.createElement('div');
                    wrapper.innerHTML = messages;
                    swal({ icon: 'error', content: wrapper });
                }
            } catch($e) {
                swal('Failed to execute your request please try again.', { icon: 'error' });
            }
        },
        fixValidator() {
            this.$validator.pause()
            this.$nextTick(() => {
                this.$validator.errors.clear()
                this.$validator.fields.items.forEach(field => field.reset())
                this.$validator.fields.items.forEach(field => this.errors.remove(field))
                this.$validator.resume()
            })
        },
        chooseLibrary( title, callback, $args ) {
            var file = wp.media({
                title,
                button: { text: title },
                multiple: false,
                library: {
                    type: ['image']
                },
                ...$args
            }).open()
            .on('select', function(e) {
                var uploaded_file = file.state().get('selection').first();
                var file_url = uploaded_file.toJSON().url;
                callback(file_url, uploaded_file.toJSON());
            });
        },
        accordion($e) {
            accordioJS.navigate(jQuery($e.target));
        },
        accordion2($target) {
            accordioJS.navigate(jQuery(`[data-target="${$target}"]`));
        },
        setDefault($data, $default, $isjson) {
            try {
                if( !$data ) {
                    return $default;
                }
                return $isjson ? JSON.parse($data) : $data;
            } catch($e) {
                return $default;
            }
        },
        jsonToString($json) {
            
        },
        _toJson($data) {
            try {
                return JSON.parse($data);
            } catch($e) {
                return null;
            }
        },
        moneyFormat(val) {
            const value = parseFloat(Number(val));
            return isNaN(value) ? 0 : value;
        }
    }
}