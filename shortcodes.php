<?php
/**
 * SHORTCODES
 *
 * @package AA_Project
 */


foreach (scandir(plugin_dir_path( __FILE__ ) . '/shortcodes') as $filename) {
    $path = plugin_dir_path( __FILE__ ) . 'shortcodes/' . $filename;
    if (is_file($path)) {
        require_once $path;
    }
}