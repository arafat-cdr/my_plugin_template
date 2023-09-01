<?php

class My_Custom_Plugin {
    private static $instance;

    private function __construct() {
        $this->setup_actions();
    }

    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function setup_actions() {

        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));

        add_action('admin_menu', array($this, 'add_admin_menu'));

    }

    public function enqueue_admin_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_style('admin-style', My_Custom_Plugin_BASE_URL . 'assets/css/admin/admin-style.css');
        wp_enqueue_script('admin-script', My_Custom_Plugin_BASE_URL . 'assets/js/admin/admin-script.js', array('jquery'), null, true);
    }

    public function enqueue_frontend_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_style('frontend-style', My_Custom_Plugin_BASE_URL . 'assets/css/frontend-style.css');
        wp_enqueue_script('frontend-script', My_Custom_Plugin_BASE_URL . 'assets/js/frontend-script.js', array('jquery'), null, true);
    }

    public function add_admin_menu() {
        
        add_menu_page(
            'My Plugin Menu',
            'My Plugin Menu',
            'manage_options',
            'my-plugin-menu',
            array($this, 'render_admin_page')
        );

    }

    public function render_admin_page() {
        // Main menu page content
        _e( '<h1>Hello admin in this Dashboard</h1>', My_Custom_Plugin );

        // global $submenu;
        // pr( $submenu );
    }

}
