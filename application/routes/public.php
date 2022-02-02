<?php


register_rest_route( $apiVersion, 'public/getProducts', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'getProducts' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));

register_rest_route( $apiVersion, 'public/products/search', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'searchProducts' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));

// register_rest_route( $apiVersion, 'public/images', array(
//     array(
//         'methods' => 'GET',
//         'callback' => array( new Api\Controllers\PublicController, 'images' ),
//         'permission_callback' => function( $request ) {
//             return true;
//         }
//     )
// ));

register_rest_route( $apiVersion, 'public/getProduct', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'getSingleProduct' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));

register_rest_route( $apiVersion, 'public/product/variations', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'variations' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, 'public/getFilter', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'getFilter' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, 'public/getProductLines', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'getProductLines' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, '/download/images', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\DownloadController, 'images' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, '/download/templates', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\DownloadController, 'templates' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, '/download/ideagalleries', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\DownloadController, 'ideagalleries' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));

register_rest_route( $apiVersion, '/download/compliances', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\DownloadController, 'compliances' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, 'pdftoimage', array(
    array(
        'methods' => 'POST',
        'callback' => array( new Api\Controllers\GenerateController, 'generate' ),
        'permission_callback' => 'apiAuthorizeCB'
    ),
    // array(
    //     'methods' => 'PUT',
    //     'callback' => array( new Api\Controllers\GenerateController, 'filebird' ),
    //     'permission_callback' => 'apiAuthorizeCB'
    // )
));



// register_rest_route( $apiVersion, 'public/test', array(
//     array(
//         'methods' => 'GET',
//         'callback' => function() {
//             // $image = new Api\Crud\PublicRoutes\Images();
// 			// $filetitle = 'D-PMGRSA_'.$image->formattitle('BLUE');
//             // return $image->get(array(
//             //     'options' => array(
// 			// 		$filetitle.'_main%'
// 			// 	)
//             // ));
//             return sanitize_file_name('D-JOGRSC-(Color)-(Shape #)_YELLOW_SS-1101_ig_1');
//         },
//         'permission_callback' => 'apiAuthorizeCB'
//     )
// ));




register_rest_route( $apiVersion, 'public/filter/getSizes', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'getSizes' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, 'public/filter/getThickness', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'getThickness' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, 'public/filter/getColors', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'getColors' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, 'public/filter/getMethods', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'getMethods' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, 'public/filter/getPrices', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'getPrices' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, 'public/filter/getMaterials', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'getMaterials' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, 'public/filter/getSubcategories', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'getSubcategories' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, 'public/product-templates', array(
    array(
        'methods' => 'GET',
        'callback' => array( new Api\Controllers\PublicController, 'productTemplates' ),
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));


register_rest_route( $apiVersion, 'public/filter/money', array(
    array(
        'methods' => 'GET',
        'callback' => function($request) {
            return (new Api\Crud\PublicRoutes\Filters)->getProductName([
                'combination_name' => 'BMBFW-046'
            ]);
        },
        'permission_callback' => function( $request ) {
            return true;
        }
    )
));