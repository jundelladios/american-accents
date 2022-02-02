<?php

// category shortcode

function aa_sc_category( $atts ) {

    $default = array(
        'ref_id' => null,
    );

    $param = shortcode_atts($default, $atts);

    if( !$param['ref_id'] ) { return `<div></div>`; }

    $cat = aa_wp_request_handler( get_aa_category([
        'strictID' => $param['ref_id'],
        'tree' => true,
        'orderByTree' => 'priority asc'
    ]) );

    if( !$cat ) { return `<div></div>`; }
    
    $nulled = '__nulled';
    
    $iterateTree = [];
    foreach( $cat[0]['subcategories'] as $sbc ) {
        $key = $sbc['categorize_as'] ? $sbc['categorize_as'] : $nulled;
        $iterateTree[$key][] = $sbc;
    }

    $time = null;
    
    $isNew = false;

    $interval = 5;

    if( $cat[0]['created_at'] ) {
        
        $catDate = new DateTime( $cat[0]['created_at'] );
        $now = new DateTime();

        $diffDate = $catDate->diff($now);

        $isNew = (int) $diffDate->format("%d") == 0 && (int) $diffDate->format("%h") <= $interval ? true : false;
    }

    ob_start();

    ?>
    
    <div class="menu-col-item-wrap">
        <div class="submenus-wrap">

            <a href="<?php echo home_url( '/product/' . $cat[0]['cat_slug'] ); ?>" class="category-link ptitle d-block">
                <strong>
                    <?php echo $cat[0]['cat_name']; ?>
                    <?php if( $isNew ): ?>
                        <span class="psubtext" date-time-interval="<?php echo $interval; ?> hours">New!</span>
                    <?php endif; ?>
                </strong>
            </a>

            <div class="grouped-menu-items-wrap" id="gitemwrap">
                <?php if( isset( $iterateTree[$nulled] ) ): ?>
                    <div class="grouped-menu-item-child-list">
                        <ul class="list-unstyled link-ul-item">
                            <?php foreach( $iterateTree[$nulled] as $listlink ): ?>
                                <li class="link-item">
                                    <a href="<?php echo home_url( '/product/' . $cat[0]['cat_slug'] . '/' . $listlink['sub_slug'] ); ?>" class="link-item-anchor"><?php echo $listlink['sub_name']; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if( isset( $iterateTree ) ): ?>
                    <div class="grouped-menu-item-child-list">
                        <?php foreach( $iterateTree as $key => $value ): ?>
                        <?php if( $key != $nulled ): ?>
                            <strong class="stitle d-block"><?php echo $key; ?></strong>
                            <ul class="list-unstyled link-ul-item">
                                <?php foreach( $value as $listlink ): ?>
                                    <li class="link-item">
                                        <a href="<?php echo home_url( '/product/' . $cat[0]['cat_slug'] . '/' . $listlink['sub_slug'] ); ?>" class="link-item-anchor"><?php echo $listlink['sub_name']; ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <?php

    $html = ob_get_clean();

    return $html;

}

add_shortcode('aa_sc_category', 'aa_sc_category');