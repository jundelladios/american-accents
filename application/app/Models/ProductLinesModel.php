<?php

/**
 * PRODUCT LINES MODEL
 * 
 * @package AA_Project
 * 
 */

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Hasher;

use Api\Models\PrintMethodsModel;

use Api\Models\CouponsModel;

use Api\Models\ProductsModel;

use Api\Models\ProductPrintMethodModel;

use Api\Models\PricingDataModel;

use Api\Models\ProductLineColorsModel;

use Api\Models\ProductLinePremiumBgModel;

use Api\Models\ProductLineStockShapeModel;

use Api\Models\ImprintTypeProductLineModel;

use Illuminate\Database\Capsule\Manager as DB;

class ProductLinesModel extends Model {

    protected $table = "product_lines";

    protected $fillable = [
        'product_subcategory_id', 'print_method_id', 'image', 'features', 'priority',
        'coupon_code_id', 'features_pivot', 'second_side', 'wrap', 'bleed', 'multicolor', 'process',
        'white_ink', 'hotstamp', 'per_thousand', 'setup_charge', 'colors', 'compliances', 'pnotes', 'seo_content', 'banner_img', 'pnotes2', 'premium_backgrounds',
        'price_tagline', 'per_item', 'show_currency'
    ];

    protected $hidden = ['print_method_id', 'product_subcategory_id', 'coupon_code_id'];

    protected $appends = ['hid', 'pricerange', 'formatted_setup_charge', 'compliancesdata', 'pnotesdata', 'hasproductcombo'];

    public function getHidAttribute() {
        // Hasher for the ID
        return Hasher::encode($this->id);
    }

    public function getPnotesdataAttribute() {

        return aa_json_array( $this->pnotes );

    }

    public function scopeActive( $query ) {

        return $query->where('active', 1);

    }

    public function scopeInactive( $query ) {

        return $query->where('active', 0);
        
    }

    public function printmethod() {

        return $this->hasOne(PrintMethodsModel::class, 'id', 'print_method_id');

    }

    public function pricingData() {

        return $this->hasMany(PricingDataModel::class, 'product_line_id', 'id')->orderBy('priority');

    }

    public function couponcode() {
        
        return $this->hasOne(CouponsModel::class, 'id', 'coupon_code_id');

    }

    public function getPricerangeAttribute() {

        $query = ProductLinesModel::query()
        ->leftJoin('product_print_method', 'product_lines.id', '=', 'product_print_method.product_line_id')
        ->leftJoin('products', 'product_print_method.product_id', '=', 'products.id')
        ->leftJoin('pricing_data_value', 'product_print_method.id', '=', 'pricing_data_value.product_print_method_id')
        ->select(
            'product_lines.id',
            'pricing_data_value.value',
            'pricing_data_value.decimal_value',
            'pricing_data_value.show_currency'
        )
        ->where('product_lines.id', $this->id)
        ->where('product_print_method.active', 1)
        ->where('products.active', 1)
        ->whereNotNull('pricing_data_value.product_print_method_id')
        ->whereNotNull('pricing_data_value.value')
        ->groupBy( 'pricing_data_value.value' );


        $max = $query->orderBy('pricing_data_value.value', 'DESC')->first();

        $query->getQuery()->orders = null;

        $min = $query->orderBy('pricing_data_value.value', 'ASC')->first();

        return aa_range_formatter($min, $max);
        
    }

    public function plinecolors() {
        
        return $this->hasMany(ProductLineColorsModel::class, 'product_line_id', 'id')->orderBy('priority')->with(['colorcollections']);

    }

    public function premiumbg() {
        
        return $this->hasMany(ProductLinePremiumBgModel::class, 'product_line_id', 'id')->orderBy('priority')->with(['premiumbg']);

    }

    public function stockshapes() {
        
        return $this->hasMany(ProductLineStockShapeModel::class, 'product_line_id', 'id')->orderBy('priority')->with(['stockshapes']);

    }

    public function imprinttypes() {

        return $this->hasMany(ImprintTypeProductLineModel::class, 'productline_id', 'id')->orderBy('priority')->with(['imprinttype']);

    }

    public function getFormattedSetupChargeAttribute() {

        $withcurrency = (int) $this->attributes['show_currency'];

        return aa_formatted_money(number_format($this->setup_charge  ?? 0), !$withcurrency);

    }

    public function getCompliancesdataAttribute() {

        return aa_json_array( $this->compliances );

    }


    public function getHasproductcomboAttribute() {

        return ProductPrintMethodModel::where('product_line_id', $this->id)->count();

    }


