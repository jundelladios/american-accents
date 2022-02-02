<?php
/**
 * @package AA_Project
 * 
 * SUBCATEGORIES INSERT
 * 
 */

namespace Api\Crud\Subcategories;

use Api\Models\ProductSubcategoriesModel;

use Api\Hasher;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

use Api\Crud\PublicRoutes\Images;

use Illuminate\Database\Capsule\Manager as DB;

class Catalog {

    use ControllerTraits;

    public function assign( $request ) {

        try {

            $data = rest_requests( $request->get_params(), ['categoryid']);
        
            // Validation
            $validate = new Validator($data);

            $validate->rule('required', ['categoryid']);


            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $categoryId = Hasher::decode( $data['categoryid'] );

            $subcategories = ProductSubcategoriesModel::select('id', 'sub_name')->where('product_category_id', $categoryId)->get();

            if(count($subcategories)) {

                $values = [];

                foreach( $subcategories as $sub ) {

                    $id = (int) $sub['id'];

                    $cataloglists = (new Images)->get(array(
                        'options' => array(
                            (new Images)->formattitle($sub['sub_name']) . '.*-Catalog.*'
                        )
                    ), '[(.pdf)]$');

                    $catalogs = [];

                    foreach( $cataloglists as $cat ):
                        $imgurl = wp_get_attachment_image_url( $cat->post_id, 'full' );
                        $catalogs[] = array(
                            'catalog' => $cat->meta_file,
                            'title' => get_the_title( $cat->post_id ),
                            'image' => $imgurl,
                        );
                    endforeach;

                    $catalogjson = json_encode($catalogs);

                    $values[] = "($id, '$catalogjson', 'auto-assign', 'auto-assign', 0)";

                }

                if( count( $values ) ) {

                    $table = ProductSubcategoriesModel::getModel()->getTable();

                    $values = implode(', ', $values);

                    $sql = "INSERT INTO $table (id, catalogs, sub_name, sub_slug, product_category_id) VALUES $values ON DUPLICATE KEY UPDATE catalogs = VALUES(catalogs)";

                    DB::unprepared($sql);

                }

            }

            return true;

        } catch(\Exception $e) {

            return $e->getMessage();

        }

    }

}