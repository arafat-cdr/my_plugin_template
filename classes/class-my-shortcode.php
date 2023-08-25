<?php

class My_Shortcode_Handler {
    private static $instance;

    private function __construct() {
        // Add shortcode action hook
        add_shortcode('my_hello_shortcode', array($this, 'render_hello_shortcode'));
    }

    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function render_hello_shortcode($atts) {
        $atts = shortcode_atts(array(
            'message' => 'Hello, World!'
        ), $atts);

        $current_user = wp_get_current_user();
        $username = $current_user->user_login;

        return '<p>' . esc_html($atts['message']) . ' My username is ' . esc_html($username) . '</p>';
    }
}

// Initialize the Singleton instance
// My_Shortcode_Handler::get_instance();

// To use the shortcode, simply add 
// [my_hello_shortcode] to your posts or pages,
// and it will display the desired message.