    public function deleteWithRelations() {

        ProductLinesModel::where('id', $this->id)->delete();

        ImprintTypeProductLineModel::where('productline_id', $this->id)->delete();

        ProductLineStockShapeModel::where('product_line_id', $this->id)->delete();

        ProductLinePremiumBgModel::where('product_line_id', $this->id)->delete();

        ProductLineColorsModel::where('product_line_id', $this->id)->delete();

        $qpricingdelete = "DELETE pricing_data, pricing_data_value FROM pricing_data LEFT JOIN pricing_data_value ON pricing_data.id = pricing_data_value.pricing_data_id WHERE pricing_data.product_line_id=?";

        DB::delete($qpricingdelete,array($this->id));

        return true;

    }

    public function getInstance( $request ) {

        $query = ProductLinesModel::query()
        ->leftJoin('product_subcategories', 'product_lines.product_subcategory_id', '=', 'product_subcategories.id')
        ->leftJoin('product_categories', 'product_subcategories.product_category_id', '=', 'product_categories.id')
        ->leftJoin('print_methods', 'product_lines.print_method_id', '=', 'print_methods.id')
        ->leftJoin('product_print_method', 'product_print_method.product_line_id', '=', 'product_lines.id')
        ->leftJoin('products', 'product_print_method.product_id', '=', 'products.id')
        ->leftJoin('pricing_data_value', 'pricing_data_value.product_print_method_id', '=', 'product_print_method.id')
        ->select(
            'product_lines.*',
            'product_categories.id as categoryID',
            'product_subcategories.id as subcategoryID',
            'product_categories.cat_name',
            'product_categories.cat_slug',
            'product_subcategories.sub_name',
            'product_subcategories.sub_slug',
            'product_subcategories.sub_name_alt',
            'print_methods.method_name',
            'print_methods.method_name2',
            'print_methods.method_slug',
            'print_methods.method_prefix',
            'print_methods.method_desc',
            'print_methods.method_desc_short',
            'print_methods.method_hex',
            'print_methods.keyfeatures',
            'print_methods.is_unprinted',
            'pricing_data_value.value',
            DB::raw("CONCAT(print_methods.method_name, IF(print_methods.method_name2 IS NOT NULL, CONCAT(' ', print_methods.method_name2), '')) as pline_method_fullname"),
            DB::raw("CONCAT(product_subcategories.sub_name, ' - ', print_methods.method_name, ' ', print_methods.method_name2) as plinecombination")
        );

        // additional fields to be retrieved
        if( isset( $request['fields'] ) && !empty( $request['fields'] ) ) {

            $query->addSelect(DB::raw( $request['fields'] ));

        }

        // category filter
        if( isset( $request['category'] ) && !empty( $request['category'] ) ) {

            $query->where('product_categories.cat_slug', $request['category']);

        }


        if( isset( $request['categoryname'] ) && !empty( $request['categoryname'] ) ) {
            
            $query->where('product_categories.cat_name', $request['categoryname']);

        }

        // subcategory filter
        if( isset( $request['subcategory'] ) && !empty( $request['subcategory'] ) ) {
            
            $query->where('product_subcategories.sub_slug', $request['subcategory']);

        }

        if( isset( $request['subcategoryname'] ) && !empty( $request['subcategoryname'] ) ) {
            
            $query->where('product_subcategories.sub_name', $request['subcategoryname']);

        }

        if( isset( $request['subcategory_status'] ) && !empty( $request['subcategory_status'] ) ) {

            $query->where('product_subcategories.active', (int) $request['subcategory_status']);

        }

        // print method filter
        if( isset( $request['method'] ) && !empty( $request['method'] ) ) {

            $query->where('print_methods.method_slug', $request['method']);

        }

        if( isset( $request['methodname'] ) && !empty( $request['methodname'] ) ) {
            
            $query->having('pline_method_fullname', $request['methodname']);

        }

        if( isset( $request['printtype'] ) ) {

            $query->where('is_unprinted', (int) $request['printtype']);

        }

        $query->whereNotNull('pricing_data_value.value');

        $query->where('product_lines.active', 1);

        $query->where('product_print_method.active', 1);

        $query->where('products.active', 1);

        if( isset( $request['orderby'] ) && !empty( $request['orderby'] ) ) {

            $query->orderByRaw( $request['orderby'] );

        } else {

            $query->orderByRaw( 'product_lines.priority asc' );

        }

        $query->groupby('product_lines.id');

        if( isset( $request['couponcode'] ) ) {

            $query->with(['couponcode']);

        }


        return $query;

    }

}