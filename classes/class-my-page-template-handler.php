<?php

class My_Page_Template_Handler {
    private static $instance;

    private function __construct() {
        // Add action to create and add custom page template
        add_filter('theme_page_templates', array($this, 'add_custom_page_template'));
        // Add action to display the custom page template
        add_filter('template_include', array($this, 'display_custom_page_template'));
    }

    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add_custom_page_template() {
        $template_name = 'MY_CUSTOM_PAGE_TEMPLATE';
        $template_file = My_Custom_Plugin_DIR_PATH . 'custom_page_template/full_custom_page_template.php';

        $templates[ $template_file ] = $template_name;
        

        return $templates;

    }

    public function display_custom_page_template($template) {
        if (is_page_template('MY_CUSTOM_PAGE_TEMPLATE')) {
            $template = My_Custom_Plugin_DIR_PATH . 'custom_page_template/full_custom_page_template.php';
        }
        return $template;
    }
}

// Initialize the Singleton instance
// My_Page_Template_Handler::get_instance();
