function $route_change($removes = [], $appends = "") {
    var url = window.location.href.split('?')[0];
    var urlParams = new URLSearchParams(window.location.search);
    var keys = urlParams.keys();
    var str = "";
    for(key of keys) {
        if(!$removes.includes(key)) {
            str += key += '=' + urlParams.get(key);
            str += "&";
        }
    }
    str = str!="" ? '?' + str + $appends : "";
    uri = url += str.replace(/&+$/,'');
    window.history.pushState({path:uri},'', uri);
}

function $indexer($array, $key, $value) {
    return $array.findIndex(row => {
        return row[$key] === $value;
    });
}

function unprocessable_entities( $error ) {
    var messages = $error.response.data.message;
    var str = "";
    if( typeof messages === 'object' ) {
        for(var key in messages) {
            if( messages.hasOwnProperty(key)) {
                messages[key].map(errors => {
                    str += errors;
                    str += '\n';
                });
            }
        }
        str!="" && swal(str, { icon: 'error' });;
    } else {
        swal(messages, { icon: 'error' });
    }
}


var accordioJS = {
    state: null,
    duration: 200,
    activeClass: 'active',
    navigate: function(target) {
        var selector = target.data( 'target' );
        if( this.state != selector ) {
            this.state = selector;
            jQuery( '.aa-accordion .accordion_contents' ).slideUp(this.duration);
            var elem = target.parent().find(this.state);
            elem.slideDown(this.duration);
            elem.addClass(this.activeClass);
        }
    },
    resetAccordion: function() {
        jQuery( '.aa-accordion .accordion_contents' ).slideUp(this.duration);
    }
}