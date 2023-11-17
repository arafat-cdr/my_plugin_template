<?php

class Plugin_Db {
    private static $instance;
    private $wpdb;

    private function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function create_custom_table() {
        $table_name = $this->wpdb->prefix . 'my_table';
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            city VARCHAR(255) NOT NULL,
            company_logo VARCHAR(255) DEFAULT NULL,
            api_token TEXT DEFAULT NULL,
            user_id VARCHAR(255) DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
