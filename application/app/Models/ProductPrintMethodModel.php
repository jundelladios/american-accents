<?php

/**
 * PRODUCT PRINTING METHODS COMBINATION MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Hasher;

use Api\Models\ProductsModel;

use Api\Models\ProductLinesModel;

use Api\Models\PricingDataValueModel;

use Api\Models\ProductColorsModel;

use Api\Models\ProductStockShapesModel;

use Api\Models\ProductColorAndStockShapeModel;

use Api\Models\SpecificationTypesModel;

use Illuminate\Database\Capsule\Manager as DB;

use Api\Constants;

class ProductPrintMethodModel extends Model {

    protected $table = "product_print_method";

    public static $withoutAppends = false;

    protected $fillable = [
        'product_id', 'product_line_id', 'description', 'features_options', 'disclaimer', 'downloads', 'templates', 'feature_img', 'showcase_img', 'images', 'seo_content', 'min_desc', 'features_options2',
        'package_count_min',
        'package_count_max',
        'imprint_width',
        'imprint_height',
        'imprint_bleed_wrap_width',
        'imprint_bleed_wrap_height',
        'package_count_as',
        'allow_print_method_prefix',
        'imprint_as',
        'imprint_bleed_as',
        'shape',
        'specs_json',
        'specification_id',
        'specification_output_id',
        'keywords'
    ];

    protected $hidden = ['active', 'product_id', 'product_line_id', 'specification_id', 'specification_output_id'];

    protected $appends = ['hid', 'price', 'productcomboimage', 
    //'ideagallery', 
    'all_quantities', 'product_line_helpers', 'specification', 'spechandler', 'spechandleroutput',
    'variations'
    ];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function getProductLineHelpersAttribute() {
        if( self::$withoutAppends ) { return null; }
        return [
            "piece" => "add per piece",
            "color" => "per color",
            "side" => "per side",
            "thousand" => "per thousand",
            "item" => "per item",
            "panel" => "per panel",
            "not_applicable" => "n/a",
            "complianceNote" => carbon_get_theme_option("aa_admin_settings_compliance"),
            "premiumBackgroundCompliance" => carbon_get_theme_option("aa_admin_settings_premiumbg")
        ];
    }

    public function getAllQuantitiesAttribute() {

        if( self::$withoutAppends ) { return null; }

        $query = ProductPrintMethodModel::query()
        ->leftJoin('pricing_data_value', 'pricing_data_value.product_print_method_id', '=', 'product_print_method.id')
        ->leftJoin('pricing_data', 'pricing_data.product_line_id', '=', 'product_print_method.product_line_id')
        ->select('pricing_data_value.quantity');

        $query->where('product_print_method.id', $this->id);
        $query->groupby('pricing_data_value.quantity');
        $query->orderBy('pricing_data_value.quantity');

        return $query->pluck('quantity');

    }

    public function getProductcomboimageAttribute() {

        $img = json_decode( $this->feature_img );

        if( $img ) { return $img; }

        return null;

    }

    public function scopeActive( $query ) {

        return $query->where('active', 1);

    }

    public function scopeInactive( $query ) {

        return $query->where('active', 0);
        
    }

    public function product() {

        return $this->hasOne(ProductsModel::class, 'id', 'product_id');

    }

    public function productline() {

        return $this->hasOne(ProductLinesModel::class, 'id', 'product_line_id');

    }

    public function pricings() {

        return $this->hasMany(PricingDataValueModel::class, 'product_print_method_id', 'id')
        ->orderBy('quantity');

    }

    public function getPriceAttribute() {

        if( self::$withoutAppends ) { return null; }

        $min = PricingDataValueModel::where( 'product_print_method_id', $this->id )
        ->whereNotNull( 'pricing_data_value.value' )
        ->whereNotNull( 'pricing_data_value.product_print_method_id' )
        ->min( 'value' );

        $max = PricingDataValueModel::where( 'product_print_method_id', $this->id )
        ->whereNotNull( 'pricing_data_value.value' )
        ->whereNotNull( 'pricing_data_value.product_print_method_id' )
        ->max( 'value' );

        $formatted_min = aa_formatted_money( PricingDataValueModel::where( 'product_print_method_id', $this->id )->min( 'value' ) );

        $formatted_max = aa_formatted_money( PricingDataValueModel::where( 'product_print_method_id', $this->id )->max( 'value' ) );

        return [
            'min' => $min,
            'max' => $max,
            'formatted_min' => $formatted_min,
            'formatted_max' => $formatted_max
        ];

    }

    public function getSpecificationAttribute() {

        return aa_json_array( $this->specs_json );
        
    }

    public function getSpechandlerAttribute() {

        if( $this->specification_id ) {

            return SpecificationTypesModel::where('id', $this->specification_id)->first();

        }

        return null;

    }


    public function getSpechandleroutputAttribute() {

        if( $this->specification_output_id ) {

            return SpecificationTypesModel::where('id', $this->specification_output_id)->where('isspec',1)->first();

        }

        return null;

    }

    public function getVariationsAttribute() {

        return $this->thevariation();

    }


    public function thevariation( $all = false ) {

        if( self::$withoutAppends ) { return null; }

        // color + stockshape
        $colorstockshape = ProductColorAndStockShapeModel::query();

        $colorstockshape->join('product_stockshapes', 'product_stockshapes.id', '=', 'product_color_stockshape.product_stockshape_id');

        $colorstockshape->select(
            'product_color_stockshape.image', 
            'product_color_stockshape.id', 
            'product_color_stockshape.priority', 
            'product_color_stockshape.created_at', 
            'product_color_stockshape.updated_at', 
            'product_color_stockshape.slug', 
            'product_color_stockshape.product_print_method_id', 
            'product_color_stockshape.product_stockshape_id', 
            'product_color_stockshape.product_color_id',
            'product_color_stockshape.vdsid'
        )
        ->where('product_color_stockshape.product_print_method_id', $this->id)
        ->where(DB::raw('JSON_LENGTH(product_color_stockshape.image)'), '>', 0);

        if( !$all ) {
            $firststockshape = ProductStockShapesModel::select('id')->where('product_print_method_id', $this->id)->first();
            $colorstockshape->where('product_stockshape_id', $firststockshape ? $firststockshape->id : 0)
            ->groupBy('product_color_id');
        }

        $colorstockshape->orderBy('product_color_stockshape.priority')
        ->orderBy('product_stockshapes.code')
        ->with(['theshape', 'thecolor']);
        if( $colorstockshape->count() ) {
            return [
                'key' => Constants::COLOR_STOCKSHAPE_VARIANT_KEY,
                'variations' => $colorstockshape->get(),
                'router_slug' => 'color-and-shape'
            ];
        }

        $colors = ProductColorsModel::select('id', 'colorhex', 'colorname', 'iscolorimage', 'colorimageurl', 'pantone', 'product_print_method_id', 'image', 'slug', 'isavailable', 'vdsid')->where('product_print_method_id', $this->id);

        // available colors
        $availablecolor = $colors;
        $availablecolor->where(DB::raw('JSON_LENGTH(image)'), '>', 0)
        ->where('isavailable', 1)
        ->orderBy('priority');
        if( $availablecolor->count() ) {
            return [
                'key' => Constants::COLOR_VARIANT_KEY,
                'variations' => $availablecolor->get(),
                'router_slug' => 'color'
            ];
        }

        // stock shape
        $stockshape = ProductStockShapesModel::select('id', 'stockname', 'code', 'product_print_method_id', 'image', 'slug', 'vdsid')->where('product_print_method_id', $this->id)
        ->where(DB::raw('JSON_LENGTH(image)'), '>', 0)
        ->orderBy('code');
        if( $stockshape->count() ) {
            return [
                'key' => Constants::STOCK_SHAPE_VARIANT_KEY,
                'variations' => $stockshape->get(),
                'router_slug' => 'shape'
            ];
        }

        // default color which is not available
        $nonavailablecolor = $colors;
        $nonavailablecolor->where(DB::raw('JSON_LENGTH(image)'), '>', 0)
        ->where('isavailable', 0)
        ->orderBy('priority');
        if( $nonavailablecolor->count() ) {
            return [
                'key' => Constants::NACOLOR_VARIANT_KEY,
                'variations' => $nonavailablecolor->get(),
                'router_slug' => null
            ];
        }

        return null;

    }


    public function getInstance( array $request = [] ) {

        $query = ProductPrintMethodModel::query()
        ->leftJoin('products', 'product_print_method.product_id', '=', 'products.id')
        ->leftJoin('product_categories', 'products.product_category_id', '=', 'product_categories.id')
        ->leftJoin('product_subcategories', 'products.product_subcategory_id', '=', 'product_subcategories.id')
        ->leftJoin('product_lines', 'product_print_method.product_line_id', '=', 'product_lines.id')
        ->leftJoin('print_methods', 'product_lines.print_method_id', '=', 'print_methods.id')
        ->leftJoin('pricing_data_value', 'product_print_method.id', '=', 'pricing_data_value.product_print_method_id')
        ->leftJoin('product_colors', 'product_print_method.id', '=', 'product_colors.product_print_method_id');

        // search query
        if( isset( $request['q'] ) && !empty( $request['q'] ) ) {
            $qrstr = $request['q'];
            $query->where( function($qs) use ($qrstr) {
                $qs->where(DB::raw("IF( product_print_method.allow_print_method_prefix = 1, CONCAT(print_methods.method_prefix, products.product_name), products.product_name)"), 'LIKE', "%$qrstr%")
                ->orWhere('products.product_description', 'LIKE', "%$qrstr%")
                ->orWhere('products.product_size_details', 'LIKE', "%$qrstr%")
                ->orWhere('products.product_tickness_details', 'LIKE', "%$qrstr%")
                ->orWhere('products.material_type', 'LIKE', "%$qrstr%")
                ->orWhere('product_colors.colorname', 'LIKE', "%$qrstr%")
                ->orWhere('product_categories.cat_name', 'LIKE', "%$qrstr%")
                ->orWhere('product_subcategories.sub_name', 'LIKE', "%$qrstr%")
                ->orWhere('print_methods.method_name', 'LIKE', "%$qrstr%")
                ->orWhere('pricing_data_value.value', 'LIKE', "%$qrstr%")
                ->orWhere('product_print_method.keywords', 'LIKE', "%$qrstr%")
                ;
            });
        }

        // price range filter
        if( (isset($request['priceMin']) && isset($request['priceMax'])) && (!empty($request['priceMin']) && !empty($request['priceMax'])) ) {

            $query->whereBetween('pricing_data_value.value', [ $request['priceMin'], $request['priceMax'] ]);

        }

        // category filter
        if( isset( $request['category'] ) && !empty( $request['category'] ) ) {

            $query->where('product_categories.cat_slug', $request['category']);

        }

        // subcategory filter
        if( isset( $request['subcategory'] ) && !empty( $request['subcategory'] ) ) {
            
            $query->whereIn('product_subcategories.sub_slug', explode(",", $request['subcategory']));

        }

        // print method filter
        if( (isset( $request['printmethod'] ) && !empty( $request['printmethod'] )) ) {
            
            $query->whereIn('print_methods.method_slug', explode(",", $request['printmethod']));

        }

        // print method filter alternative
        if( (isset( $request['method'] ) && !empty( $request['method'] )) ) {
            
            $query->whereIn('print_methods.method_slug', explode(",", $request['method']));

        }

        // size filter
        if( isset( $request['size'] ) && !empty( $request['size'] ) ) {

            $query->whereIn('products.product_size_details', explode(",", $request['size']));

        }

        // thickness filter
        if( isset( $request['thickness'] ) && !empty( $request['thickness'] ) ) {

            $query->whereIn('products.product_tickness_details', explode(",", $request['thickness']));

        }

        // material filter
        if( isset( $request['material'] ) && !empty( $request['material'] ) ) {

            $query->whereIn('products.material_type', explode(",", $request['material']));

        }

        // unprinted filter
        if( isset( $request['is_unprinted'] ) && !empty( $request['is_unprinted'] ) ) {

            $query->where('print_methods.is_unprinted', $request['is_unprinted']);

        }

        // product filter
        if( isset( $request['product'] ) && !empty( $request['product'] ) ) {
                
            $query->where('products.product_slug', $request['product']);

        }

        if( isset( $request['id'] ) ) {

            $query->where('product_print_method.id', Hasher::decode( $request['id'] ));

        }

        if( isset($request['orderBy']) && !empty($request['orderBy'])) {

            $query->orderByRaw($request['orderBy']);

        } else {

            $query->orderByRaw( 'product_print_method.priority asc' );

        }

        $query->whereNotNull('pricing_data_value.value');

        $query->where('product_print_method.active', 1);

        $query->where('products.active', 1);

        $query->where('product_categories.active', 1);

        $query->where('product_subcategories.active', 1);

        $query->where('product_lines.active', 1);

        $query->where('print_methods.active', 1);

        return $query;

    }



    public function deleteWithRelations() {

        ProductPrintMethodModel::where('id', $this->id)->delete();

        PricingDataValueModel::where('product_print_method_id', $this->id)->delete();

        ProductColorsModel::where('product_print_method_id', $this->id)->delete();

        ProductStockShapesModel::where('product_print_method_id', $this->id)->delete();

        ProductColorAndStockShapeModel::where('product_print_method_id', $this->id)->delete();

        return true;

    }

}