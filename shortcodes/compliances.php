<?php

// clip arts lists

function aa_sc_compliances( $atts ) {

    ob_start();

    $categories = (new Api\Crud\Categories\Retrieve)->get([
        'orderBy' => 'priority'
    ]);

    if( $categories && isset( $categories['data'] ) && count( $categories['data'] ) ) {

        ?>
        <div class="accordion-module producttemplatessc">
            <?php 
                foreach( $categories['data'] as $tmpcategories ) { 
                    $accordionId = "category-compliance-".$tmpcategories['id'];
                    ?>
                    <div class="aa-accordion-module">
                        <button class="acc-btn aa-accordion-child" data-accordion="<?php echo $accordionId; ?>" data-category-compliance="<?php echo $tmpcategories['cat_slug']; ?>">
                            <span class="acc-text"><?php echo $tmpcategories['cat_name']; ?></span>
                            <div class="accordion-indicator">
                                <span></span>
                                <span></span>
                            </div>
                        </button>

                        <div class="accordion-child-content" data-accordion-content="<?php echo $accordionId; ?>">
                            <div class="accordion-content" data-category-compliance-content="<?php echo $tmpcategories['cat_slug']; ?>">
                                

                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>

        <script type="text/javascript">
            jQuery(document).ready( function($) {

                var productlinescompliancedata = {};

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

                <?php foreach( $categories['data'] as $tmpcategories ): ?>
                    productlinescompliancedata['<?php echo $tmpcategories['cat_slug']; ?>'] = [];
                <?php endforeach; ?>


                function productLinesComplianceTemplateHtml(productlines) {
                    var plinehtml = $('<div/>', { class: 'productlines' });
                    productlines.map(row => {
                        var accordionid = `productline-compliances-${row.id}`;

                        var tmphtml = $('<div/>', { class: `aa-grid dltemplatecols` });

                        row.compliancesdata.map(comp => {
                            tmphtml.append(
                                $('<div/>', { class: 'aacolitem' })
                                .append(
                                    $('<div/>', { class: 'aacolitemwrap' })
                                    .append(
                                        $('<a/>', { href: comp.documentLink, target: '_blank', class: 'tmplink' })
                                        .append(
                                            $('<div/>', { class: 'tmpimgewrap' })
                                            .append($('<img/>', { 
                                                src: AA_JS_OBJ.CDNSRCMIN(comp.previewImage), 
                                                srcset: AA_JS_OBJ.SRCSET(comp.previewImage),
                                                'data-sizes': 'auto',
                                                class: 'lazyload lz-blur'
                                            }))
                                        )
                                        .append($('<h5/>').html(comp.compliance))
                                    )
                                )
                            )
                        });

                        plinehtml
                        .append(
                            $('<div/>', { class: 'aa-accordion-module' })
                            .append(
                                $('<button/>', { class: 'acc-btn aa-accordion-child', 'data-accordion': accordionid })
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
                                    $('<div/>', { class: 'accordion-content' })
                                    .append(tmphtml)
                                )
                            )
                        )
                    });

                    return plinehtml;
                }

                async function getProductLines(category) {
                    var elemcontent = $(`[data-category-compliance-content="${category}"]`);
                    if(!productlinescompliancedata[category].length) {
                        elemcontent.html(productlineloader);
                    }
                    var plineparam = $.param({
                        category: category,
                        orderby: 'product_subcategories.priority asc, print_methods.priority asc',
                        fields: 'product_subcategories.priority as subcatprio, print_methods.priority as methodprio',
                        withcompliance: 1
                    });
                    const plines = await $.get(`${AA_JS_OBJ.API_BASE}/wp-json/v1/public/getProductLines?${plineparam}`);
                    const html = productLinesComplianceTemplateHtml(plines.data);
                    if(!productlinescompliancedata[category].length) {
                        elemcontent.html(html);
                    }
                    productlinescompliancedata[category] = plines.data;
                }


                $(document).on('aa_accordion_show_done', '[data-category-compliance]', function() {
                    var category = $(this).data('category-compliance');
                    if(productlinescompliancedata[category].length) { return false; }
                    getProductLines(category);
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


add_shortcode('aa_sc_compliances', 'aa_sc_compliances');