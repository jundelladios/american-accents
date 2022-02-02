<?php

// Stock shapes

function aa_sc_stockshape( $atts ) {

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

    $scs = (new Api\Crud\CollectionStockShape\Retrieve)->get([
        'id' => $param['ref_id']
    ]);

    $apiRequest = aa_wp_request_handler( $scs );

    if(!$apiRequest['data']) { return `<div></div>`; }

    ob_start();

    ?>

    <div class="aa-grid 
    aa-grid-<?php echo $param['col']; ?> stockshapemodule 
    aa-grid-<?php echo $param['colxl']; ?>-xl
    aa-grid-<?php echo $param['collg']; ?>-lg
    aa-grid-<?php echo $param['colmd']; ?>-md
    aa-grid-<?php echo $param['colsm']; ?>-sm
    aa-grid-<?php echo $param['colxs']; ?>-xs
    ">

        <?php foreach($apiRequest['data'] as $scs ): ?>

            <?php

            foreach( $scs['collections'] as $sc ):

            $code = isset( $sc['code'] ) ? $sc['code'] : '';

            $stockname = isset( $sc['stockname'] ) ? $sc['stockname'] : '';

            $alt = '';
            if($stockname) {
                $alt .= $stockname . ' - ';
            }

            $alt .= $code;
                
            ?>

                <div class="position-relative scwrap">
                    <?php if( isset( $sc['image'] )): ?>
                        <div class="p-3 bgimg">
                            <?php aa_lazyimg([
                                'src' => $sc['image'],
                                'alt' => "$alt",
                                'width' => 'auto',
                                'height' => 'auto'
                            ]); ?>
                        </div>
                    <?php endif; ?>

                    <?php if( $code): ?><span class="sccode"><?php echo $code; ?></span><?php endif; ?>
                    <?php if( $stockname): ?><span class="scname"><?php echo $stockname; ?></span><?php endif; ?>
                </div>

            <?php endforeach; ?>

        <?php endforeach; ?>

    </div>

    <?php


    $html = ob_get_clean();

    return $html;

}

add_shortcode('aa_sc_stockshape', 'aa_sc_stockshape');