jQuery( function($) {

    var slickHorizontal = $('.carousel-horizontal');

    var slickVertical = $('.carousel-vertical');

    slickHorizontal.slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        asNavFor: '.carousel-vertical'
    });

    slickVertical.slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        vertical: true,
        asNavFor: '.carousel-horizontal',
        arrows: false,
        dots: false,
        focusOnSelect: true,
        verticalSwiping:true
    });

    // onload
    var activeSlideImgsrc = $('.carousel-horizontal .slick-current .img-data').attr('src');
    $('.data-img-newtab-carousel').attr('href', activeSlideImgsrc);
    slickHorizontal.on('load afterChange', function() {
      $('.data-img-newtab-carousel').attr('href', $('.carousel-horizontal .slick-current .img-data').attr('src'));
    });

    $('.carousel-indicator').click( function() {
        var trigger = $(this).data('trigger');
        if( trigger == "prev" ) {
            slickHorizontal.slick('slickPrev');
        } else {
            slickHorizontal.slick('slickNext');
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

    $.each($('[data-auto-color]'), function() {
        var colorDetect = getContrastYIQ( $(this).data('auto-color') );
        $(this).css({ color: colorDetect });
    });


    var methodsCompare = {
        el: $(`.methodcomparison`),
        data: [],
        load: async function() {
          this.loading();
          var res = await $.get( `${AA_JS_OBJ.API_BASE}/wp-json/v1/public/getProduct`, {
            category: inventoryJSVars.category,
            subcategory: inventoryJSVars.subcategory,
            product: inventoryJSVars.productOrMethod,
            multiple: 1
          });
          this.data = res;
          if( this.data.length < 2 ) {
            $('.compare').remove();
            return;
          }
          this.unload();
          this.bind();
        },
        loading: function() {
          this.el.html( this.loadingTemplate );
        },
        unload: function() {
          $(`.methodcomparison .ajax-loader`).remove();
        },
        bind: function() {
    
          var html = "",
          e = this;
    
          this.data.map( function( row, index ) {
            var finalqty = [];
            var pricingqty = row.pricings.map( q => q.quantity );
            var plinesqty = row.productline.pricing_data.map( q => q.pvalues.map( x => x.quantity ) );
            plinesqty.map( row => {
              finalqty = pricingqty.concat( row );
            });
            finalqty = finalqty.concat( pricingqty);
            finalqty = [...new Set( finalqty )];
    
            html += e.template( row, finalqty );
    
          });
    
          e.el.html( html );
    
        },
        template: function( obj, quantities ) {

          var iterate = {};

          quantities.map( function( qty ) {
            var priceValue = obj.pricings.find(x => x.quantity === qty );
            iterate[qty] = [];
            iterate[qty].push( priceValue.value );
            
            obj.productline.pricing_data.map( function(pdata) {
              var pval = pdata.pvalues.find( x => x.quantity === qty );
              iterate[qty].push( pval.value );
            });
          
          });

          var redirectTo = `${inventoryJSVars.baseURL}product/${obj.cat_slug}/${obj.sub_slug}/${obj.product_slug}/${obj.method_slug}`;
    
          var html = "",
          color = obj.productline.printmethod.method_hex;
    
          html += `
          
          <div class="col-md-4">

            <div class="radio-container">
            <input 
            type="radio" 
            class="compare-radio-url" 
            name="compare-radio" 
            value="${redirectTo}" 
            `;
          
          if( inventoryJSVars.permalink == redirectTo ) { html += `checked`; }

          html += `/>
          <span class="checkmark"></span>
          </div>
    
            <div class="print-method-comparison-item">
              <div class="print-method-header" style="background:${color};">
                <h4 class="text-center">
                  <span class="text-capitalize">${obj.productline.printmethod.method_name}</span> 
                  <span class="text-uppercase">${obj.productline.printmethod.method_name2}</span>
                </h4>
              </div>
    
              <div class="compare-tbl">
                <table>
                  <tr>
                    <td class="th">quantity</td>
                    <td class="th">price</td>
        `;
        
        obj.productline.pricing_data.map( row => {
          html += `<td class="th"><span class="icon ${row.chargetypes.icon}"></span></td>`;
        });
    
    
        html += `
                  </tr>`;

        Object.keys(iterate).map( function(key, index) {
          html += `<tr>`;
          html += `<td class="bold">${key}</td>`;
          iterate[key].map( function(vals) {
            html += `<td>${vals}</td>`;
          });
          html += `</tr>`;
        });

        html += `</table>
              </div>`;


        html += `<div class="print-method-spacer"></div>`;

        html += `<div class="print-method-footer">`;
        
        obj.productline.pricing_data.map( row => {
          html += `<p class="pm-footer-item"><span class="icon ${row.chargetypes.icon} mr-1"></span> ${row.chargetypes.charge_name}</p>`;
        });
        
        html += `</div>`;

        html += `<div class="features-c">`;
        html += `<ul class="tfeature">`;
        var features = $.toJson( obj.productline.printmethod.keyfeatures );

        features.map( fea => {
          html += `<li>
          <span class="icon icon ${fea.image}" style="color: ${color};"></span>
          <span style="color: ${color};">${fea.text}</span>
          </li>`;
        });
        html += `<ul>`;
        html += `</div>`;

        html += `</div>`;
        
        html += `</div>`;
    
          return html;
    
        },
        loadingTemplate: function() {
          var loader = ``;
          loader += `<div class="ajax-loader position-relative">
    
              <div class="background"></div>
    
              <div class="loaderWrap">
                  <div>
                      <div class="loader"></div>    
                      <div class="text">Loading</div>
                  </div>
              </div>
    
          </div>`;
          return loader;
        }
      }
    
      methodsCompare.load();


      var compareMethod = {
        state: null,
        modal: $('#comparePrintMethod'),
        compare: function($url) {
          if( this.state != $url ) {
            this.state = $url;
            this.modal.modal('hide');
            var e = this;
            setTimeout( function() {
              window.location.href = e.state;
            }, 500);
          }
        }
      }

      $('.compare-method-changes').click( function() {
        var url = $('.compare-radio-url:checked').val();
        compareMethod.compare( url );
        return false;
      });

});