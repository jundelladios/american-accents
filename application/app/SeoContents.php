<?php
/**
 * 
 * Wordpress SEO Content
 * 
 */

 namespace Api;

 class SeoContents {

    public $seo = null;

    public function set( $seo ) {

        $this->seo = $seo;

    }

    public function get() {

        return $this->seo;

    }

    public function getTitle() {

        if( isset( $this->seo['title'] ) && !empty( $this->seo['title'] ) ) {

            return $this->seo['title'];

        }

        return null;

    }

    public function getDescription() {

        if( isset( $this->seo['description'] ) && !empty( $this->seo['description'] ) ) {

            return $this->seo['description'];

        }

        return null;

    }


    public function getImage() {

        if( isset( $this->seo['image'] ) && !empty( $this->seo['image'] ) ) {

            return $this->seo['image'];

        }

        return null;

    }

    public function getURL() {

        if( isset( $this->seo['url'] ) && !empty( $this->seo['url'] ) ) {

            return $this->seo['url'];

        }

        return null;

    }

    public function _handle( $obj, $key ) {

        $obj = (array) $obj;

        return isset( $obj[$key] ) ? $obj[$key] : null;

    }

 }