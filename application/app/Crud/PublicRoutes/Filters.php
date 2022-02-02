<?php
/**
 * @package AA_Project
 * 
 * PRODUCT UPDATE
 * 
 */

namespace Api\Crud\PublicRoutes;

use Api\Models\ProductCategoriesModel;

use Api\Models\ProductPrintMethodModel;

use Api\Models\ProductColorsModel;

use Api\Models\ProductStockShapesModel;

use Api\Models\ProductColorAndStockShapeModel;

use Api\Models\ProductLinesModel;

use Api\Constants;

use Api\Collection;

use Api\Hasher;

use Api\Traits\ControllerTraits;

use Illuminate\Database\Capsule\Manager as DB;

class Filters {

    use ControllerTraits;

    public function getFilter( $request ) {

        // if( !isset( $request['category'] ) || empty( $request['category'] ) ) {

        //     return [
        //         'subcategories' => [],
        //         'material' => [],
        //         'sizes' => [],
        //         'thickness' => [],
        //         'colors' => [],
        //         'methods' => [],
        //         'range' => [
        //             'min' => 0,
        //             'max' => 0
        //         ]
        //     ]; 

        // }

        // $subcategories = ProductPrintMethodModel::query()
        // ->leftJoin('products', 'product_print_method.product_id', '=', 'products.id')
        // ->leftJoin('product_categories', 'products.product_category_id', '=', 'product_categories.id')
        // ->leftJoin('product_subcategories', 'products.product_subcategory_id', '=', 'product_subcategories.id')
        // ->select('product_subcategories.sub_name', 'product_subcategories.sub_slug', 'product_categories.cat_slug', 'product_subcategories.priority')
        // ->orderBy('product_subcategories.priority')
        // ->groupby('product_subcategories.sub_name')
        // ->where('product_categories.cat_slug', $request['category'])
        // ->get();


        // $material = ProductPrintMethodModel::query()
        // ->leftJoin('products', 'product_print_method.product_id', '=', 'products.id')
        // ->leftJoin('product_categories', 'products.product_category_id', '=', 'product_categories.id')
        // ->select('products.material_type', 'product_categories.cat_slug')
        // ->where('product_categories.cat_slug', $request['category'])
        // ->whereNotNull('products.material_type')
        // ->groupby('products.material_type')->get();


        // $colors = ProductPrintMethodModel::query()
        // ->leftJoin('products', 'product_print_method.product_id', '=', 'products.id')
        // ->leftJoin('product_colors', 'product_colors.product_print_method_id', '=', 'product_print_method.id')
        // ->leftJoin('product_color_stockshape', 'product_color_stockshape.product_color_id', '=', 'product_colors.id')
        // ->leftJoin('product_categories', 'products.product_category_id', '=', 'product_categories.id')
        // ->select('product_colors.colorhex', 'product_colors.colorname', 'product_colors.slug', 'product_categories.cat_slug', 'product_colors.iscolorimage', 'product_colors.colorimageurl', 'isavailable', 'product_colors.image', 'product_color_stockshape.image as pcsimage')
        // ->where('product_categories.cat_slug', $request['category'])
        // ->where('product_colors.isavailable', 1)
        // ->where(function($q) {
        //     $q->where(DB::raw('JSON_LENGTH(product_colors.image)'), '>', 0)
        //     ->orWhere(DB::raw('JSON_LENGTH(product_color_stockshape.image)'), '>', 0);
        // })
        // ->whereNotNull('product_colors.colorhex')
        // ->whereNotNull('product_colors.colorname')
        // ->groupby('product_colors.slug')
        // ->orderBy('product_colors.priority')->get();


        // $methods = ProductPrintMethodModel::query()
        // ->leftJoin('products', 'product_print_method.product_id', '=', 'products.id')
        // ->leftJoin('product_lines', 'product_print_method.product_line_id', '=', 'product_lines.id')
        // ->leftJoin('print_methods', 'product_lines.print_method_id', '=', 'print_methods.id')
        // ->leftJoin('product_categories', 'products.product_category_id', '=', 'product_categories.id')
        // ->select('print_methods.method_name', 'print_methods.method_name2', 'print_methods.method_slug', 'product_categories.cat_slug')
        // ->where('product_categories.cat_slug', $request['category'])
        // ->groupby('print_methods.id')->get();

        // $price = ProductPrintMethodModel::query()
        // ->leftJoin('products', 'product_print_method.product_id', '=', 'products.id')
        // ->leftJoin('product_categories', 'products.product_category_id', '=', 'product_categories.id')
        // ->leftJoin('pricing_data_value', 'product_print_method.id', '=', 'pricing_data_value.product_print_method_id')
        // ->select('pricing_data_value.value', 'product_categories.cat_slug')
        // ->where('product_categories.cat_slug', $request['category']);


        // $priceMin = $price->min('pricing_data_value.value');

        // $priceMax = $price->max('pricing_data_value.value');

        return [
            // 'subcategories' => $this->getSubcategories($request),
            'material' => $this->getMaterials($request),
            'sizes' => $this->getSizes($request),
            'thickness' => $this->getThickness($request),
            // 'colors' => $this->getColors($request),
            'methods' => $this->getMethods($request),
            'range' => $this->getPrices($request)
        ];

    }


