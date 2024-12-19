<?php
/**
 * @package AA_Project
 * 
 * Product Category Page
 * 
 */

namespace Api\Pages;

use Api\Pages\Page;

use Api\Assets;

class QRCode extends Page {

    public function render() {

        add_filter( 'query_vars', array( $this, 'queryVars' ) );

        add_filter('template_redirect', array( $this, 'template' ));

    }

    public function queryVars($vars) {

        $vars[] = 'content';

        return $vars;

    }

    public function template() {

        Page::found();

        ob_start();

        get_header();

        ?>
        
        <div class="container" style="margin-top: 100px; margin-bottom: 100px; text-align: center;">

            <h1>QR Code Generator</h1>

            <p>American Accents QR Code Generator.</p>

            <?php 

            echo  do_shortcode( '[kaya_qrcode content="'.get_query_var('content').'"]' );
            
            ?>
			
			<p style="margin-top: 20px;"><?php if(get_query_var('content')): echo get_query_var('content'); endif; ?></p>

        </div>


        <?php

        get_footer();

        $html = ob_get_clean();

        echo $html;

        exit;

    }
}