jQuery( function($) {

    $.moneyFormat = function(value, withcurrency = false) {
        let currencysymbol = withcurrency && globalJSVars.inventoryCurrency ? globalJSVars.inventoryCurrency : '';
        return currency(value, { symbol: currencysymbol }).format();
    }

    $.toJson = function(json) {
        try {
            return JSON.parse(json);
        } catch($e) {
            return null;
        }
    }

    // tab module
    window.tabModule = {
        selector: null,
        tabChange: function($tab) {
            if(this.selector!=$tab) {
                this.selector=$tab;
                this.tabsReset();
                this.tabActive();
            }
        },
        tabSet: function($tab) {
            this.selector = $tab;
            this.tabsReset();
            this.tabActive();
        },
        tabsReset: function() {
            this.selector.parent().closest('.tab-module').find('.tablinks button').removeClass('active');
            this.selector.parent().closest('.tab-module').find('.tabcontents .tab-item').removeClass('active');
        },
        tabActive: function() {
            var target = this.selector.data('target');
            this.selector.addClass('active');
            this.selector.parent().closest('.tab-module').find(`.tabcontents .tab-item${target}`).addClass('active');
        }
    }

    $(document).on( '[data-tab-link]', 'click', function() {
        tabModule.tabChange($(this));
        return false;
    });


    // accordion module
    window.accordionModule = {
        selector: null,
        toggleclass: 'open',
        toggle: function($elem, event = null, callback= function(){}) {
            this.selector = $elem;
            var parentItem = this.selector.parent('[data-accordion-module-item]');
            var contentItem = parentItem.children('[data-accordion-module-content]');
            var e = this;
            if( parentItem ) {

                if( event == 'close' ) {

                    contentItem && contentItem.slideUp( function() {
                        // e.scrollTo(parentItem);
                        parentItem.removeClass( e.toggleclass );
                        callback();
                    });

                    return;
                }

                if( event == 'open' ) {

                    contentItem && contentItem.slideDown( function() {
                        // e.scrollTo(parentItem, $elem.data('element-scroll'));
                        parentItem.addClass( e.toggleclass );
                        callback();
                    });

                    return;
                }

                // autoclose collapse item
                if( parentItem.parent().closest('[data-accordion-autoclose]').length ) {

                    if(parentItem.hasClass('open')) {return;}

                    var autocloseelem = parentItem.parent().closest('[data-accordion-autoclose]').children();
                    var item = autocloseelem.closest('[data-accordion-module-item]');
                    var content = autocloseelem.closest('[data-accordion-module-item]').children('[data-accordion-module-content]');
                    
                    content.slideUp( function() {
                        item.removeClass( e.toggleclass );
                    });
                }

                if( parentItem.hasClass(this.toggleclass) ) {
                    contentItem && contentItem.slideUp( function() {
                        // e.scrollTo(parentItem);
                        parentItem.removeClass( e.toggleclass );
                        return;
                    });
                } else {
                    contentItem && contentItem.slideDown( function() {
                        // e.scrollTo(parentItem, $elem.data('element-scroll'));
                        parentItem.addClass( e.toggleclass );
                        return;
                    });
                }
            }
        },
        scrollTo: function(elem, scrollelement) {
            var scrollTop = elem.offset().top-20;
            var elemscroll = $('html, body');
            if( scrollelement ) {
                elemscroll = $(scrollelement);
                scrollTop = elem.position().top+50;
            }
            elemscroll.stop().animate({ scrollTop: scrollTop}, 500, 'swing');
        }
    }

    $(document).on( 'click', '[data-accordion-module]', function(e) {
        e.preventDefault();
        e.stopPropagation();
        accordionModule.toggle($(this));
        return false;
    });


    window.scrollToElem = {
        scroll: function( $elem, callback ) {
            $('html, body').stop().animate({ scrollTop: ($elem.offset().top - 50) }, 500, 'swing', function() {
                callback && callback();
            });
        }
    }

    function getContrastYIQ(hexcolor){
        hexcolor = hexcolor.replace("#", "");
        var r = parseInt(hexcolor.substr(0,2),16);
        var g = parseInt(hexcolor.substr(2,2),16);
        var b = parseInt(hexcolor.substr(4,2),16);
        var yiq = ((r*299)+(g*587)+(b*114))/1000;
        return (yiq >= 200) ? 'black' : 'white';
    }

    // $.autocolorinit = function() {
    //     $.each($('[data-auto-color]'), function() {
    //         var colorDetect = getContrastYIQ( $(this).data('auto-color') );
    //         $(this).css({ color: colorDetect });
    //     });
    // }

    // $.autocolorinit();


    $.fn.hScroll = function (amount) {
        amount = amount || 120;
        $(this).bind("DOMMouseScroll mousewheel", function (event) {
            var oEvent = event.originalEvent, 
                direction = oEvent.detail ? oEvent.detail * -amount : oEvent.wheelDelta, 
                position = $(this).scrollLeft();
            position += direction > 0 ? -amount : amount;
            $(this).scrollLeft(position);
            event.preventDefault();
        })
    };

});