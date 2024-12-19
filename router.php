<?php

// custom wp routes

Routes::map('/ref/debug', function( $params ) {
    phpinfo();
});

Routes::map('/ref/qrcode', function( $params ) {
    $page = new \Api\Pages\QRCode();
    $page->render();
});

Routes::map('/products', function( $params ) {
    $page = new \Api\Pages\ProductsPage();
    $page->render();
});

Routes::map('/product/search', function( $params ) {
    $page = new \Api\Pages\SearchPage();
    $page->render();
});


Routes::map('/product/:category', function( $params ) {
    $page = new \Api\Pages\CategoryPage();
    $page->set(array(
        'slug' => $params['category']
    ));
    $page->render(); 
});

Routes::map('/product/:category/:subcategory', function( $params ) {
    $page = new \Api\Pages\SubcategoryPage();
    $page->set(array(
        'category' => $params['category'],
        'subcategory' => $params['subcategory']
    ));
    // $productline = $page->getJSONData();
    // if( is_array($productline) && count($productline) > 0 && isset($productline[0]) && $productline[0]['is_unprinted'] == 1 ) {

    //     $page->render_unprinted(); 
    // }
    $page->render();
});


Routes::map('/product/:category/:subcategory/print-method/:method', function( $params ) {
    $page = new \Api\Pages\SubcategoryPage();
    $page->set(array(
        'category' => $params['category'],
        'subcategory' => $params['subcategory'],
        'method_filter' => $params['method']
    ));
    $page->render();
});



Routes::map('/product/:category/:subcategory/:product', function( $params ) {
    $page = new \Api\Pages\ProductPage();
    $page->set(array(
        'category' => $params['category'],
        'subcategory' => $params['subcategory'],
        'product' => $params['product'],
        'is_unprinted' => 1
    ));
    $page->render();
});


Routes::map('/product/:category/:subcategory/:product/color/:color', function( $params ) {
    $page = new \Api\Pages\ProductPage();
    $page->set(array(
        'category' => $params['category'],
        'subcategory' => $params['subcategory'],
        'product' => $params['product'],
        'color' => $params['color'],
        'is_unprinted' => 1
    ));
    $page->render();
});


Routes::map('/product/:category/:subcategory/:product/:method', function( $params ) {
    $page = new \Api\Pages\ProductPage();
    $page->set(array(
        'category' => $params['category'],
        'subcategory' => $params['subcategory'],
        'product' => $params['product'],
        'printmethod' => $params['method']
    ));
    $page->render();
});


Routes::map('/product/:category/:subcategory/:product/:method/shape/:shape', function( $params ) {
    $page = new \Api\Pages\ProductPage();
    $page->set(array(
        'category' => $params['category'],
        'subcategory' => $params['subcategory'],
        'product' => $params['product'],
        'printmethod' => $params['method'],
        'shape' => $params['shape']
    ));
    $page->render();
});


Routes::map('/product/:category/:subcategory/:product/:method/color/:color', function( $params ) {
    $page = new \Api\Pages\ProductPage();
    $page->set(array(
        'category' => $params['category'],
        'subcategory' => $params['subcategory'],
        'product' => $params['product'],
        'printmethod' => $params['method'],
        'color' => $params['color']
    ));
    $page->render();
});


Routes::map('/product/:category/:subcategory/:product/:method/color-and-shape/:shape_color', function( $params ) {
    $page = new \Api\Pages\ProductPage();
    $page->set(array(
        'category' => $params['category'],
        'subcategory' => $params['subcategory'],
        'product' => $params['product'],
        'printmethod' => $params['method'],
        'color-shape' => $params['shape_color']
    ));
    $page->render();
});