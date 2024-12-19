<?php
/**
 * 
 * Dynamic Pages Handler
 * 
 */

 namespace Api;

 class DynamicPages {

    public $page = null;

    public function get() {

        return $this->page;

    }

    public function set( $page ) {

        $this->page = $page;

    }

 }