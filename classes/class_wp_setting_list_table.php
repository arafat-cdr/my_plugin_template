<?php

require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

/**
 *
 * at class My_Custom_Setting_List_Table
 * 
 * You Need to Do all the things By your Self
 * 
 * I think using Jquery Data-Table is More Good Than That
 *
 */


class My_Custom_Setting_List_Table extends WP_List_Table
{
    function __construct()
    {
        parent::__construct(array(
            'singular' => 'custom_data',
            'plural' => 'custom_data',
            'ajax' => false
        ));
    }

    public function set_flash_msg(){
        $_SESSION['my_custom_plugin_notice'] = 1;
    }

    public function get_flash_msg(){
        
        if( isset( $_SESSION['my_custom_plugin_notice'] ) && $_SESSION['my_custom_plugin_notice'] == 1 ){

            echo '<div class="notice notice-success is-dismissible">
                    <p>'.__( 'Item deleted successfully', My_Custom_Plugin ).'</p>
                </div>';

            // invalidate it
            $_SESSION['my_custom_plugin_notice'] = 0;
        }
    }

    public function handle_delete_item(
        $delete_key = 'id',
        $nonce_str = 'delete_item_',
        $action_name = 'delete_item',
        $nonce_key = 'nonce' 
    ) {
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'my_table';

        if (isset($_GET['action']) && $_GET['action'] === $action_name && isset($_GET[$delete_key]) && isset($_GET[$nonce_key])) 
        {

            // Check if the nonce is valid
            if ( wp_verify_nonce( $_GET[$nonce_key], $nonce_str . $_GET[$delete_key] ) ) {

                $key = intval($_GET[$delete_key]);

                // Delete the item from the database table
                $result = $wpdb->delete($table_name, array($delete_key => $key));

                if ($result !== false) {
                    // Deletion successful
                    // Make session base Admin Notice
                    $this->set_flash_msg();
                    wp_safe_redirect(wp_get_referer());
                    exit;
                } else {
                    // Deletion failed
                    wp_die('Error deleting item');
                }
            } else {
                // Nonce verification failed
                wp_die('Nonce verification failed');
            }
        }

        // die('Debug::Query Parameter Not Match');

    }

    public function build_query_for_action(
        $custom_admin_page_slug,
        $action_key_val,
        $action_key_name  = 'id',
        $nonce_str = 'delete_item_',
        $action_name = 'delete_item',
        $nonce_key = 'nonce'
    ){

        $nonce = wp_create_nonce($nonce_str . $action_key_val);

        $query_str = add_query_arg(
            array(
                $action_key_name => $action_key_val,
                $nonce_key => $nonce
            ),
            admin_url("admin.php?page=$custom_admin_page_slug&action=$action_name") // Adjust the URL according to your use case
        );

        return $query_str;

    }

    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()
    {
        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (!empty($ids)) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'my_table';
                foreach ($ids as $id) {
                    $wpdb->delete($table_name, array('id' => $id), array('%d'));
                }
            }
            
            // Make session base Admin Notice
            $this->set_flash_msg();

