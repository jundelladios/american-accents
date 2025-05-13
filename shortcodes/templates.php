<?php

// clip arts lists

function aa_sc_templates( $atts ) {

    ob_start();

    $categories = (new Api\Crud\Categories\Retrieve)->get([
        'orderBy' => 'priority',
        'template_section' => 1
    ]);

    if( $categories && isset( $categories['data'] ) && count( $categories['data'] ) ) {

        ?>
        <div class="accordion-module producttemplatessc">
            <?php 
                foreach( $categories['data'] as $tmpcategories ) { 
                    $accordionId = "category-".$tmpcategories['id'];
                    ?>
                    <div class="aa-accordion-module">
                        <button class="acc-btn aa-accordion-child" data-accordion="<?php echo $accordionId; ?>" data-category="<?php echo $tmpcategories['cat_slug']; ?>">
                            <span class="acc-text"><?php echo $tmpcategories['cat_name']; ?></span>
                            <div class="accordion-indicator">
                                <span></span>
                                <span></span>
                            </div>
                        </button>

                        <div class="accordion-child-content" data-accordion-content="<?php echo $accordionId; ?>">
                            <div class="accordion-content" data-category-content="<?php echo $tmpcategories['cat_slug']; ?>">
                                

                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>

        <script type="text/javascript">
            jQuery(document).ready( function($) {

                var productlinesdata = {};
                var producttemplatesdata = {};

                var productlineloader = $('<div/>', { class: 'productlineloaderwrap' });
                for(var i=0;i<4;i++) {
                    productlineloader.append(
                        $('<div/>', { class: 'd-flex justify-content-between' })
                        .append(
                            $('<div/>', { class: 'skeleton animation mb-5 d-block', style: 'max-width: 50%;height:25px;' }),
                            $('<div/>', { class: 'skeleton animation mb-5 d-block', style: 'max-width: 25px;height:25px;border-radius:50%;' })
                        )
                    );
                }


                var producttemplateloader = $('<div/>', { class: 'producttemplateloaderwrap aa-grid dltemplatecols' });
                for(var i=0;i<8;i++) {
                    producttemplateloader.append(
                        $('<div/>', { class: 'aacolitem' })
                        .append($('<div/>', { class: 'aacolitemwrap' })
                            .append(
                                $('<div/>', { class: 'skeleton animation mb-2', style: 'height: 200px;' }),
                                $('<div/>', { class: 'skeleton animation mb-2', style: 'height: 20px;max-width:150px;' }),
                                $('<div/>', { class: 'skeleton animation mb-2', style: 'height: 20px;max-width:100px;' }),
                            )
                        )
                    )
                }


                <?php foreach( $categories['data'] as $tmpcategories ): ?>
                    productlinesdata['<?php echo $tmpcategories['cat_slug']; ?>'] = [];
                <?php endforeach; ?>


                function productLinesTemplateHtml(productlines) {
                    var plinehtml = $('<div/>', { class: 'productlines' });
                    productlines.map(row => {
                        var accordionid = `productline-${row.id}`;
                        plinehtml
                        .append(
                            $('<div/>', { class: 'aa-accordion-module' })
                            .append(
                                $('<button/>', { class: 'acc-btn aa-accordion-child', 'data-accordion': accordionid, 'data-product-line': row.id })
                                .append(
                                    $('<span/>', { class: 'acc-text' }).html(row.plinecombination),
                                    $('<div/>', { class: 'accordion-indicator' })
                                    .append(
                                        $('<span/>'),
                                        $('<span/>')
                                    )
                                ),
                                $('<div/>', { class: 'accordion-child-content', 'data-accordion-content': accordionid })
                                .append(
                                    $('<div/>', { class: 'accordion-content', 'data-pline-content': row.id })
                                    .append(producttemplateloader[0].outerHTML)
                                )
                            )
                        )
                    });

                    return plinehtml;
                }

                async function getProductLines(category) {
                    var elemcontent = $(`[data-category-content="${category}"]`);
                    if(!productlinesdata[category].length) {
                        elemcontent.html(productlineloader);
                    }
                    var plineparam = $.param({
                        category: category,
                        orderby: 'product_subcategories.priority asc, print_methods.priority asc',
                        fields: 'product_subcategories.priority as subcatprio, print_methods.priority as methodprio',
                        subcategory_status: 1
                    });
                    const plines = await $.get(`${AA_JS_OBJ.API_BASE}/wp-json/v1/public/getProductLines?${plineparam}`);
                    const html = productLinesTemplateHtml(plines.data);
                    if(!productlinesdata[category].length) {
                        elemcontent.html(html);
                    }
                    productlinesdata[category] = plines.data;
                }


                function tmpjsondata($json) {
                    try {
                        $jsnarr = JSON.parse($json);
                        if(Array.isArray($jsnarr)) { return $jsnarr; }
                        return [];
                    } catch($e) {
                        return [];
                    }
                }


                function productTemplatesHtml(template) {

                    var wrapper = $('<div/>', { class: `templatepage-${template.page} template-dl-lists` });
                    var tmphtml = $('<div/>', { class: `aa-grid dltemplatecols` });
                    
                    wrapper.append(tmphtml);

                    if( !template.data.length ) {
                        wrapper = $('<p/>', { class: 'text-center' }).text('There is no product template on this product line.');
                    }

                    template.data.map(row => {
                        //console.log(row);
                        const colorstemplates = tmpjsondata(row.colorstemplates);
                        const colorstocktemplates = tmpjsondata(row.colorstocktemplates);
                        const stockshapetemplates = tmpjsondata(row.stockshapetemplates);

                        colorstemplates.map(cltmp => {
                            tmphtml.append(
                                $('<div/>', { class: 'aacolitem' })
                                .append(
                                    $('<div/>', { class: 'aacolitemwrap' })
                                    .append(
                                        $('<a/>', { href: cltmp.link, target: '_blank', class: 'tmplink' })
                                        .append(
                                            $('<div/>', { class: 'tmpimgewrap' })
                                            .append($('<img/>', { 
                                                'src': AA_JS_OBJ.IMG_PRELOADER,
                                                'data-breeze': AA_JS_OBJ.CDNSRCMIN(cltmp.preview), 
                                                'data-brsrcset': AA_JS_OBJ.SRCSET(cltmp.preview),
                                                'data-brsizes': AA_JS_OBJ.SRCSIZES(),
                                                class: 'br-lazy',
                                                loading: 'lazy'
                                            }))
                                        )
                                        .append($('<h5/>').html(row.product_method_combination_name))
                                        .append($('<span/>').html(cltmp.title))
                                    )
                                )
                            )
                        });

                        colorstocktemplates.map(cstmp => {
                            tmphtml.append(
                                $('<div/>', { class: 'aacolitem' })
                                .append(
                                    $('<div/>', { class: 'aacolitemwrap' })
                                    .append(
                                        $('<a/>', { href: cstmp.link, target: '_blank', class: 'tmplink' })
                                        .append(
                                            $('<div/>', { class: 'tmpimgewrap' })
                                            .append($('<img/>', { 
                                                'src': AA_JS_OBJ.IMG_PRELOADER,
                                                'data-breeze': AA_JS_OBJ.CDNSRCMIN(cstmp.preview), 
                                                'data-brsrcset': AA_JS_OBJ.SRCSET(cstmp.preview),
                                                'data-brsizes': AA_JS_OBJ.SRCSIZES(),
                                                class: 'br-lazy',
                                                loading: 'lazy'
                                            }))
                                        )
                                        .append($('<h5/>').html(row.product_method_combination_name))
                                        .append($('<span/>').html(cstmp.title))
                                    )
                                )
                            )
                        });

                        stockshapetemplates.map(stmp => {
                            tmphtml.append(
                                $('<div/>', { class: 'aacolitem' })
                                .append(
                                    $('<div/>', { class: 'aacolitemwrap' })
                                    .append(
                                        $('<a/>', { href: stmp.link, target: '_blank', class: 'tmplink' })
                                        .append(
                                            $('<div/>', { class: 'tmpimgewrap' })
                                            .append($('<img/>', {
                                                'src': AA_JS_OBJ.IMG_PRELOADER,
                                                'data-breeze': AA_JS_OBJ.CDNSRCMIN(stmp.preview), 
                                                'data-brsrcset': AA_JS_OBJ.SRCSET(stmp.preview),
                                                'data-brsizes': AA_JS_OBJ.SRCSIZES(),
                                                class: 'br-lazy',
                                                loading: 'lazy'
                                            }))
                                        )
                                        .append($('<h5/>').html(row.product_method_combination_name))
                                        .append($('<span/>').html(stmp.title))
                                    )
                                )
                            )
                        });
                    });

                    if( template.next ) {
                        wrapper.append(
                            $('<div/>', { class: 'text-center mt-5 mb-5 btn-template-loadmorewrap' })
                            .append($('<button/>', { class: 'btn btn-primary', 'data-productline-loadmore': template.id }).text('Load More'))
                        );
                    }

                    $(`[data-pline-content="${template.id}"] .producttemplateloaderwrap`).remove();
                    return wrapper;

                }

                async function getProductTemplates(productline, callback) {
                    var elemcontent = $(`[data-pline-content="${productline}"]`);
                    
                    if(!producttemplatesdata[productline]) {
                        producttemplatesdata[productline] = {
                            data: [],
                            next: 1,
                            page: 1,
                            id: productline
                        };
                    }

                    var ptemplatesparam = $.param({
                        paginate: 40,
                        productlineid: productline,
                        page: producttemplatesdata[productline].page
                    });

                    const ptemplates = await $.get(`${AA_JS_OBJ.API_BASE}/wp-json/v1/public/product-templates?${ptemplatesparam}`);
                    producttemplatesdata[productline].data = ptemplates.data;
                    producttemplatesdata[productline].next = ptemplates.next_page_url;
                    const htmltemplate = productTemplatesHtml(producttemplatesdata[productline]);
                    callback({ productline: productline, template: htmltemplate, page: producttemplatesdata[productline].page });
                    producttemplatesdata[productline].page++;
                    producttemplatesdata[productline].id = productline;
                    window.AALazyLoadInstance.update();
                }

                $(document).on('aa_accordion_show_done', '[data-category]', function() {
                    var category = $(this).data('category');
                    if(productlinesdata[category].length) { return false; }
                    getProductLines(category);
                });


                $(document).on('aa_accordion_show_done', '[data-product-line]', function() {
                    var productline = $(this).data('product-line');
                    if(producttemplatesdata[productline]) { return false; }
                    getProductTemplates(productline, function(obj) {
                        $(`[data-pline-content="${obj.productline}"]`).html(obj.template);
                    });
                });

                $(document).on('click', '[data-productline-loadmore]', function() {
                    var btne = $(this);
                    var productline = btne.data('productline-loadmore');
                    btne.prop('disabled', true);
                    btne.text('Loading...');
                    getProductTemplates(productline, function(obj) {
                        var prevpage = obj.page-1;
                        $(`.templatepage-${prevpage}`).append(obj.template);
                        btne.remove();
                    });
                });
            });
        </script>
        <?php
        
    } else {
        ?>
        <div></div>
        <?php
    }

    $html = ob_get_clean();

    return $html;

}


add_shortcode('aa_sc_templates', 'aa_sc_templates');