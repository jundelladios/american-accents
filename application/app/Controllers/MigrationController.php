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
        $this->path = wp_upload_dir()['basedir'] . '/aa-inventory-migrations/';
        $this->date = date("YmdHisu");
    }

    public function backup( Request $request ) {

        try {

            $validate = new Validator($request);

            $validate->rule('required', 'filename');

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $file = str_replace(' ', '-', $request['filename']).'_'. $this->date . '_' . $this->db . '.sql';

            if(defined( '_APP_EXEC_MYSQL_BIN' ))
            {
                $mysqldumpvars = defined('_APP_EXEC_MYSQLDUMP_VARS') ? _APP_EXEC_MYSQLDUMP_VARS : '';

                $cmd = _APP_EXEC_MYSQL_BIN."mysqldump $mysqldumpvars -h ".$this->host." -u ".$this->user." -p".$this->password." ".$this->db." > " . $this->path.$file;

                exec($cmd);
            }

            return true;

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

            if(defined( '_APP_EXEC_MYSQL_BIN' ))
            {
                DB::statement('SET GLOBAL FOREIGN_KEY_CHECKS=0;');
                
                $cmd = _APP_EXEC_MYSQL_BIN."mysql -h ".$this->host." -u ".$this->user." -p".$this->password." ".$this->db." < " . $this->path.$request['file'];

                exec($cmd);

                DB::statement('SET GLOBAL FOREIGN_KEY_CHECKS=1;');
            }

            return true;

        } catch (\Exception $e) {

            return rest_response( Constants::IMPORT_FAILED . $e->getMessage(), 422 );

        }

    }

    public function migrate( Request $request ) {

        try {

            // Validation
            $validate = new Validator($request);

            $validate->rule('url', array('old', 'new'));

            $validate->rule('required', array('old', 'new'));

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $oldurl = addslashes(str_replace('"', '', json_encode($request['old'])));

            $newurl = addslashes(str_replace('"', '', json_encode($request['new'])));

            $database = DB::connection()->getDatabaseName();

            $updatedatasquery = "SELECT table_schema, table_name, column_name, data_type, ordinal_position from information_schema.columns where table_schema='".$database."'AND data_type='longtext' order by table_name,ordinal_position;";

            $data = DB::select(DB::raw($updatedatasquery));

            $querystr = "";

            foreach($data as $schema) {
                
                // for url in json string
                $querystr .= "UPDATE `".$schema->table_schema."`.".$schema->table_name." SET ".$schema->column_name."=REPLACE(".$schema->column_name.", '".$oldurl."', '".$newurl."');";

                // for url non json
                $querystr .= "UPDATE `".$schema->table_schema."`.".$schema->table_name." SET ".$schema->column_name."=REPLACE(".$schema->column_name.", '".$request['old']."', '".$request['new']."');";
            }

            DB::unprepared($querystr);

            return true;
            
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