<?php
/**
 * @package AA_Project
 * 
 * Subcategory Controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Crud\Subcategories\Insert;

use Api\Crud\Subcategories\Retrieve;

use Api\Crud\Subcategories\Update;

use Api\Crud\Subcategories\Catalog;

use Api\Crud\Subcategories\Remove;

class SubcategoryController {

    public function get( Request $request ) {

        return (new Retrieve)->get( $request );

    }

    public function store( Request $request ) {

        return (new Insert)->store( $request );

    }

    
    public function update( Request $request ) {

        return (new Update)->update( $request );

    }

    public function remove( Request $request ) {

        return (new Remove)->remove( $request );

    }

    public function getCategorize( Request $request ) {

        return (new Retrieve)->categorize( $request );

    }

    public function catalogAssign( Request $request ) {

        return (new Catalog)->assign( $request );

    }

}