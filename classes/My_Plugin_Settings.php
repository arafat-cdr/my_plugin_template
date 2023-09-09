<?php

class My_Plugin_Settings {
    private static $instance;

    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_post_save_settings', array($this, 'save_settings'));
    }

    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add_admin_menu() {

        add_submenu_page(
            'my-plugin-menu',
            'My Plugin Setting',
            'Setting',
            'manage_options',
            'my-plugin-setting',
            array($this, 'render_setting_page')
        );

        // For Renaming Current Menu In the Child Menu
        global $submenu;
        $submenu['my-plugin-menu'][0][0] = "Dashboard";


    }

    public function render_setting_page() {

        // Display your settings page content
        $custom_table = new My_Custom_Setting_List_Table();

        $custom_table->prepare_items();

        echo '<div class="wrap">';
        echo '<h1>Custom Table Data</h1>';

        // show search box
        // Search Box
        // $custom_table->search_box('Search', 'my-table-search');

        // Search Box
       // Search Box
        echo '<form method="get">';
        echo '<input type="hidden" name="page" value="my-plugin-setting">';
        $custom_table->search_box('Search', 'my-table-search');
        echo wp_nonce_field('my-custom-table-search', 'my-table-search-nonce', false, false);


        $custom_table->display();

        echo '</form>';
        echo '</div>';

        ?>
        <div class="containers top-20">
            <form class="contact" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">

                <h1>My Plugin Setting</h1>
                <hr>

                <?php wp_nonce_field('my_plugin_settings_nonce', 'my_plugin_settings_nonce'); ?>
                <input type="hidden" name="action" value="save_settings">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name">
                <label for="img">Image:</label>
                <input type="file" name="img" id="img">

                <div class="for_country_code_input_container">
                    <span class="country_code_input_button">+966</span>
                    <input class="country_input_field" name="mobile" value="" type="text"  required autofocus placeholder="i.e 543216788" >
                </div>

                <fieldset>
                    <label> <strong>City</strong> </label>
                    <br>
                  <input name="city" placeholder="City" value="" type="text"  required autofocus>
                </fieldset>

                <?php submit_button('Save Settings'); ?>
            </form>
        </div>
<?php
    }

    public function save_settings() {
        if (
            isset($_POST['my_plugin_settings_nonce'])
            && wp_verify_nonce($_POST['my_plugin_settings_nonce'], 'my_plugin_settings_nonce')
        ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'my_table';

            $name = sanitize_text_field($_POST['name']);
            $city = sanitize_text_field($_POST['city']);

            // Handle Image Upload
            $img_url = $this->handle_image_upload();

            if ($img_url) {

                $data = array(
                    'name' => $name,
                    'city' => $city,
                    'img' => $img_url,
                );

                $wpdb->insert($table_name, $data);

            }
        }
        wp_redirect(admin_url('admin.php?page=my-plugin-setting'));
        exit();
    }

    private function handle_image_upload() {
        if (!empty($_FILES['img']['name'])) {
            $allowed_types = array('image/jpeg', 'image/png');
            $uploaded_file_type = $_FILES['img']['type'];

            // Validate Image Format
            if (!in_array($uploaded_file_type, $allowed_types)) {
                return false; // Invalid image format
            }

            // Upload Image
            $upload_dir = wp_upload_dir();
            $upload_path = $upload_dir['path'] . '/';
            $upload_file = $upload_path . basename($_FILES['img']['name']);

            // Validate and move the uploaded file
            if (move_uploaded_file($_FILES['img']['tmp_name'], $upload_file)) {
                $attachment = array(
                    'guid'           => $upload_dir['url'] . '/' . basename($_FILES['img']['name']),
                    'post_mime_type' => $uploaded_file_type,
                    'post_title'     => preg_replace('/\.[^.]+$/', '', basename($_FILES['img']['name'])),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                $attach_id = wp_insert_attachment($attachment, $upload_file);

                // Generate attachment metadata and update it
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file);
                wp_update_attachment_metadata($attach_id, $attach_data);

                return wp_get_attachment_url($attach_id);
            }
        }
        return false; // Image upload failed
    }

}