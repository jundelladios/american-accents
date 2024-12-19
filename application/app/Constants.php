<?php
/**
 * 
 * REST API CONSTANTS
 * 
 */

 namespace Api;

 class Constants {

    // Hashing Configs | more info https://mainfacts.com/cipher-encrypt-decrypt-generator/md5salted

    CONST SALT = "d91a0533d6a70897adbcb821bb1cc343:48e95c45c8217961bf6cd7696d80d238";
    CONST LENGTH = 10;

    // Response ERRORS
    CONST BAD_REQUEST = "Bad Request, You are not allowed to execute this request.";
    CONST NOT_FOUND = "The request that you are looking for was not found.";

    // SLUG ERROR RESPONSES
    CONST UPDATE_SLUG_ERROR = "The slug you entered was already used.";
    CONST STORE_SLUG_ERROR = "Slug already exists, please create another one.";

    // Coupon Exists
    CONST COUPON_EXISTS = "Coupon Code already exists.";

    // Export Import
    CONST EXPORT_FAILED = "Export failed with message: ";
    CONST IMPORT_FAILED = "Import failed with message: ";
    CONST EXPORT_SUCCESS = "Success";
    CONST IMPORT_SUCCESS = "Success";

    // COLORS
    CONST COLOR_TITLE_EXISTS = "Color title already exists.";

    // imprint type
    CONST IMPRINT_TYPE_EXIST = "Imprint title already exists.";


    CONST COLOR_STOCKSHAPE_VARIANT_KEY = "color-stockshape";
    CONST COLOR_VARIANT_KEY = "color";
    CONST STOCK_SHAPE_VARIANT_KEY = "stockshape";
    CONST NACOLOR_VARIANT_KEY = "na-color";

 }