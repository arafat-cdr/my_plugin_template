<?php

if (!defined('ABSPATH')) {
    die;
}

// Helpers Goes Here

if( !function_exists('pr') ){
    
    function pr( $data, $die = false ){
        
        echo '<pre>';
        print_r( $data );
        echo '</pre>';

        if( $die ){
            die($die);
        }
    }

}

if( !function_exists('get_my_custom_lang_str') ){

    function get_my_custom_lang_str( $key ){

        global $my_custom_lang_arr;

        $lang_get = My_Custom_Plugin_Lang; // eng or pol

        if( isset( $my_custom_lang_arr[$key][$lang_get] ) ){
            return ucwords( $my_custom_lang_arr[$key][$lang_get] );

        }else if( isset( $my_custom_lang_arr[strtolower($key)][$lang_get] ) ){
            # check lowercase key if not found
            return ucwords( $my_custom_lang_arr[strtolower($key)][$lang_get] );
        }

        return "{$key}";

    }

}