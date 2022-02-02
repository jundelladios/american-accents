<?php

// premium backgrounds

function aa_sc_premiumbg( $atts ) {

    $default = array(
        'ref_id' => null,
        'col' => 8,
        'colxl' => 5,
        'collg' => 4,
        'colmd' => 3,
        'colsm' => 2, 
        'colxs' => 1
    );

    $param = shortcode_atts($default, $atts);

    if( !$param['ref_id'] ) { return `<div></div>`; }

    $bgs = (new Api\Crud\CollectionPremiumBackground\Retrieve)->get([
        'id' => $param['ref_id']
    ]);

    $apiRequest = aa_wp_request_handler( $bgs );

    if(!$apiRequest['data']) { return `<div></div>`; }

    ob_start();

    ?>

    <div class="aa-grid premiumbgmodule 
    aa-grid-<?php echo $param['col']; ?> 
    aa-grid-<?php echo $param['colxl']; ?>-xl
    aa-grid-<?php echo $param['collg']; ?>-lg
    aa-grid-<?php echo $param['colmd']; ?>-md
    aa-grid-<?php echo $param['colsm']; ?>-sm
    aa-grid-<?php echo $param['colxs']; ?>-xs
    ">

        <?php foreach($apiRequest['data'] as $bgs ): ?>

            <?php

            foreach( $bgs['collections'] as $bg ):

            $code = isset( $bg['code'] ) ? $bg['code'] : '';

            $type = isset( $bg['type'] ) ? $bg['type'] : '';
                
            ?>

                <div class="position-relative bgwrap">
                    <?php if( isset( $bg['image'] ) && $bg['image'] ): ?>
                        <div class="p-3 bgimg">
                            <?php aa_lazyimg([
                                'src' => $bg['image'],
                                'alt' => "$code $type",
                                'width' => 'auto',
                                'height' => 'auto',
                                'class' => 'pbg'
                            ]); ?>
                        </div>
                    <?php else: ?>
                        <div class="p-3 bgimg">
                            <div class="pbg" style="background: <?php echo $bg['hex']; ?>;"></div>
                        </div>
                    <?php endif; ?>

                    <?php if( $code): ?><span class="bgcode"><?php echo $code; ?></span><?php endif; ?>
                    <?php if( $type): ?><span class="bgtype"><?php echo $type; ?></span><?php endif; ?>
                </div>

            <?php endforeach; ?>

        <?php endforeach; ?>

    </div>

    <?php


    $html = ob_get_clean();

    return $html;

}

add_shortcode('aa_sc_premiumbg', 'aa_sc_premiumbg');