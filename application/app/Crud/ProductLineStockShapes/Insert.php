<?php
/**
 * @package AA_Project
 * 
 * PRODUCT LINES Color INSERT
 * 
 */

namespace Api\Crud\ProductLineStockShapes;

use Api\Hasher;

use Api\Models\ProductLineStockShapeModel;

use Api\Models\ProductLinesModel;

use Api\Traits\ControllerTraits;

use Api\Constants;

use Valitron\Validator;

class Insert {

    use ControllerTraits;

    private $required = ['product_line_id', 'collection_stockshape_id'];

    private $statuses = ['priority'];

    private $allowedNulls = ['title'];

    public function store( $request ) {

        try {

            // allowed requests
            $data = rest_requests( $request->get_params(), array_merge( 
                $this->required, 
                $this->statuses,
                $this->allowedNulls
            ));

            $data['product_line_id'] = Hasher::decode( $data['product_line_id'] );

            $data['collection_stockshape_id'] = Hasher::decode( $data['collection_stockshape_id'] );

            // Validation
            $validate = new Validator($data);

            $validate->rule('required', $this->required );

            if( !$validate->validate() ) {

                return rest_response( $validate->errors(), 422 );

            }

            $res = $this->updateOrPostHelper( new ProductLineStockShapeModel, $data, ProductLineStockShapeModel::query(), $this->allowedNulls );

            return ProductLinesModel::where('id', $data['product_line_id'])->with(['premiumbg', 'stockshapes'])->first();

        } catch( \Exception $e ) { return $e->getMessage();
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }

}