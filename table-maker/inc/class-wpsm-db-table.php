<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

class WPSM_DB_Table {
	private $db;

	function __construct() {
		global $wpdb;
		$this->db = $wpdb;
		$this->table_name = $this->db->prefix ."wpsm_tables";
		$this->old_table_name = "wpsm_tables"; // since v.1.1
		$this->db_version = "1.1";
	}

	public static function get_instance() {
		static $instance = null;
		if($instance == null){
			$instance = new WPSM_DB_Table();
		}
		return $instance;
	}

	public function create_table() {
		$current_version = get_option('wpsm_db_table_version');
		if($current_version && $current_version == $this->db_version && $this->db->get_var("SHOW TABLES LIKE '$this->table_name'") == $this->table_name){
			return;
		}

		$sql = "
			CREATE TABLE $this->table_name (
				id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
				name TINYTEXT NOT NULL,
				rows MEDIUMINT(9) NOT NULL,
				cols MEDIUMINT(9) NOT NULL,
				subs TINYTEXT NOT NULL,
				color TINYTEXT NOT NULL,
				responsive tinyint(1) NOT NULL DEFAULT '0',
				tvalues LONGTEXT NOT NULL,
				UNIQUE KEY id (id)
			);
		";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		// since v.1.1
		if($this->db->get_var("SHOW TABLES LIKE '$this->old_table_name'") == $this->old_table_name){
			$this->upgrade_new_name_table();
		}
		
		update_option('wpsm_db_table_version', $this->db_version);
	}

	public function add($name, $rows, $cols, $subs, $color, $responsive, $tvalues) {
		$name 	= wp_strip_all_tags(wp_unslash($name));
		$rows 		= intval(wp_unslash($rows));
		$cols 		= intval(wp_unslash($cols));
		$subs 		= strval(wp_unslash($subs));
		$color 		= strval(wp_unslash($color));
		$responsive 		= intval(wp_unslash($responsive));
		$tvalues 	= $this->serialize(wp_unslash($tvalues));

		$result = $this->db->insert( $this->table_name, array('name' => $name, 'rows' => $rows, 'cols' => $cols, 'subs' => $subs, 'color' => $color, 'responsive' => $responsive, 'tvalues' => $tvalues ) );
		if($result)
			return $this->db->insert_id;
		return false;
	}

	public function update($id, $name, $rows, $cols, $subs, $color, $responsive, $tvalues) {
		$name 	= wp_strip_all_tags(wp_unslash($name));
		$rows 		= intval(wp_unslash($rows));
		$cols 		= intval(wp_unslash($cols));
		$subs 		= strval(wp_unslash($subs));
		$color 		= strval(wp_unslash($color));
		$responsive 		= intval(wp_unslash($responsive));
		$tvalues 	= $this->serialize(wp_unslash($tvalues));

		return $this->db->update( $this->table_name, array('name' => $name, 'rows' => $rows, 'cols' => $cols, 'subs' => $subs, 'color' => $color, 'responsive' => $responsive, 'tvalues' => $tvalues ), array( 'id' => $id ) );
	}

	public function drop_table() {
		$query = "DROP TABLE $this->table_name";
		return $this->db->query($query);
	}

	public function delete($id) {
		$query = $this->db->prepare("DELETE FROM $this->table_name WHERE id IN (%d)", $id);
		return $this->db->query($query);
	}

	public function get($id){
		$query = $this->db->prepare("SELECT * FROM $this->table_name WHERE id IN (%d)", $id);
		$row = $this->db->get_row($query, ARRAY_A);
		if($row){
			$row['tvalues'] = $this->unserialize($row['tvalues']);
			return $row;
		}
		return false;
	}

	public function get_page_items($curr_page, $per_page){
		$start = (($curr_page-1)*$per_page);
		$query = "SELECT * FROM $this->table_name ORDER BY id DESC LIMIT $start, $per_page";
		return $this->db->get_results( $query, ARRAY_A );
	}

	public function get_count(){
		$count = $this->db->get_var("SELECT COUNT(*) FROM $this->table_name");
		return isset($count) ? $count : 0;
	}

	private function serialize($item){
		return base64_encode(serialize($item));
	}

	private function unserialize($item){
		return unserialize(base64_decode($item));
	}
	
	/**
	 * Removes old table and moves its data into renamed one
	 *
	 * @since v.1.1
	 **/
	public function upgrade_new_name_table(){
		$copy_query = "INSERT INTO $this->table_name SELECT * FROM $this->old_table_name";
		$this->db->query($copy_query);
		$delete_query = "DROP TABLE $this->old_table_name";
		$this->db->query($delete_query);
	}
}

?>