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
                </tr>         
            </thead>

            <tbody>

            	<?php
            		if( $data ){
            			foreach( $data as $k => $v ){
            				echo '<tr>';
            				echo '<td>'.$v['name'].'</td>';
            				echo '<td>'.$v['email'].'</td>';
            				echo '<td>'.$v['city'].'</td>';
            				echo '<td>'.$v['country'].'</td>';
            				echo '</tr>';
            			}
            		}
            	?>

            </tbody>
		</table>

<?php
	}

}