    public function getProductName( $request ) {

        ProductPrintMethodModel::$withoutAppends = true;

        $query = (new ProductPrintMethodModel)->getInstance( $request );
        $query->select(
            'product_print_method.id',
            DB::raw("IF( product_print_method.allow_print_method_prefix = 1, CONCAT(print_methods.method_prefix, products.product_name), products.product_name) as product_method_combination_name" )
        );

        if( isset( $request['combination_name'] ) ) {

            $query->having('product_method_combination_name', $request['combination_name']);

        }

        return $query->first();

    }


    public function getSingleProduct( $request ) {

        try {

            $query = (new ProductPrintMethodModel)->getInstance( $request );
            $query->leftJoin('product_stockshapes', 'product_print_method.id', '=', 'product_stockshapes.product_print_method_id');
            $query->leftJoin('product_color_stockshape', 'product_print_method.id', '=', 'product_color_stockshape.product_print_method_id');
            $query->select(
                'product_print_method.*',
                'print_methods.method_slug',
                'print_methods.is_unprinted',
                'product_categories.cat_slug',
                'product_categories.cat_name',
                'product_subcategories.sub_slug',
                'product_subcategories.sub_name',
                'product_subcategories.sub_name_alt',
                'product_subcategories.sub_description',
                'product_subcategories.catalogs',
                'products.product_slug',
                'products.id as product_fk_id',
                'product_print_method.product_id',
                'product_print_method.product_line_id',
                'product_colors.slug as pcolorslug',
                'product_stockshapes.slug as pstockshapeslug',
                'product_color_stockshape.slug as pcolorstockshapeslug',
                DB::raw("IF( product_print_method.allow_print_method_prefix = 1, CONCAT(print_methods.method_prefix, products.product_name), products.product_name) as product_method_combination_name" ),
                DB::raw("CONCAT('" . home_url() . "', '/product/', product_categories.cat_slug, '/', product_subcategories.sub_slug, '/', products.product_slug, IF(print_methods.is_unprinted != 1,CONCAT('/', print_methods.method_slug, '/'), '/')) as productURL"),
                DB::raw("IF( product_print_method.allow_print_method_prefix = 1, CONCAT(print_methods.method_prefix, products.product_name, ' - " . get_bloginfo('name') . "'), CONCAT(products.product_name, ' - " . get_bloginfo('name') . "')) as theProductComboTitle" )
            );

            $thevariantSlug = null;

            // color
            if( isset( $request['color'] ) && !empty( $request['color'] ) ) {

                $query->where('product_colors.slug', $request['color']);

                $thevariantSlug = $request['color'];

            }

            // shape
            if( isset( $request['shape'] ) && !empty( $request['shape'] ) ) {

                $query->where('product_stockshapes.slug', $request['shape']);
                
                $thevariantSlug = $request['shape'];

            }

            // color + shape
            if( isset( $request['color-shape'] ) && !empty( $request['color-shape'] ) ) {

                $query->where('product_color_stockshape.slug', $request['color-shape']);

                $thevariantSlug = $request['color-shape'];

            }
            
            $query->groupby('product_print_method.id');

            $query->with(['productline' => function($first) {

                $first->with(['couponcode', 'printmethod', 'plinecolors', 'imprinttypes', 'premiumbg', 'stockshapes']);

                $first->with(['pricingData' => function($pricingdata) {
                    
                    $pricingdata->with('chargetypes');

                }]);

            }]);

            $query->with(['product' => function($first) {

                $first->with(['product_colors']);

            }]);

            $query->with(['pricings']);


            if( isset( $request['multiple'] ) ) {

                return Collection::toJson($query->get());

            }

            if( !$query->first() ) {

                return rest_response( Constants::NOT_FOUND, 404 );

            }

            $res = $query->first();

            $dataret = Collection::toJson($res);

            $dataret['specificationIterate'] = $this->specificationIterate($dataret);

            $dataret['variations'] = $res->thevariation(true);

            $dataret['the_selected_variant'] = null;

            $catalogs = json_decode( $dataret['catalogs'] );

            $dataret['catalogs'] = (array) $catalogs;

            if( isset( $dataret['variations']['variations'] ) ) {

                $variantArr = json_decode(json_encode($dataret['variations']['variations']), TRUE);
                $filteredIndex = array_search($thevariantSlug, array_column($variantArr, 'slug'));
                if( is_int($filteredIndex) ) {
                    $dataret['the_selected_variant'] = $variantArr[$filteredIndex];
                }

            }

            return $dataret;

        } catch ( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST, 500 );

        }

    }


    public function productTemplates( $request ) {

        try {

            ProductPrintMethodModel::$withoutAppends = true;

            $query = (new ProductPrintMethodModel)->getInstance( $request->get_params() );
            $query->leftJoin('product_stockshapes', 'product_print_method.id', '=', 'product_stockshapes.product_print_method_id');
            $query->leftJoin('product_color_stockshape', function( $join ) {
                $join->on('product_print_method.id', '=', 'product_color_stockshape.product_print_method_id'); 
                $join->on('product_colors.id', '=', 'product_color_stockshape.product_color_id'); 
                $join->on('product_stockshapes.id', '=', 'product_color_stockshape.product_stockshape_id'); 
            });
            $query->select(
                'product_print_method.id',
                'product_colors.templates as colorstemplates',
                'product_stockshapes.templates as stockshapetemplates',
                'product_color_stockshape.templates as colorstocktemplates',
                'product_colors.id as pcolorid',
                'product_stockshapes.id as pstockshapeid',
                'product_color_stockshape.id as pcolorstockshapeid',
                'product_lines.id as plineid',
                DB::raw("IF( product_print_method.allow_print_method_prefix = 1, CONCAT(print_methods.method_prefix, products.product_name), products.product_name) as product_method_combination_name" )
            );

            if( isset( $request['combination_name'] ) ) {

                $query->having('product_method_combination_name', $request['combination_name']);
    
            }

            if( isset( $request['productlineid'] ) ) {

                $query->where('product_lines.id', $request['productlineid']);

            }

            $query->where(function($qw) {
                $qw->where(DB::raw('JSON_LENGTH(product_colors.templates)'), '>', 0);
                $qw->orWhere(DB::raw('JSON_LENGTH(product_stockshapes.templates)'), '>', 0);
                $qw->orWhere(DB::raw('JSON_LENGTH(product_color_stockshape.templates)'), '>', 0);
            });


            $query->groupBy('product_colors.id', 'product_stockshapes.id', 'product_color_stockshape.id', 'product_print_method.id');

            // paginate
            
            $limit = 50;
            
            if( isset( $request['paginate'] ) ) {

                $limit = (int) $request['paginate'];

            }

            return $query->paginate($limit);


        } catch( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );
            
        }

    }

    public function getProducts( $request ) {

        try {

            $query = (new ProductPrintMethodModel)->getInstance( $request );
            $query->select(
                'product_print_method.id',
                'product_print_method.allow_print_method_prefix',
                'products.product_name',
                'products.product_description',
                'products.product_slug',
                'products.product_size_details',
                'products.product_size',
                'products.product_tickness_details',
                'products.product_thickness',
                'products.material_type',
                'products.priority',
                'product_colors.colorhex',
                'product_colors.colorname',
                'product_categories.cat_name', 
                'product_categories.cat_slug', 
                'product_subcategories.sub_name', 
                'product_subcategories.sub_slug',
                'product_subcategories.sub_description',
                'print_methods.method_name',
                'print_methods.method_slug',
                'print_methods.method_prefix',
                'print_methods.is_unprinted',
                'product_print_method.feature_img',
                'pricing_data_value.value',
                DB::raw("IF( product_print_method.allow_print_method_prefix = 1, CONCAT(print_methods.method_prefix, products.product_name), products.product_name) as product_method_combination_name" ),
                DB::raw("CONCAT('" . home_url() . "', '/product/', product_categories.cat_slug, '/', product_subcategories.sub_slug, '/', products.product_slug, IF(print_methods.is_unprinted != 1,CONCAT('/', print_methods.method_slug, '/'), '/')) as productURL"),
                DB::raw("IF( product_print_method.allow_print_method_prefix = 1, CONCAT(print_methods.method_prefix, products.product_name, ' - " . get_bloginfo('name') . "'), CONCAT(products.product_name, ' - " . get_bloginfo('name') . "')) as theProductComboTitle" )
            );

            if( isset( $request['fields'] ) && !empty( $request['fields'] ) ) {

                $query->addSelect(DB::raw( $request['fields'] ));

            }
            
            $query->groupby('product_print_method.id');

            // color filter
            if( isset( $request['color'] ) && !empty( $request['color'] ) ) {

                $query->whereIn('product_colors.slug', explode(",", $request['color']));

                $query->where('product_colors.isavailable', 1);

            }

            // with pricing
            if( isset( $request['pricings'] ) && !empty( $request['pricings'] ) ) {

                $query->with(['pricings']);

            }

            // paginate
            if( isset( $request['paginate'] ) ) {

                $res = $query->paginate( $request['paginate'] );

                return $res;

            }

            return ['data' => Collection::toJson($query->get())];

        } catch ( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }



    public function searchProducts( $request ) {

        try {

            $query = (new ProductPrintMethodModel)->getInstance( $request );
            $query->select(
                'product_print_method.id',
                'product_print_method.allow_print_method_prefix',
                'products.product_name',
                'products.product_description',
                'products.product_slug',
                'products.product_size_details',
                'products.product_size',
                'products.product_tickness_details',
                'products.product_thickness',
                'products.material_type',
                'products.priority',
                'product_colors.colorhex',
                'product_colors.colorname',
                'product_categories.cat_name', 
                'product_categories.cat_slug', 
                'product_subcategories.sub_name', 
                'product_subcategories.sub_slug',
                'product_subcategories.sub_description',
                'print_methods.method_name',
                'print_methods.method_slug',
                'print_methods.method_prefix',
                'print_methods.is_unprinted',
                'product_print_method.feature_img',
                'pricing_data_value.value',
                DB::raw("IF( product_print_method.allow_print_method_prefix = 1, CONCAT(print_methods.method_prefix, products.product_name), products.product_name) as product_method_combination_name" ),
                DB::raw("CONCAT('" . home_url() . "', '/product/', product_categories.cat_slug, '/', product_subcategories.sub_slug, '/', products.product_slug, IF(print_methods.is_unprinted != 1,CONCAT('/', print_methods.method_slug, '/'), '/')) as productURL"),
                DB::raw("IF( product_print_method.allow_print_method_prefix = 1, CONCAT(print_methods.method_prefix, products.product_name, ' - " . get_bloginfo('name') . "'), CONCAT(products.product_name, ' - " . get_bloginfo('name') . "')) as theProductComboTitle" )
            );
            
            // color filter
            if( isset( $request['color'] ) && !empty( $request['color'] ) ) {

                $query->whereIn('product_colors.slug', explode(",", $request['color']));

                $query->where('product_colors.isavailable', 1);

            }
            
            $query->groupby('product_print_method.id');

            $limit = 10;

            // paginate
            if( isset( $request['paginate'] ) ) {

                $limit = (int) $request['paginate'];

            }

            $res = $query->paginate( $limit );

            return [
                'result' => $res
            ];

        } catch ( \Exception $e ) {

            return rest_response( Constants::BAD_REQUEST . $e->getMessage(), 500 );

        }

    }



    public function getProductLines( $request ) {

        $query = (new ProductLinesModel)->getInstance($request);


        if( isset( $request['withcompliance'] ) && !empty( $request['withcompliance'] ) ) {

            $query->where(DB::raw('JSON_LENGTH(product_lines.compliances)'), '>', 0);

        }

        // paginate
        if( isset( $request['paginate'] ) ) {

            $res = $query->paginate( (int) $request['paginate'] );

            return $res;

        }

        return [
            'data' => Collection::toJson($query->get())
        ];

    }


    public function getSizes( $request ) {

            $sizes = (new ProductPrintMethodModel)->getInstance( $request );
            $sizes->select(
                'products.product_size', 
                'products.product_size_details',
                'product_categories.cat_slug', 
                'print_methods.method_slug', 
                'product_subcategories.sub_slug' 
            );

            $sizes->whereNotNull('products.product_size');

            $sizes->whereNotNull('products.product_size_details');

            $sizes->where('products.product_size', '!=', 0);
            
            $sizes->groupby('products.product_size_details');

            $sizes->orderBy('products.product_size');

            return Collection::toJson($sizes->get());

    }


    public function getThickness( $request ) {

        $thickness = (new ProductPrintMethodModel)->getInstance( $request );
        $thickness->select(
            'products.product_thickness', 
            'products.product_tickness_details',
            DB::raw("CONCAT(products.product_thickness, products.product_tickness_details) as productfinalthickness"),
            'product_categories.cat_slug', 
            'print_methods.method_slug', 
            'product_subcategories.sub_slug' 
        );

        $thickness->whereNotNull('products.product_thickness');

        $thickness->whereNotNull('products.product_tickness_details');
        
        $thickness->groupby('products.product_tickness_details');

        return Collection::toJson($thickness->get());

    }

    public function getSubcategories( $request ) {

        $subcategories = (new ProductPrintMethodModel)->getInstance( $request );
        $subcategories->select('product_subcategories.sub_name', 'product_subcategories.sub_slug', 'product_categories.cat_slug', 'product_subcategories.priority')
        ->orderBy('product_subcategories.priority')
        ->groupby('product_subcategories.sub_name');

        return Collection::toJson($subcategories->get());

    }


    public function getMaterials( $request ) {

        $material = (new ProductPrintMethodModel)->getInstance( $request );
        $material->select('products.material_type', 'product_categories.cat_slug')
        ->whereNotNull('products.material_type')
        ->groupby('products.material_type');

        return Collection::toJson($material->get());

    }

    public function getColors( $request ) {

        $colors = (new ProductPrintMethodModel)->getInstance( $request );
        $colors->leftJoin('product_color_stockshape', 'product_print_method.id', '=', 'product_color_stockshape.product_print_method_id');
        $colors->select('product_colors.colorhex', 'product_colors.colorname', 'product_colors.slug', 'product_categories.cat_slug', 'product_colors.iscolorimage', 'product_colors.colorimageurl', 'product_colors.isavailable', 'product_colors.image', 'product_color_stockshape.image as pcsimage')
        ->where('product_colors.isavailable', 1)
        ->where(function($q) {
            $q->where(DB::raw('JSON_LENGTH(product_colors.image)'), '>', 0)
            ->orWhere(DB::raw('JSON_LENGTH(product_color_stockshape.image)'), '>', 0);
        })
        ->whereNotNull('product_colors.colorhex')
        ->whereNotNull('product_colors.colorname')
        ->groupby('product_colors.slug')
        ->orderBy('product_colors.priority');

        return Collection::toJson($colors->get());

    }


    public function getMethods( $request ) {

        $methods = (new ProductPrintMethodModel)->getInstance( $request );
        $methods->select('print_methods.method_name', 'print_methods.method_name2', 'print_methods.method_slug', 'product_categories.cat_slug')
        ->groupby('print_methods.id');

        return Collection::toJson($methods->get());

    }

    public function getPrices( $request ) {

        $price = (new ProductPrintMethodModel)->getInstance( $request );
        $price->select('pricing_data_value.value', 'product_categories.cat_slug');

        $priceMin = $price->min('pricing_data_value.value');

        $priceMax = $price->max('pricing_data_value.value');

        return [
            'min' => $priceMin,
            'max' => $priceMax,
            'formatted_min' => aa_formatted_money( $priceMin ),
            'formatted_max' => aa_formatted_money( $priceMax )
        ];

    }

    public function variations( $request ) {

        if( !isset( $request['id'] ) || !isset( $request['key'] ) ) {
            return rest_response( Constants::NOT_FOUND, 404 );
        }

        $id = Hasher::decode($request['id']);
        if( !$id ) {
            return rest_response( Constants::NOT_FOUND, 404 );
        }

        if( $request['key'] == Constants::COLOR_STOCKSHAPE_VARIANT_KEY ) {
            $ret = ProductColorAndStockShapeModel::where('id', $id)
            ->where(DB::raw('JSON_LENGTH(image)'), '>', 0)
            ->orderBy('priority')
            ->with(['theshape', 'thecolor']);
            return $ret->first();
        }

        if( $request['key'] == Constants::COLOR_VARIANT_KEY ) {
            $ret = ProductColorsModel::where('id', $id)
            ->where(DB::raw('JSON_LENGTH(image)'), '>', 0)
            ->where('isavailable', 1)
            ->orderBy('priority');
            return $ret->first();
        }

        if( $request['key'] == Constants::STOCK_SHAPE_VARIANT_KEY ) {
            $ret = ProductStockShapesModel::where('id', $id)
            ->where(DB::raw('JSON_LENGTH(image)'), '>', 0)
            ->orderBy('priority');
            return $ret->first();
        }

        if( $request['key'] == Constants::NACOLOR_VARIANT_KEY ) {
            $ret = ProductColorsModel::where('id', $id)
            ->where(DB::raw('JSON_LENGTH(image)'), '>', 0)
            ->where('isavailable', 0)
            ->orderBy('priority');
            return $ret->first();
        }

        return rest_response( 'Variation Query is Invalid', 422 );

    }

}