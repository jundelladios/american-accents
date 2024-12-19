<?php
/**
 * @package AA_Project
 * 
 * Images api
 * 
 */

namespace Api\Crud\PublicRoutes;

use Api\Constants;

use Valitron\Validator;

class Images {

    public function get( $request, $mimes = "[(.jpg)(.jpeg)(.gif)(.png)]$" ) {

        try {

            if( !isset( $request['options'] ) || !is_array( $request['options'] ) ) {
    
                return [];
    
            }
    
            global $wpdb;

            $baseuri = wp_upload_dir()['baseurl'];

            $query = "SELECT pm.meta_id, pm.post_id, pm.meta_value, CONCAT('$baseuri/', pm.meta_value) as meta_file, p.post_title FROM $wpdb->postmeta pm join $wpdb->posts p on p.ID = pm.post_id WHERE pm.meta_key='_wp_attached_file'";

            if( isset( $request['options'] ) && count( $request['options'] ) ) {

                $query .= " AND (";

                $index = 0;

                foreach( $request['options'] as $opt ) {

                    $query .= " pm.meta_value REGEXP '".$opt."\.$mimes' ";
                
                    $index++;

                    if( $index != count( $request['options'] ) ) {

                        $query .= " OR ";

                    }
                }

                $query .= ") ";

            }

            $query .= "GROUP BY pm.meta_id ORDER BY p.post_title";

            $images = $wpdb->get_results( $query );

            return $images;

        } catch( \Exception $e ) {

            return [];

        }

    }

    public function formattitle( $title ) {

        return sanitize_file_name( $title );

    }

}