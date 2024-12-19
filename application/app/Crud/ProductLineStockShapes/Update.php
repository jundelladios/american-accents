<?php
/**
 * @package AA_Project
 * 
 * PRODUCT LINES Color Update
 * 
 */

namespace Api\Crud\ProductLineStockShapes;

use Api\Hasher;

use Api\Models\ProductLineStockShapeModel;

use Api\Models\ProductLinesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Update {

    use ControllerTraits;

    private $required = ['product_line_id', 'collection_stockshape_id', 'id'];

    private $statuses = ['priority'];

    private $allowedNulls = ['title'];

    public function update( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required, 
                $this->statuses,
                $this->allowedNulls
            ));

            $data['id'] =  Hasher::decode( $data['id'] );

            $data['product_line_id'] = Hasher::decode( $data['product_line_id'] );

            $data['collection_stockshape_id'] = Hasher::decode( $data['collection_stockshape_id'] );

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', $this->required );

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $update = ProductLineStockShapeModel::find( $data['id'] );

            if( !$update ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            $res = $this->updateOrPostHelper( $update, $data, ProductLineStockShapeModel::query(), $this->allowedNulls );

            return ProductLinesModel::where('id', $data['product_line_id'])->with(['premiumbg', 'stockshapes'])->first();

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}