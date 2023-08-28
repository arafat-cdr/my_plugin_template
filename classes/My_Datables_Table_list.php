<?php

class My_Datables_Table_list{

	private static $instance;

	private function __construct(){
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
		add_action('admin_menu', array($this, 'add_admin_menu'));
		// add_action('admin_post_arafat_custom_call', array($this, 'arafat_metod_to_call'));
	}

	public static function get_instance(){
		if (!self::$instance) {
		    self::$instance = new self();
		}
		return self::$instance;
	}

	public function enqueue_admin_scripts(){
		// loading data table css
		wp_register_style( 'wl_custom_datatable', 'https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css', false, '1.0.0' );
		wp_enqueue_style( 'wl_custom_datatable' );
		

		// loading select 2
		wp_register_style( 'wl_custom_admin_select_2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', false, '1.0.0' );
		    wp_enqueue_style( 'wl_custom_admin_select_2' );


		// loading data table js
		wp_register_script( 'wl_custom_admin_datatable_script', 'https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js', array('jquery-core'), false, true );
		wp_enqueue_script( 'wl_custom_admin_datatable_script' );

		// loading moment js
		wp_register_script( 'wl_custom_admin_moment_script', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js', array('jquery-core'), false, true );
		wp_enqueue_script( 'wl_custom_admin_moment_script' );
		     

		// loading select 2 script
		wp_register_script( 'wl_custom_admin_select_2_js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery-core'), false, true );
		wp_enqueue_script( 'wl_custom_admin_select_2_js' );
	}


	public function add_admin_menu() {

	    add_submenu_page(
	        'my-plugin-menu',
	        'Datatable Example',
	        'Datatable Example',
	        'manage_options',
	        'my-plugin-data-table',
	        array($this, 'render_table_list_page')
	    );


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


	function generate_sample_data($count = 50) {
	    $data = array();

	    $names = array('John', 'Jane', 'Alice', 'Bob', 'Eva');
	    $cities = array('New York', 'Los Angeles', 'Chicago', 'Miami', 'San Francisco');
	    $countries = array('USA', 'Canada', 'UK', 'Australia', 'Germany');

	    for ($i = 1; $i <= $count; $i++) {
	        $data[] = array(
	            'name' => $names[array_rand($names)] . ' ' . $i,
	            'email' => 'user' . $i . '@example.com',
	            'city' => $cities[array_rand($cities)],
	            'country' => $countries[array_rand($countries)],
	        );
	    }

	    return $data;
	}


	public function render_table_list_page(){
		
		$this->get_flash_msg();
		$this->handle_delete_item();

		$data = $this->generate_sample_data();
?>

<div class="container" style="min-width:100%;"> 
	<div style="overflow-x:auto;">
		<h3 class="text-center">
			User List
		</h3>
		<hr>
		<table class="wl_table" style="margin-top: 20px;">
			<thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>actions</th>
                </tr>         
            </thead>

            <tbody>

            	<?php
            		if( $data ){
            			foreach( $data as $k => $v ){

            				$id = $v['email'];
            				$delete_url = $this->build_query_for_action( 'my-plugin-admin-page-slug', $id );

            				echo '<tr>';
            				echo '<td>'.$v['name'].'</td>';
            				echo '<td>'.$v['email'].'</td>';
            				echo '<td>'.$v['city'].'</td>';
            				echo '<td>'.$v['country'].'</td>';
            				echo '<td>';
            				?>

            				<a href="<?php echo $delete_url; ?>"><button class="text-red pointer" onclick="return confirm('You want to delete this?')">Delete</button></a>
            				<a href="#"><button class="text-green pointer">Edit</button></a>
            				<a href="#"><button class="text-green pointer">View Details</button></a>

            				<?php
            				echo '</td>';
            				echo '</tr>';
            			}
            		}
            	?>

            </tbody>
		</table>

<?php
	}

}