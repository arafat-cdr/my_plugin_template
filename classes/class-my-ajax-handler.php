<?php

class My_Ajax_Handler {
    private static $instance;
    private $wpdb;

    private function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;

        // Add AJAX action hooks
        add_action('wp_ajax_my_search_user_name', array($this, 'search_user_name'));
        add_action('wp_ajax_nopriv_my_search_user_name', array($this, 'search_user_name'));

        // Enqueue JavaScript and provide AJAX URL
        add_action('wp_enqueue_scripts', array($this, 'enqueue_ajax_scripts'));
    }

    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function enqueue_ajax_scripts() {
        wp_enqueue_script('my-ajax-script', My_Custom_Plugin_DIR_PATH . 'js/ajax-script.js', array('jquery'), '1.0', true);
        // Pass Ajax Url to script.js
        wp_localize_script('my-ajax-script', 'my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    public function search_user_name() {
        // Verify nonce
        if (
            isset($_POST['my_ajax_nonce'])
            && wp_verify_nonce($_POST['my_ajax_nonce'], 'my_ajax_nonce')
        ) {
            if (isset($_POST['username'])) {
                $username = sanitize_text_field($_POST['username']);

                // Search for user by username
                $user = get_user_by('login', $username);

                if ($user) {
                    echo $user->display_name;
                } else {
                    echo 'User not found';
                }
            }
        } else {
            echo 'Nonce verification failed';
        }
        wp_die();
    }

    public function render_ajax_search_form() {
        ob_start();
        ?>


        <input type="text" id="search-user-input">
        <button id="search-user-btn">Search User</button>
        <div id="user-result"></div>
        <?php wp_nonce_field('my_ajax_nonce', 'my-ajax-nonce'); // Add the nonce field ?>
        <script type="text/javascript">

        // if you want place this code in a script.js file
        jQuery(document).ready(function($) {
            $('#search-user-btn').on('click', function() {
                var username = $('#search-user-input').val();
                var nonce = $('#my-ajax-nonce').val(); // Get the nonce value
                $.ajax({
                    type: 'POST',
                    url: my_ajax_object.ajax_url,
                    data: {
                        action: 'my_search_user_name',
                        username: username
                        my_ajax_nonce: nonce // Pass the nonce in the data
                    },
                    success: function(response) {
                        $('#user-result').html(response);
                    }
                });
            });
        });

        // end place this code in script.js file
        </script>
        <?php
        return ob_get_clean();
    }
}

// In your admin page or front-end template, use the shortcode to render the AJAX search form:
// echo do_shortcode('[ajax_search_form]');