<?php
/**
 * @package AA_Project
 * 
 * Migration Controller REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Constants;

use Api\Models\ProductColorsModel;

use Api\Models\ProductStockShapesModel;

use Api\Models\ProductColorAndStockShapeModel;

use Valitron\Validator;

use Api\Crud\PublicRoutes\Filters;

use Api\Hasher;

class DownloadController {

    private $variant = [Constants::COLOR_VARIANT_KEY, Constants::STOCK_SHAPE_VARIANT_KEY, Constants::COLOR_STOCKSHAPE_VARIANT_KEY, Constants::NACOLOR_VARIANT_KEY];

    private $required = ['slug', 'printmethodid', 'variant', 'output_filename'];

    public function templates( Request $request ) {

        try {

            $data = $request->get_params();

            $validate = new Validator($data);

            $validate->rule('required', $this->required);

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            if( !in_array( $data['variant'], $this->variant ) ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            $q = ProductColorsModel::query();

            if( $data['variant'] == "stockshape" ) {

                $q = ProductStockShapesModel::query();

            }

            if( $data['variant'] == "color-stockshape" ) {

                $q = ProductColorAndStockShapeModel::query();

            }
            

            $q->select('templates');

            $q->where('slug', $data['slug']);

            $q->where('product_print_method_id', Hasher::decode($data['printmethodid']));

            $results = $q->first();

            $results = $results->templatedata;

            $results = is_array( $results ) ? $results : [];

            $files = [];

            foreach( $results as $template ):

                $files[] = $template->link;

            endforeach;

            $this->download( $files, $data['output_filename'] );

            exit;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }



    public function ideagalleries( Request $request ) {

        try {

            $data = $request->get_params();

            $validate = new Validator($data);

            $validate->rule('required', $this->required);

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            if( !in_array( $data['variant'], $this->variant ) ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            $q = ProductColorsModel::query();

            if( $data['variant'] == "stockshape" ) {

                $q = ProductStockShapesModel::query();

            }

            if( $data['variant'] == "color-stockshape" ) {

                $q = ProductColorAndStockShapeModel::query();

            }

            $q->select('idea_galleries');

            $q->where('slug', $data['slug']);

            $q->where('product_print_method_id', Hasher::decode($data['printmethodid']));

            $results = $q->first();

            $results = $results->ideagallerydata;

            $results = is_array( $results ) ? $results : [];

            $files = [];

            foreach( $results as $idea ):

                if( !$idea->usecurfile && $idea->downloadLink ) {
                    $files[] = $idea->downloadLink;
                } else {
                    $files[] = $idea->image;
                }

            endforeach;

            $this->download( $files, $data['output_filename'] );

            exit;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }



    public function images( Request $request ) {

        try {

            $data = $request->get_params();

            $validate = new Validator($data);

            $validate->rule('required', $this->required);

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            if( !in_array( $data['variant'], $this->variant ) ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            $q = ProductColorsModel::query();

            if( $data['variant'] == "stockshape" ) {

                $q = ProductStockShapesModel::query();

            }

            if( $data['variant'] == "color-stockshape" ) {

                $q = ProductColorAndStockShapeModel::query();

            }

            $q->select('image');

            $q->where('slug', $data['slug']);

            $q->where('product_print_method_id', Hasher::decode($data['printmethodid']));

            $results = $q->first();

            $results = $results->imagedata;

            $results = is_array( $results ) ? $results : [];

            $files = [];

            foreach( $results as $idea ):

                if( !$idea->usecurfile && $idea->downloadLink ) {
                    $files[] = $idea->downloadLink;
                } else {
                    $files[] = $idea->image;
                }

            endforeach;

            $this->download( $files, $data['output_filename'] );

            exit;

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }



    public function compliances( Request $request ) {

        try {

            $data = $request->get_params();

            $validate = new Validator($data);

            $validate->rule('required', [
                'product'
            ]);

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $product = (new Filters)->getSingleProduct(array(
                'product' => $data['product']
            ));

            if( !$product || is_wp_error( $product ) ) {

                return rest_response( Constants::NOT_FOUND, 404 );
            }

            $compliances =  $product['productline']['compliancesdata'];

            $files = [];

            foreach( $compliances as $comp ):

                if( isset( $comp['documentLink'] ) && !empty($comp['documentLink']) ) {

                    $files[] = $comp['documentLink'];

                }

            endforeach;

            $this->download( $files, $product['product_method_combination_name'] . ' compliances.zip' );

            exit;

        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );
            
        }

    }


    public function download( $files, $filename ) {

        try {

            # create new zip object
            $zip = new \ZipArchive();

            # create a temp file & open it
            $tmp_file = tempnam('.', '');
            $zip->open($tmp_file, \ZipArchive::CREATE);

            # loop through each file
            foreach ($files as $file) {
                # download file
                $download_file = file_get_contents($file);
                #add it to the zip
                $zip->addFromString(basename($file), $download_file);
            }

            # close zip
            $zip->close();

            # send the file to the browser as a download
            header('Content-disposition: attachment; filename="' . $filename . '"');
            header('Content-type: application/zip');
            readfile($tmp_file);
            unlink($tmp_file);

            exit;

        } catch( \Execption $e ) {

            return rest_response( "This page is not available.", 404 );

        }

    }

}