<?php
/**
 * @package AA_Project
 * 
 * PUBLIC ROUTES REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\PublicRoutes\Filters;

use Api\Crud\PublicRoutes\Images;

use Api\Crud\PublicRoutes\Variations;

class PublicController {

    public function getFilter( Request $request ) {

        return (new Filters)->getFilter( $request->get_params() );

    }

    public function getProducts( Request $request ) {

        return (new Filters)->getProducts( $request->get_params() );

    }

    public function searchProducts( Request $request ) {

        return (new Filters)->searchProducts( $request->get_params() );

    }


    public function getSingleProduct( Request $request ) {

        return (new Filters)->getSingleProduct( $request->get_params() );

    }

    public function getProductLines( Request $request ) { 

        return (new Filters)->getProductLines( $request->get_params() );

    }

    public function getSizes( Request $request ) {

        return (new Filters)->getSizes( $request->get_params()  );

    }

    public function getThickness( Request $request ) {

        return (new Filters)->getThickness( $request->get_params()  );

    }


    public function getSubcategories( Request $request ) {

        return (new Filters)->getSubcategories( $request->get_params()  );

    }

    public function getColors( Request $request ) {

        return (new Filters)->getColors( $request->get_params()  );

    }


    public function images( Request $request ) {

        return (new Images)->get( $request );

    }

    public function variations( Request $request ) {

        return (new Filters)->variations( $request );

    }


    public function productTemplates( Request $request ) {

        return (new Filters)->productTemplates( $request );

    }

}