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

use Rah\Danpu\Dump;

use Rah\Danpu\Export;

use Rah\Danpu\Import;

use Illuminate\Database\Capsule\Manager as DB;

use Valitron\Validator;

use Api\Traits\ControllerTraits;

class MigrationController {

    use ControllerTraits;

    private $host, $db, $user, $password, $path, $date;

    public function __construct() {
        $this->host = _APP_DB_HOST;
        $this->db = _APP_DB_NAME;
        $this->user = _APP_DB_USER;
        $this->password = _APP_DB_PASSWORD;
        $this->path = american_accent_plugin_base_dir() . 'migrations/';
        $this->date = date("YmdHisu");
    }

    public function backup() {

        try {
            $file = $this->path . $this->date . '_' . $this->db . '.sql';
            $dump = new Dump;
            $dump
                ->file($file)
                ->dsn('mysql:dbname='.$this->db.';host='.$this->host)
                ->user($this->user)
                ->pass($this->password)
                ->tmp('/tmp');
            new Export($dump);
            return [
                'message' => Constants::EXPORT_SUCCESS
            ];
        } catch (\Exception $e) {
            return rest_response( Constants::EXPORT_FAILED . $e->getMessage(), 422 );
        }
    }

    public function restore( Request $request ) {

        try {

            // Validation
            $validate = new Validator($request);

            $validate->rule('required', 'file');

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }
            
            DB::statement('SET GLOBAL FOREIGN_KEY_CHECKS=0;');
            $dump = new Dump;
            $dump
                ->file($this->path . $request['file'])
                ->dsn('mysql:dbname='.$this->db.';host='.$this->host)
                ->user($this->user)
                ->pass($this->password)
                ->tmp('/tmp');

            new Import($dump);
            DB::statement('SET GLOBAL FOREIGN_KEY_CHECKS=1;');
            return [
                'message' => Constants::IMPORT_SUCCESS
            ];

        } catch (\Exception $e) {

            return rest_response( Constants::IMPORT_FAILED . $e->getMessage(), 422 );

        }

    }

    public function migrate( Request $request ) {

        try {

            // Validation
            $validate = new Validator($request);

            $validate->rule('required', 'old');

            $validate->rule('required', 'new');

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $query = $this->updateURLStatements([
                // product categories
                [
                    "table" => "product_categories",
                    "column" => "category_banner"
                ],
                [
                    "table" => "product_categories",
                    "column" => "category_banner_content"
                ],
                [
                    "table" => "product_categories",
                    "column" => "notes"
                ],
                // product lines
                [
                    "table" => "product_lines",
                    "column" => "image"
                ],
                [
                    "table" => "product_lines",
                    "column" => "features"
                ],
                [
                    "table" => "product_lines",
                    "column" => "features_pivot"
                ],
                [
                    "table" => "product_lines",
                    "column" => "compliances"
                ],
                [
                    "table" => "product_lines",
                    "column" => "colors"
                ],
                [
                    "table" => "product_lines",
                    "column" => "banner_img"
                ],
                [
                    "table" => "product_lines",
                    "column" => "premium_backgrounds"
                ],
                // print methods
                [
                    "table" => "print_methods",
                    "column" => "method_desc"
                ],
                [
                    "table" => "print_methods",
                    "column" => "method_desc_short"
                ],
                [
                    "table" => "print_methods",
                    "column" => "keyfeatures"
                ],
                // products
                [
                    "table" => "products",
                    "column" => "product_description"
                ],
                // product subcategories
                [
                    "table" => "product_subcategories",
                    "column" => "sub_description"
                ],
                [
                    "table" => "product_subcategories",
                    "column" => "catalogs"
                ],
                [
                    "table" => "product_subcategories",
                    "column" => "banner_img"
                ],
                // product print method
                [
                    "table" => "product_print_method",
                    "column" => "description"
                ],
                [
                    "table" => "product_print_method",
                    "column" => "features_options"
                ],
                [
                    "table" => "product_print_method",
                    "column" => "features_options2"
                ],
                [
                    "table" => "product_print_method",
                    "column" => "templates"
                ],
                [
                    "table" => "product_print_method",
                    "column" => "feature_img"
                ],
                // clip arts
                [
                    "table" => "cliparts",
                    "column" => "clipartdata"
                ],
                // seo contents
                [
                    "table" => "product_categories",
                    "column" => "seo_content"
                ],
                [
                    "table" => "product_subcategories",
                    "column" => "seo_content"
                ],
                [
                    "table" => "product_print_method",
                    "column" => "seo_content"
                ],
                [
                    "table" => "product_lines",
                    "column" => "seo_content"
                ],
                // colors
                [
                    "table" => "collection_colors",
                    "column" => "colorjson"
                ],
                // imprint type product line
                [
                    "table" => "imprint_type_product_line",
                    "column" => "image"
                ],
                // product colors
                [
                    "table" => 'product_colors',
                    "column" => 'colorimageurl'
                ],
                [
                    "table" => "product_colors",
                    "column" => "image"
                ],
                [
                    "table" => "product_colors",
                    "column" => "templates"
                ],
                [
                    "table" => "product_colors",
                    "column" => "idea_galleries"
                ],
                // product stock shapes
                [
                    'table' => 'product_stockshapes',
                    'column' => 'image'
                ],
                [
                    'table' => 'product_stockshapes',
                    'column' => 'templates'
                ],
                [
                    'table' => 'product_stockshapes',
                    'column' => 'idea_galleries'
                ],
                [
                    'table' => 'product_stockshapes',
                    'column' => 'stockimage'
                ],
                // product color + stockshape
                [
                    'table' => 'product_color_stockshape',
                    'column' => 'image'
                ],
                [
                    'table' => 'product_color_stockshape',
                    'column' => 'templates'
                ],
                [
                    'table' => 'product_color_stockshape',
                    'column' => 'idea_galleries'
                ],
                // collectio premium bg
                [
                    'table' => 'collection_premium_backgrounds',
                    'column' => 'collection'
                ],
                // collection stock shape
                [
                    'table' => 'collection_stock_shape',
                    'column' => 'collection'
                ],
                // imprint_types
                [
                    'table' => 'imprint_types',
                    'column' => 'body'
                ],
            ], $request['old'], $request['new']);

            DB::unprepared($query);

            return "saved";
            
        } catch (\Exception $e) {

            return rest_response( "Failed " . $e->getMessage(), 422 );

        }

    }

    public function remove( Request $request ) {

        $validate = new Validator($request);

        $validate->rule('required', 'file');

        if( !$validate->validate() ) {

            return rest_response( $validate->errors(), 422 );

        }

        try {

            $ret = wp_delete_file( $this->path . $request['file'] );

            return "Removed";

        } catch( \Execption $e ) {

            return rest_response( "Failed to remove file." . $e->getMessage(), 422 );

        }

    }

}