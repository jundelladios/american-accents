<?php
/**
 * @package AA_Project
 * 
 * VDS Items REST API
 * 
 */

namespace Api\Controllers;

use \WP_REST_Request as Request;

use Api\Models\ProductColorsModel;

use Api\Models\ProductColorAndStockShapeModel;

use Api\Models\ProductStockShapesModel;

use Api\Hasher;

use Api\Models\ProductPrintMethodModel;

class VDSItemsController {


    public function vdsvariantItems( $request ) {

        $id = Hasher::decode( $request['id'] );

        $colors = ProductColorsModel::select('vdsid', 'vdsproductid')->whereNotNull(['vdsid', 'vdsproductid'])->where('product_print_method_id', $id)->get();

        $stockshapes = ProductStockShapesModel::select('vdsid', 'vdsproductid')->whereNotNull(['vdsid', 'vdsproductid'])->where('product_print_method_id', $id)->get();

        $colorstockshapes = ProductColorAndStockShapeModel::select('vdsid', 'vdsproductid')->whereNotNull(['vdsid', 'vdsproductid'])->where('product_print_method_id', $id)->get();
        
        return [
            'colors' => $colors,
            'stockshapes' => $stockshapes,
            'color_stockshapes' => $colorstockshapes
        ];
    }

    public function index( Request $request ) {

        try {

            return $this->vdsvariantItems( $request );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }
    }


    public function sageConnect($contextparam = array()) {

        $vdsaccid = carbon_get_theme_option('aa_admin_settings_vdsapiaccid');
        $vdstoken = carbon_get_theme_option('aa_admin_settings_vdsapiauthtoken');
        $vdssupplier = carbon_get_theme_option('aa_admin_settings_vdsapisupplierid');
        $vdsversion = carbon_get_theme_option('aa_admin_settings_vdsapiversion');

        if( !isset($request['context']) && is_object($request['context'])) {

            return rest_response( 'context is required.', 422 );
        }

        if( !$vdsaccid || !$vdstoken || !$vdssupplier || !$vdsversion ) {

            return rest_response( 'You are not allowed to access this page, make sure you entered the all API details in AA Settings', 422 );
        }

        $context = [
            'apiVer' => $vdsversion,
            'auth' => [
                'acctId' => $vdsaccid,
                'key' => $vdstoken
            ],
            'sageNum' => $vdssupplier,
        ];

        $context = array_merge($context, (array) $contextparam);

        $sageRequest = wp_remote_post('https://www.promoplace.com/ws/ws.dll/ConnectAPI', [
            'method' => 'POST',
            'body' => json_encode($context)
        ]);

        $retsage = json_decode(wp_remote_retrieve_body( $sageRequest ));

        return $retsage;

    }


    public function connect( Request $request ) {

        try {

            return $this->sageConnect( $request['context'] );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }
    }



    public function vdsItemsSynchronize( Request $request ) {

        try {

            $id = Hasher::decode( $request['id'] );

            $combo = ProductPrintMethodModel::query();

            $combo->select("*");

            if( isset( $request['product_id'] ) ) {

                $combo->where( 'product_id', Hasher::decode( $request['product_id'] ) );

            }

            $combo->where('id', $id);

            $combo->with(['pricings']);

            $productcombo = $combo->first();

            if(!$productcombo) {
                return false;
            }


            $variations = $this->vdsvariantItems( $request );

            $variantmerge = array();

            if(count($variations['colors'])) {
                $variantmerge = array_merge($variantmerge, json_decode($variations['colors']));
            }

            if(count($variations['stockshapes'])) {
                $variantmerge = array_merge($variantmerge, json_decode($variations['stockshapes']));
            }

            if(count($variations['color_stockshapes'])) {
                $variantmerge = array_merge($variantmerge, json_decode($variations['color_stockshapes']));
            }

            if(!count($variantmerge)) {
                return false;
            }

            $context = array(
                'serviceId' => 108,
                'productId' => $variantmerge[0]->vdsproductid
            );

            $firstSageData = $this->sageConnect( $context );

            if(!isset($firstSageData->products) || !count($firstSageData->products)) {

                return false;
            }

            $firstSageData = $firstSageData->products[0];

            $combopricings = json_decode(json_encode($productcombo['pricings'], true));

            $quantitylists = array_column($combopricings, 'quantity');

            $pricinglists = array_column($combopricings, 'value');

            $sageUpdateParams = array();

            foreach($variantmerge as $vrmerge) {

                $sageUpdateParams[] = array(
                    "productId" => $vrmerge->vdsproductid,
                    "quantities" => $quantitylists,
                    "prices" => $pricinglists,
                    "prCode" => $firstSageData->prCode,
                    "updateType" => 1,
                    "piecesPerUnit" => $firstSageData->piecesPerUnit,
                    "quoteUponRequest" => $firstSageData->quoteUponRequest,
                    "suppId" => $firstSageData->suppId,
                );
            }

            $context = array(
                'serviceId' => 109,
                'products' => $sageUpdateParams
            );

            return $this->sageConnect( $context );

        } catch( \Exception $e ) {
            
            return rest_response( Constants::BAD_REQUEST, 500 );

        }
    }
}