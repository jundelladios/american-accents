<?php

// clip arts lists

function aa_sc_clipart( $atts ) {

    $default = array(
        'ref_id' => null,
        'col' => 7,
        'colxl' => 5,
        'collg' => 4,
        'colmd' => 3,
        'colsm' => 2, 
        'colxs' => 1
    );

    $param = shortcode_atts($default, $atts);

    if( !$param['ref_id'] ) { return `<div></div>`; }

    $cliparts = (new Api\Crud\ClipArts\Retrieve)->get([
        'id' => $param['ref_id']
    ]);

    $apiRequest = aa_wp_request_handler( $cliparts );

    if(!$apiRequest['data']) { return `<div></div>`; }

    ob_start();

    ?>

    <div class="aa-grid 
    aa-grid-<?php echo $param['col']; ?> 
    aa-grid-<?php echo $param['colxl']; ?>-xl
    aa-grid-<?php echo $param['collg']; ?>-lg
    aa-grid-<?php echo $param['colmd']; ?>-md
    aa-grid-<?php echo $param['colsm']; ?>-sm
    aa-grid-<?php echo $param['colxs']; ?>-xs
    ">

        <?php foreach($apiRequest['data'] as $clipart ): ?>

            <?php

                foreach( $clipart['collections'] as $cs ):
                
            ?>

                <div class="position-relative clipartwrap">
                    <?php if( isset( $cs['image'] )): ?>
                        <div class="p-3 clipartimg">
                            <?php aa_lazyimg([
                                'src' => $cs['image'],
                                'alt' => isset($cs['title']) ? $cs['title'] : ''
                            ]); ?>
                        </div>
                    <?php endif; ?>

                    <?php if( isset( $cs['title'] )): ?><span class="clipartlabel"><?php echo $cs['title']; ?></span><?php endif; ?>
                </div>
            
            <?php endforeach; ?>

        <?php endforeach; ?>

    </div>

    <?php


    $html = ob_get_clean();

    return $html;

}

add_shortcode('aa_sc_clipart', 'aa_sc_clipart');