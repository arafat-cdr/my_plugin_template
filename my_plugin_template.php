<?php


/*
 *
 * Plugin Name:       My Plugin Template
 * Plugin URI:        https://simplerscript.com/
 * Description:       Custom plugin Description
 * Version:           1.0
 * Author:            Yeasir Arafat
 * Author URI:        https://simplerscript.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://simplerscript.com/ikomex-shipping
 * Text Domain:       my-custom-plugin
 * Domain Path:       /languages
 *
 */


// Direct Access Not Allowed

if (!defined('ABSPATH')) {
    die;
}

// All Constant Goes Here

define('My_Custom_Plugin', 'my-custom-plugin');
define('My_Custom_Plugin_FILE', __FILE__);
define('My_Custom_Plugin_DIR_PATH', plugin_dir_path(My_Custom_Plugin_FILE));
define('My_Custom_Plugin_BASE_URL', plugin_dir_url(My_Custom_Plugin_FILE));
define('My_Custom_Plugin_LOGO', My_Custom_Plugin_BASE_URL . '/assets/my-logo.png');


// Helpers Goes Here

if( !function_exists('pr') ){
    
    function pr( $data, $dead = false ){
        
        echo '<pre>';
        print_r( $data );
        echo '</pre>';

        if( $die ){
            die($die);
        }
    }

}



// Includes 
require_once( My_Custom_Plugin_DIR_PATH.'classes/Plugin_Db.php' );
require_once( My_Custom_Plugin_DIR_PATH.'classes/class_wp_setting_list_table.php' );

# Datatable Example
require_once( My_Custom_Plugin_DIR_PATH.'classes/My_Datables_Table_list.php' );

require_once( My_Custom_Plugin_DIR_PATH.'classes/My_Plugin_Settings.php' );
require_once( My_Custom_Plugin_DIR_PATH.'classes/class-search-by-id-page.php' );

require_once( My_Custom_Plugin_DIR_PATH.'classes/My_Custom_Plugin.php' );

// For Ajax Loading
require_once My_Custom_Plugin_DIR_PATH . 'classes/class-my-ajax-handler.php';

// For Shortcode Loading
require_once My_Custom_Plugin_DIR_PATH . 'classes/class-my-shortcode.php';

// For Using Custom Page Template
require_once My_Custom_Plugin_DIR_PATH . 'classes/class-my-page-template-handler.php';

// Hook callback

# For supporting Session in My plugin Code
function my_plugin_name_session_start() {
    if(!session_id()) {
        session_start();
    }
}

function plugin_activation(){
    
    $plugin_db = Plugin_Db::get_instance();
    $plugin_db->create_custom_table();

}

// -----------------------------------------------------
// For Ajax Loading
// -----------------------------------------------------

function render_ajax_search_form() {
    return My_Ajax_Handler::get_instance()->render_ajax_search_form();
}


// -----------------------------------------------------
// End For Ajax Loading
// -----------------------------------------------------

// Initialize the plugin
function run_my_custom_plugin() {

    // Example of wp_list_table

    My_Custom_Plugin::get_instance();

    My_Plugin_Settings::get_instance();

    // end example of wp_list_table

    // example of datatale

    My_Datables_Table_list::get_instance();
    Search_By_ID_Page::get_instance();

    // end example of datatable

    // -----------------------------------------------------
    // For Ajax Loading
    // -----------------------------------------------------

    // Initialize AJAX handler class using Singleton pattern
    My_Ajax_Handler::get_instance();
    // Add shortcode for rendering AJAX search form
    // add_shortcode('ajax_search_form', array($this, 'render_ajax_search_form'));

    // Need to call this for Render ajax search form
    // render_ajax_search_form();

    // Need to call this also

    // echo do_shortcode('[ajax_search_form]');

    // -----------------------------------------------------
    // End ajax loading
    // -----------------------------------------------------

    // For ShortCode to use
    // Initialize shortcode handler class using Singleton pattern
    My_Shortcode_Handler::get_instance();
    // End Shortcode use


    // For Using Custom page Template
    // Initialize page template handler class using Singleton pattern
    My_Page_Template_Handler::get_instance();


}


// Hooks

// Here priority 1 is import bcs wp do not
// Has support of session and if you do it
// without init wp can destroy you session
add_action('init', 'my_plugin_name_session_start', 1);
register_activation_hook(__FILE__, 'plugin_activation');
add_action('plugins_loaded', 'run_my_custom_plugin');

// Use any of this below hook for checking wc after order
// add_action('woocommerce_checkout_order_processed', 'wl_test_now');
// add_action('woocommerce_thankyou', 'wl_test_now');

// Shortcodes



