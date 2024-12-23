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

use Valitron\Validator;

class GenerateController {

    // private $pdfs = ['pdf', 'title'];

    // private $filebird = ['id'];

    // private $formatOutput = "png";

    // public function generatev2( Request $request ) {

    //     global $wpdb;

    //     try {

    //         $params = rest_requests( $request->get_params(), array_merge( 
    //             $this->pdfs
    //         ));


    //         // Validation
    //         $validate = new Validator($params);

    //         $validate->rule('required', $this->pdfs);


    //         $filedata = pathinfo( $params['pdf'] );

    //         $exists = $filedata['dirname'].'/'.$filedata['filename'].'.'.$this->formatOutput;

    //         if( file_exists( \Api\Media::urlToPath( $exists ) ) ) {

    //             // no generation could be made.

    //             return \Api\Media::getImageByID(attachment_url_to_postid($exists));

    //         }


    //         $filename = pathinfo(basename( $params['pdf'] ), PATHINFO_FILENAME);

    //         $filetype = wp_check_filetype( $params['pdf'] );

    //         if( $filetype['ext'] == "pdf" ) {

    //             $filepath = \Api\Media::urlToPath( $params['pdf'] );

    //             $firstPDFPage = $filepath."[0]";

    //             $tmp = american_accent_plugin_base_dir() . 'tmp/';

    //             $uniqid = abs( crc32( uniqid() ) );

    //             $output = $tmp.sanitize_file_name( $filename )."-$uniqid.".$this->formatOutput;


    //             if( _APP_LOCALMODE ) {

    //                 $execcmd = 'magick convert -background white -alpha remove "'.$firstPDFPage.'" -resize 800x -quality 100 "'.$output.'"';

    //                 exec( $execcmd );

    //             } else {

    //                 $im = new \Imagick();
                    
    //                 $im->readimage($firstPDFPage); 
                    
    //                 $im->setImageBackgroundColor('#ffffff');
                    
    //                 $im = $im->flattenImages();
                    
    //                 $im->setImageFormat($this->formatOutput);
					
	// 				$im->setImageUnits(\Imagick::RESOLUTION_PIXELSPERINCH);

    //                 $im->resizeImage( 800, 800, \Imagick::FILTER_LANCZOS, 1, TRUE );
                    
    //                 $im->setImageCompressionQuality(80);
                    
    //                 $im->setCompressionQuality(80);
                    
    //                 $im->writeImage($output); 
                    
    //                 $im->clear();
                    
    //                 $im->destroy();

    //             }
                
    //             $attachment = \Api\Media::templateToMedia( $output, $params['title'] );


    //             if( !$attachment ) {

    //                 return rest_response( "Failed to generate PDF to Image", 422 );

    //             }

    //             unlink( $output );

    //             return $attachment;

    //         }

    //         return rest_response( "File extension not allowed", 404 );

    //     } catch( \Exception $e ) {

    //         return rest_response( "This page is not available. " . $e->getMessage(), 404 );

    //     }

    // }


    // public function filebird( Request $request ) {

    //     // if filebird was installed - categorized to PDF TO IMAGE Folder
    //     global $wpdb;

    //     $params = rest_requests( $request->get_params(), array_merge( 
    //         $this->filebird
    //     ));

    //     // Validation
    //     $validate = new Validator($params);

    //     $validate->rule('required', $this->filebird);

    //     if ( is_plugin_active( 'filebird-pro/filebird.php' ) ) {
            
    //         try {

    //             $folder = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}fbv where name='PDF TO IMAGE GENERATED'");

    //             if( isset( $folder[0]->id ) ) {

    //                 $exists = $wpdb->get_results("SELECT attachment_id FROM {$wpdb->prefix}fbv_attachment_folder where attachment_id=".$params['id'].";");
                    
    //                 if( !isset( $exists[0]->attachment_id ) ) {

    //                     $wpdb->insert("{$wpdb->prefix}fbv_attachment_folder", array(
    //                         'folder_id' => $folder[0]->id,
    //                         'attachment_id' => $params['id']
    //                     ));
                        
    //                     return 1;

    //                 }

    //                 return 0;

    //             }

    //             return rest_response( array(
    //                 'folder_id' => $folder[0]->id,
    //                 'attachment_id' => $params['id']
    //             ), 200 );

    //         } catch( \Exception $e ) {

    //             return rest_response( "invalid request", 422 );
    //         }

    //     }

    //     return rest_response( "plugin not installed", 422 );

    // }


    public function generate( Request $request ) {

        // Validation
        $params = rest_requests( $request->get_params(), array_merge( 
            ['id']
        ));

        // Validation
        $validate = new Validator($params);

        $validate->rule('required', ['id']);

        if( class_exists('pdf_thumbnail_generator') ) {
            $imgurl = get_pdf_thumbnail_url( $params['id'] );
        } else {
            $imgurl = false;
        }

        return [
            'image' => $imgurl
        ];

    }

}