            $redirect_url =  get_admin_url(null, 'admin.php?page=my-plugin-setting');
            wp_safe_redirect($redirect_url);
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
    }

    function column_default($item, $column_name)
    {
        // return $item[$column_name];
        switch ($column_name) {
            case 'name':
                return $item[$column_name];
            case 'img':
                return $item[$column_name];
            case 'city':
                return $item[$column_name];
            case 'action':

                # this will build with nonce
                $delete_url = $this->build_query_for_action( 'my-plugin-settings', $item['id'] );

                $actions = array(
                    'view' => sprintf('<a href="?page=my-plugin-settings&action=view&id=%s">View</a>', $item['id']),
                    'edit' => sprintf('<a href="?page=my-plugin-settings&action=edit&id=%s">Edit</a>', $item['id']),
                    'delete' => sprintf('<a href="%s">Delete</a>', $delete_url),
                );

                return sprintf('%s | %s | %s', $actions['view'], $actions['edit'], $actions['delete']);

            default:
                return $item[$column_name];
        }
    }

    function column_name($item)
    {

        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&testimonial=%s">View</a>', $_REQUEST['page'], 'edit', $item['id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&testimonial=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id']),
        );

        //Return the title contents
        return sprintf(
            '<strong> %1$s </strong> <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/
            $item['name'],
            /*$2%s*/
            $item['id'],
            /*$3%s*/
            $this->row_actions($actions)
        );
    }


    function column_city($item)
    {
        return '<strong>' . $item['city'] . '</strong>';
    }

    function column_img($item)
    {
        return '<img src="' . esc_url($item['img']) . '" alt="Image" style="max-width: 100px;">';
    }

    function get_columns()
    {
        return array(
            'cb' => '<input type="checkbox" />',
            'name' => 'Name',
            'city' => 'City',
            'img' => 'Image',
            'action' => 'Action',
        );
    }

    function search_box($text, $input_id)
    {
?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo esc_attr($input_id); ?>" name="s" value="<?php _admin_search_query(); ?>" />
            <?php submit_button($text, 'button', false, false, array('id' => 'search-submit')); ?>
        </p>
<?php
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    # Doing Filter Options

    function extra_tablenav($which)
    {


        global $wpdb;
        $table_name = $wpdb->prefix . 'my_table';
        $data = $wpdb->get_results("SELECT DISTINCT city FROM $table_name", ARRAY_A);

        $cities = array();

        if ($data) {
            foreach ($data as $k => $v) {
                $cities[] = $v['city'];
            }
        }

        if ('top' == $which) {

            if ($which === 'top') {
                // Get unique city values from your data
                // $cities = array_unique(wp_list_pluck($this->items, 'city'));

                // Render the dropdown filter
                echo '<div class="alignleft actions">';
                echo '<label for="filter_city" class="screen-reader-text">Filter by City</label>';
                echo '<select name="filter_city" id="filter_city">';
                echo '<option value="">All Cities</option>';
                foreach ($cities as $city) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        $city,
                        isset($_GET['filter_city']) && $_GET['filter_city'] === $city ? 'selected' : '',
                        $city
                    );
                }
                echo '</select>';
                echo '<input type="submit" name="filter_action" id="post-query-submit" class="button" value="Filter">';
                echo '</div>';
            }
        } else if ('bottom' == $which) {
        }
    }

    function prepare_items()
    {

        #---------------------------------------------
        // Show my session base flush msg
        $this->get_flash_msg();
        // My single Delete Items
        $this->handle_delete_item();
        #---------------------------------------------

        $table_data = $this->get_table_data();

        # Call to process bulk actions
        // Need to call this
        $this->process_bulk_action();

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable, 'cb');

        // Handle sorting

        // Handle sorting
        $orderby = isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : 'name';
        $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'asc';

        // Modify your query to include sorting
        if ($orderby && $order) {
            usort($table_data, function ($a, $b) use ($orderby, $order) {
                $result = strcmp($a[$orderby], $b[$orderby]);
                return ($order === 'asc') ? $result : -$result;
            });
        }

        // Handle search
        $search = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';
        if (!empty($search)) {
            $table_data = array_filter($table_data, function ($item) use ($search) {
                return stripos($item['name'], $search) !== false;
            });
        }

        // Handle Filter
        $filter = isset($_REQUEST['filter_city']) ? sanitize_text_field($_REQUEST['filter_city']) : '';
        if (!empty($filter)) {
            $table_data = array_filter($table_data, function ($item) use ($filter) {
                return stripos($item['city'], $filter) !== false;
            });
        }

        // Handle pagination
        $per_page = 5;
        $current_page = $this->get_pagenum();
        $total_items = count($table_data);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page
        ));

        $offset = ($current_page - 1) * $per_page;
        $this->items = array_slice($table_data, $offset, $per_page);
    }



    function get_table_data()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'my_table';
        $data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        return $data;
    }

    function get_sortable_columns()
    {
        return array(
            'name' => array('name', false),
            'city' => array('city', false),
            'img' => array('name', false)
        );
    }
}
