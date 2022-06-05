<?php

namespace OXI_TABS_PLUGINS\Helper;

/**
 *
 * @author biplo
 */
Class Database {

    /**
     * Define $wpdb
     *
     * @since 3.3.0
     */
    public $wpdb;

    /**
     * Database Parent Table
     *
     * @since 3.3.0
     */
    public $parent_table;

    /**
     * Database Import Table
     *
     * @since 3.3.0
     */
    public $import_table;

    /**
     * Database Import Table
     *
     * @since 3.3.0
     */
    public $child_table;
    protected static $lfe_instance = NULL;

    /**
     * Access plugin instance. You can create further instances by calling
     */
    public static function get_instance() {
        if (NULL === self::$lfe_instance)
            self::$lfe_instance = new self;

        return self::$lfe_instance;
    }

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->parent_table = $wpdb->prefix . 'content_tabs_ultimate_style';
        $this->child_table = $wpdb->prefix . 'content_tabs_ultimate_list';
        $this->import_table = $wpdb->prefix . 'oxi_div_import';
    }

    public function update_database() {
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql1 = "CREATE TABLE $this->parent_table (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
                name varchar(50) NOT NULL,
		style_name varchar(10) NOT NULL,
                rawdata longtext,
                stylesheet longtext,
                font_family text,
		PRIMARY KEY  (id)
	) $charset_collate;";
        $sql2 = "CREATE TABLE $this->child_table (
                id mediumint(5) NOT NULL AUTO_INCREMENT,
                styleid mediumint(6) NOT NULL,
		rawdata longtext,
		PRIMARY KEY  (id)
	)$charset_collate;";
        
          $sql3 = "CREATE TABLE $this->import_table (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
                type varchar(50) NULL,
                name varchar(100) NULL,
                font varchar(200) NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql1);
        dbDelta($sql2);
        dbDelta($sql3);
    }

}
