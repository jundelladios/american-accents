jQuery( function($) {

    $( document ).ready( function() {

        var productSlick = {
            slickOptions: {
                horizontal: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    focusOnSelect: true,
                    asNavFor: '.ssr.carousel-thumbnail'
                },
                vertical: {
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    asNavFor: '.ssr.carousel-main',
                    arrows: false,
                    dots: false,
                    focusOnSelect: true,
                    centerPadding: 10,
                    variableWidth: true
                },
                // ideagalleryHorizontalSlick: {
                //     slidesToShow: 1,
                //     slidesToScroll: 1,
                //     focusOnSelect: true,
                //     asNavFor: '.ssr-carousel-idea-thumbnail',
                //     nextArrow: '<button class="idea-modal-arrow left rotate-left"><span class="icon idea-arrow icon-arrow-right"></span></button>',
                //     prevArrow: '<button class="idea-modal-arrow right"><span class="icon idea-arrow icon-arrow-right"></span></button>'
                // },
                // ideagalleryVerticalSlick: {
                //     slidesToShow: 5,
                //     slidesToScroll: 1,
                //     asNavFor: '.ssr-carousel-idea-main',
                //     arrows: false,
                //     dots: false,
                //     focusOnSelect: true,
                //     centerPadding: 10,
                //     variableWidth: true
                // }
            },
            initialize: function() {

                var slickHorizontal = $('.ssr.carousel-main');

                var slickVertical = $('.ssr.carousel-thumbnail');

                slickHorizontal.slick({...this.slickOptions.horizontal});

                slickVertical.slick({...this.slickOptions.vertical});

                // var ideagalleryHorizontal = $('.ssr-carousel-idea-main');

                // var ideagalleryVertical = $('.ssr-carousel-idea-thumbnail');

                // ideagalleryHorizontal.slick({
                //     ...this.slickOptions.ideagalleryHorizontalSlick
                // });
    
                // ideagalleryVertical.slick({
                //     ...this.slickOptions.ideagalleryVerticalSlick
                // });
            },
            // ideagalleryrefresh() {
            //     var ideagalleryHorizontal = $('.ssr-carousel-idea-main');
            //     var ideagalleryVertical = $('.ssr-carousel-idea-thumbnail');
                
            //     ideagalleryHorizontal.slick('destroy');
            //     ideagalleryVertical.slick('destroy');

            //     ideagalleryHorizontal.slick({
            //         ...this.slickOptions.ideagalleryHorizontalSlick
            //     });
    
            //     ideagalleryVertical.slick({
            //         ...this.slickOptions.ideagalleryVerticalSlick
            //     });
            // }
        };

        // initialize
        productSlick.initialize();


        // var realtimeBinding = {
        //     state: null,
        //     downloadfile: `${AA_JS_OBJ.API_BASE}/wp-json/v1/downloadFiles?`,
        //     fadeDuration: 100,
        //     templates: function(entry, append='') {
        //         var template = '', templatemodal = '', downloadAllTemplate = null, downloadLink = null;
        //         if( entry.templatedata.length ) {
        //             var getArchiveLists = entry.templatedata.filter( row => row.link ).map( row => row.link );
        //             if( getArchiveLists.length ) {
        //                 /** ======= GENERATED DOWNLOAD ARCHIVE LINK ============= */
        //                 downloadLink = `${this.downloadfile}${$.param({
        //                     filename: `${productJSON.product_method_combination_name}${append}-product-templates.zip`,
        //                     files: getArchiveLists
        //                 })}`;
        //             }

        //             entry.templatedata.map( row => {
        //                 template += /*html */`
        //                 <div class="img-slide-item">
        //                     <div class="img-fit-wrap p-0">
        //                         <img 
        //                             class="img-fit-center lazyload lz-blur" 
        //                             srcset="${row.previewImage.srcset}"
        //                             src="${row.previewImage.src}"
        //                             fallback="${inventoryJSVars.fallbackImage}"
        //                             alt="${row.previewImage.alt}"
        //                             data-toggle="modal"
        //                             data-target="#producttemplatesmodal"
        //                         />
        //                     </div>
        //                 </div>
        //                 `;

        //                 templatemodal += /*html */`
        //                 <div class="product-template-modal-content-item">
        //                     <div class="p-template-wrap">
        //                         <img 
        //                             class="img-fit-center lazyload lz-blur" 
        //                             srcset="${row.previewImage.srcset}"
        //                             src="${row.previewImage.src}"
        //                             fallback="${inventoryJSVars.fallbackImage}"
        //                             alt="${row.previewImage.alt}"
        //                         />

        //                         <div class="button-actions mt-3">
        //                             <a href="#" class="btn-action button-light"><span class="icon mr-1 icon-email"></span> email</a>
        //                             <a href="#" class="btn-action button-light frontend-desktop-only"><span class="icon mr-1 icon-email"></span> print</a>
        //                 `;
                        
        //                 if( row.link ) {
        //                     templatemodal += /*html */`
        //                         <a href="${row.link}" target="_blank" rel="nofollow" download class="btn-action button-light"><span class="icon mr-1 icon-email"></span> download</a>
        //                     `;
        //                 }
                        
        //                 templatemodal += /*html */`
        //                         </div>

        //                     </div>
        //                 </div>
        //                 `;
        //             });

        //             if( downloadLink ) {
        //                 template += /*html */`
        //                     <div class="img-slide-item more align-items-center justify-content-center">
        //                         <a href="${downloadLink}" target="_blank" rel="nofollow" class="more-item-gallery">
        //                             <span class="icon d-block icon-paper-download"></span>
        //                             <span class="d-block text-uppercase font-weight-bold font-10">download templates</span>
        //                         </a>
        //                     </div>
        //                 `;

        //                 downloadAllTemplate = /*html */`
        //                 <div class="modal-button-centered text-center">
        //                     <a href="${downloadLink}" target="_blank" rel="nofollow" class="btn btn-primary">
        //                         <span class="icon icon-download-archive mr-1"></span>
        //                         <span class="text">Download All</span>
        //                     </a>
        //                 </div>
        //                 `;
        //                 $('[realtime-binding-template-archive-modal]').html(downloadAllTemplate);
        //                 $('[realtime-binding-template-archive-modal').fadeIn(this.fadeDuration);
        //             } else {
        //                 $('[realtime-binding-template-archive-modal]').fadeOut(this.fadeDuration);
        //             }

        //             $('[realtime-binding-template-modal]').html(templatemodal);
        //             $('[realtime-binding-template]').html(template);
        //             $('[realtime-binding-showtemplate]').fadeIn(this.fadeDuration);
        //         } else {
        //             $('[realtime-binding-showtemplate]').fadeOut(this.fadeDuration);
        //         }
        //     },
        //     ideagallery: function(entry, append='') {

        //         var idea = '', ideaslide = '', ideathumb = '', ideaarchive = '', downloadall = '';

        //         if( entry.ideagallerydata.length ) {
        //             var getArchiveLists = entry.ideagallerydata.filter( row => row.finalLink ).map( row => row.finalLink );
        //             if( getArchiveLists.length ) {
        //                 /** ======= GENERATED DOWNLOAD ARCHIVE LINK ============= */
        //                 ideaarchive = `${this.downloadfile}${$.param({
        //                     filename: `${productJSON.product_method_combination_name}${append}-idea-galleries.zip`,
        //                     files: getArchiveLists
        //                 })}`;

        //                 downloadall = /*html */`
        //                 <div class="modal-button-centered text-center">
        //                     <a href="${ideaarchive}" rel="nofollow" class="btn btn-primary">
        //                         <span class="icon icon-download-archive mr-1"></span>
        //                         <span class="text">Download All</span>
        //                     </a>
        //                 </div>
        //                 `;

        //                 $('[realtime-binding-idea-archive-modal]').html(downloadall);
        //                 $('[realtime-binding-idea-archive-modal]').fadeIn(this.fadeDuration);
        //             } else {
        //                 $('[realtime-binding-idea-archive-modal]').fadeOut(this.fadeDuration)
        //             }

        //             entry.ideagallerydata.map( row => {
        //                 idea += /*html */`
        //                     <div class="img-slide-item" data-image="${row.image}">
        //                         <div class="img-fit-wrap p-2">
        //                             <img 
        //                             class="img-fit-center lazyload lz-blur" 
        //                             srcset="${row.imageData.srcset}"
        //                             src="${row.imageData.src}"
        //                             fallback="${inventoryJSVars.fallbackImage}"
        //                             alt="${row.imageData.alt}" />
        //                         </div>
        //                     </div>
        //                 `;

                        
        //                 ideaslide += /*html */`
        //                 <div class="idea-main-item-slide">
        //                     <div class="img-slide-item">
        //                         <div class="img-fit-wrap">
        //                             <img 
        //                             class="img-fit-center lazyload lz-blur" 
        //                             srcset="${row.imageData.srcset}"
        //                             src="${row.imageData.src}"
        //                             fallback="${inventoryJSVars.fallbackImage}"
        //                             alt="${row.imageData.alt}" />
        //                         </div>
        //                     </div>

        //                     <div class="button-actions mt-3">
        //                         <a href="#" class="btn-action button-light"><span class="icon mr-1 icon-email"></span> email</a>
        //                         <a href="#" class="btn-action button-light frontend-desktop-only"><span class="icon mr-1 icon-email"></span> print</a>
        //                     `

        //                 if(row.finalLink) {
        //                     ideaslide += /*html */`<a href="${row.finalLink}" class="btn-action button-light" download target="_blank" rel="nofollow" class="btn-action button-light"><span class="icon mr-1 icon-email"></span> download</a>`;
        //                 }

        //                 ideaslide += /*html */`
        //                         <a href="#" class="btn-action button-light"><span class="icon mr-1 icon-email"></span> share</a>
        //                     </div>
        //                 </div>`;



        //                 ideathumb += /*html */`
        //                 <div class="aa-idea-thumbnail-slide">
        //                     <div class="img-slide-item">
        //                         <div class="img-fit-wrap p-2">
        //                             <img 
        //                             class="img-fit-center lazyload lz-blur" 
        //                             srcset="${row.imageData.srcset}"
        //                             src="${row.imageData.src}"
        //                             fallback="${inventoryJSVars.fallbackImage}"
        //                             alt="${row.imageData.alt}"
        //                             data-imgsrc-selector="${row.image}"
        //                             />
        //                         </div>
        //                     </div>`;

        //                 if( row.finalLink ) {
        //                     ideathumb += /*html */`
        //                     <div class="text-center dl-link">
        //                         <a href="${row.finalLink}" download target="_blank" rel="nofollow"><span class="icon icon-download-single mr-1"></span> Download</a>
        //                     </div>
        //                     `;
        //                 }

        //                 ideathumb += /*html */`
        //                 </div>
        //                 `;


        //             });

        //             idea += /*html */`
        //             <div class="img-slide-item align-items-center justify-content-center" data-image="${entry.ideagallerydata[0].image}">
        //                 <div class="more-item-gallery">
        //                     <span class="icon d-block icon-image"></span>
        //                     <span class="d-block text-uppercase font-weight-bold font-10">view full gallery</span>
        //                 </div>
        //             </div>
        //             `;

        //             $('[realtime-binding-idea]').html(idea);
        //             $('[realtime-binding-ideaslide]').html(ideaslide);
        //             $('[realtime-binding-ideathumb]').html(ideathumb);
        //             $('[realtime-binding-showidea]').fadeIn(this.fadeDuration);
        //             productSlick.ideagalleryrefresh();
                    
        //         } else {
        //             $('[realtime-binding-showidea]').fadeOut(this.fadeDuration);
        //         }

        //     }
        // }

        // var gettheparam = inventoryJSVars.params.filter( row => row != '' );
        // if(gettheparam.length) {
        //     realtimeBinding.state = gettheparam[0];
        // }

        // $(document).on( 'click', '.idea-galleries .gallery-item-small .img-slide-item', function() {
        //     var image = $(this).data('image');
        //     var selectorThumbnail = $(`.carousel-idea-thumbnail img.img-thumbnail-trigger[data-imgsrc-selector="${image}"]`).parent().closest('.img-slide-item');
        //     selectorThumbnail && selectorThumbnail.trigger('click');
        //     $('#ideagallerymodal').modal('show');
        // });


        // product colors fetcher
        // $(document).on( 'click', '[data-color]', async function() {
        //     $('[data-color]').removeClass('active');
        //     $(this).addClass('active');
        //     var slug = $(this).data('color');
        //     if( realtimeBinding.state == slug ) { return; } 
        //     realtimeBinding.state = slug;
        //     var availableColors = productJSON.availablecolors;
        //     var getselectedcolor = availableColors.filter(row => row.slug == slug)[0];
        //     if( !getselectedcolor ) { return; }
        //     //realtimeBinding.maingallery(getselectedcolor, `-${getselectedcolor.slug}`);
        //     realtimeBinding.ideagallery(getselectedcolor, `-${getselectedcolor.slug}`);
        //     realtimeBinding.templates(getselectedcolor, `-${getselectedcolor.slug}`);
        //     window.history.pushState({},document.title, `${inventoryJSVars.permalink}/?color=${getselectedcolor.slug}`);
        // });

        // $(document).on( 'submit', 'form#printmethodcomparison', async function(e) {
        //     e.preventDefault();
        //     var selectedMethod = $(this).find('#compare_value:checked').val();
        //     $('#comparePrintMethod').modal('toggle');
        //     $('.page-loader').fadeIn();
        //     var res = await $.get(`${AA_JS_OBJ.API_BASE}/wp-json/v1/public/template/product?category=${inventoryJSVars.category}&subcategory=${inventoryJSVars.subcategory}&product=${inventoryJSVars.productOrMethod}&method=${selectedMethod}`);
        //     document.title = res.title;
        //     window.history.pushState({},res.title, res.url);
        //     $('.page-loader').fadeOut();
        //     $('#main').html( res.template );
        //     productSlick.initialize();
        //     jQuery.autocolorinit();
        // });

    });

});