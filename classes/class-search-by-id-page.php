<?php

class Search_By_ID_Page {
    
    private static $instance = null;

    private function __construct() {

    	add_action('admin_menu', array($this, 'add_admin_menu'));

    }

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add_admin_menu(){
    	add_submenu_page(
    	    'my-plugin-menu',
    	    'Search By Id Page',
    	    'Search By Id Page',
    	    'manage_options',
    	    'search-by-id-page',
    	    array($this, 'render_search_by_id_page')
    	);
    }

    public function render_search_by_id_page()
    {

    	// Check if the Search Id is found on request
    	$my_search_id = '';
    	if( isset( $_REQUEST['my_search_id'] ) && $_REQUEST['my_search_id'] ){
    		$my_search_id = sanitize_text_field( $_REQUEST['my_search_id'] );
    	}

	?>

        <div class="containers top-20">
            <form class="contact"  method="get" action="">

                <h3>
                    <?php _e( 'Search By Id From', My_Custom_Plugin ); ?>
                </h3>
                <hr>
                <br>
            <?php wp_nonce_field('search_by_id_nonce_action', 'search_by_id_nonce_key'); ?>
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
            <input type="text" name="my_search_id" placeholder="Enter Search Id" value="<?php echo $my_search_id; ?>" required>
            <input type="submit" value="Search" class="button button-primary">
        </form>
    	</div>

    	<div class="containers top-20" style="float:left; color: black; min-width:80%; font-weight: bold;">
    		<div class="contact" style="min-width: 100%;">
    			<h3>
    				<?php _e( 'Search  Information', My_Custom_Plugin ); ?>
    			</h3>
    			<br>
    			<hr>
    			<br>
    			<?php 

    			$this->handle_search_action();

    			?>
    		</div>
    	</div>

    <?php

    }


    public function handle_search_action() {
        if (isset($_GET['search_by_id_nonce_key']) && wp_verify_nonce($_GET['search_by_id_nonce_key'], 'search_by_id_nonce_action')) {
            if (isset($_GET['my_search_id']) && !empty($_GET['my_search_id'])) {
                // Call the tracking API method here
                $this->call_db_or_api($_GET['my_search_id']);
            }
        }
    }

    private function call_db_or_api($my_search_id) {
        pr( $_REQUEST );
    	echo 'Search Id: '.$my_search_id;
    	// die('Calling My Api');

    }
}

