<?php

if ( !defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Smart_Manager_Base' ) ) {
	class Smart_Manager_Base {

		public $dashboard_key = '',
			$dashboard_title = '',
			$post_type = '',
			$default_store_model = array(),
			$terms_val_parent = array(),
			$req_params = array(),
			$terms_sort_join = false,
			$advance_search_operators = array(
												'eq'=> '=',
												'neq'=> '!=',
												'lt'=> '<',
												'gt'=> '>',
												'lte'=> '<=',
												'gte'=> '>='
										);

		// include_once $this->plugin_path . '/class-smart-manager-utils.php';

		function __construct($dashboard_key) {
			$this->dashboard_key = $dashboard_key;
			$this->post_type = $dashboard_key;
			$this->plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) );
			$this->req_params  	= (!empty($_REQUEST)) ? $_REQUEST : array();
			$this->dashboard_title = ( !empty( $this->req_params['active_module_title'] ) ) ? $this->req_params['active_module_title'] : 'Post';

			add_filter('posts_join_paged',array(&$this,'sm_query_join'),99,2);
			add_filter('posts_where',array(&$this,'sm_query_post_where_cond'),99,2);
			add_filter('posts_groupby',array(&$this,'sm_query_group_by'),99,2);
			add_filter('posts_orderby',array(&$this,'sm_query_order_by'),99,2);
		}

		public function sm_query_join ($join, $wp_query_obj) {

			global $wpdb;

			$sort_params = array();
			if( $wp_query_obj ){
				$sort_params = ( ! empty( $wp_query_obj->query_vars['sm_sort_params'] ) ) ? $wp_query_obj->query_vars['sm_sort_params'] : array();		
			}

			// Code for sorting of the terms columns
			if ( !empty( $sort_params ) ) {

				if( !empty( $sort_params['column_nm'] ) && !empty( $sort_params['sortOrder'] ) ) {

					if( !empty( $sort_params['table'] ) && $sort_params['table'] == 'terms' ) {

						$join_condition = "AND ". $wpdb->prefix ."term_taxonomy.taxonomy = '". $sort_params['column_nm'] ."'";
						$join_condition = apply_filters('sm_terms_sort_join_condition', $join_condition, $wp_query_obj);

						// Query to get the ordered term_taxonomy_ids of the taxonomy being sorted
						$taxonomy_ids = $wpdb->get_col( $wpdb->prepare( "SELECT {$wpdb->prefix}term_taxonomy.term_taxonomy_id
																		FROM {$wpdb->prefix}term_taxonomy
																			JOIN {$wpdb->prefix}terms
																			ON ( {$wpdb->prefix}terms.term_id = {$wpdb->prefix}term_taxonomy.term_id 
																				 ". $join_condition ." )
																		WHERE 1=%d", 1 ) );

						$rows_taxonomy_ids = $wpdb->num_rows;

						if ( count( $taxonomy_ids ) > 0 && strpos( $join, 'taxonomy_sort' ) === false ) { //added 'term_relationships' check as the event gets fired more than once in some cases causing the queryto break
							$join .= " LEFT JOIN ( SELECT {$wpdb->prefix}term_relationships.object_id as object_id,
														{$wpdb->prefix}terms.name as term_name
													FROM {$wpdb->prefix}term_relationships
														JOIN {$wpdb->prefix}term_taxonomy
															ON( {$wpdb->prefix}term_taxonomy.term_taxonomy_id = {$wpdb->prefix}term_relationships.term_taxonomy_id
																AND {$wpdb->prefix}term_relationships.term_taxonomy_id IN (" .implode(",",$taxonomy_ids). ") ) 
														JOIN {$wpdb->prefix}terms
															ON ( {$wpdb->prefix}terms.term_id = {$wpdb->prefix}term_taxonomy.term_id ) 
															". $join_condition ." ) as taxonomy_sort 
										ON (taxonomy_sort.object_id = {$wpdb->prefix}posts.ID)";
							
							$this->terms_sort_join = true;
						}
					}
				}
			}

			//Code for handling search
			if( !empty($this->req_params) && !empty($this->req_params['advanced_search_query']) && $this->req_params['advanced_search_query'] != '[]' && strpos($join,'sm_advanced_search_temp') === false ) {
				$join .= " JOIN {$wpdb->base_prefix}sm_advanced_search_temp
                            	ON ({$wpdb->base_prefix}sm_advanced_search_temp.product_id = {$wpdb->prefix}posts.id)";
			}

			//Code for handling simple search
			if( !empty( $this->req_params['search_text'] ) && strpos( $join, 'postmeta' ) === false ) {
				$join .= " JOIN {$wpdb->prefix}postmeta
                            	ON ({$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.id)";
			}

			return $join;
		}

		public function sm_query_post_where_cond ($where, $wp_query_obj) {

			global $wpdb, $current_user;

			//Code for handling search
			if( !empty($this->req_params) && !empty($this->req_params['advanced_search_query']) && $this->req_params['advanced_search_query'] != '[]' && strpos($where,'sm_advanced_search_temp.flag > 0') === false ) {
				$where .= " AND {$wpdb->base_prefix}sm_advanced_search_temp.flag > 0";
			}

			//Code for handling simple search
			if( !empty( $this->req_params['search_text'] ) ) {

				$store_model_transient = get_transient( 'sa_sm_'.$this->dashboard_key );

				if( ! empty( $store_model_transient ) && !is_array( $store_model_transient ) ) {
					$store_model_transient = json_decode( $store_model_transient, true );
				}
				
				$col_model = (!empty($store_model_transient['columns'])) ? $store_model_transient['columns'] : array();
	        	$search_text = $wpdb->_real_escape( $this->req_params['search_text'] );

	        	$ignored_cols = array('comment_count', 'post_mime_type', 'post_type', 'menu_order');
	        	$simple_search_ignored_cols = apply_filters('sm_simple_search_ignored_posts_columns', $ignored_cols, $col_model);
	        	$matchedResults = array();

	        	//Code for getting users table condition
	        	if( !empty( $col_model ) ) {
	        		foreach( $col_model as $col ) {
	        			if( empty( $col['src'] ) ) continue;

						$src_exploded = explode("/",$col['src']);

						if( !empty( $src_exploded[0] ) && $src_exploded[0] == 'posts' && !in_array($src_exploded[1], $simple_search_ignored_cols) ) {

							if( !empty( $col['selectOptions'] ) ) {
								$matchedResults = preg_grep('/'. ucfirst($search_text) .'.*/', $col['selectOptions']);
							}

							if( is_array( $matchedResults ) && !empty( $matchedResults ) ) {
								foreach( array_keys( $matchedResults ) as $search ) {
									if( false === strpos( $where, "{$wpdb->prefix}posts.".$src_exploded[1]." LIKE '%".$search."%'" ) ) {
										$user_where_cond[] = "( {$wpdb->prefix}posts.".$src_exploded[1]." LIKE '%".$search."%' )";									
									}
								}
							} else {
								if( false === strpos( $where, "{$wpdb->prefix}posts.".$src_exploded[1]." LIKE '%".$search_text."%'" ) ) {
									$user_where_cond[] = "( {$wpdb->prefix}posts.".$src_exploded[1]." LIKE '%".$search_text."%' )";
								}
							}
						}
	        		}
	        	}

	        	$where .= ( strpos( $where, 'meta_value LIKE' ) === false || ( !empty( $user_where_cond ) ) ) ? ' AND ( ' : '';
				$where .= ( strpos( $where, "meta_value LIKE '%".$search_text."%" ) === false ) ? " ({$wpdb->prefix}postmeta.meta_value LIKE '%".$search_text."%') " : '';
				$where .= ( ( !empty( $user_where_cond ) ) ? ' OR '. implode(" OR ", $user_where_cond) : '' );
				$where .= ( strpos( $where, 'meta_value LIKE' ) === true || ( !empty( $user_where_cond ) ) ) ? ' ) ' : '';

			}

			return $where;
		}

		public function sm_query_group_by ($group_by, $wp_query_obj) {
			
			global $wpdb;

			if( strpos( $group_by, 'posts.id' ) === false ) {
				$group_by = $wpdb->prefix.'posts.id';
			}
			return $group_by;
		}

		public function sm_query_order_by ($order_by, $wp_query_obj) {

			global $wpdb;

			$sort_params = array();
			if( $wp_query_obj ){
				$sort_params = ( ! empty( $wp_query_obj->query_vars['sm_sort_params'] ) ) ? $wp_query_obj->query_vars['sm_sort_params'] : array();		
			}

			if( !empty( $sort_params ) && !empty( $sort_params['column_nm'] ) ) {
				$sort_order = ( !empty( $sort_params['sortOrder'] ) ) ? $sort_params['sortOrder'] : 'ASC';

				if( !empty( $sort_params['table'] ) ) {
					if ( $sort_params['table'] == 'posts' ) {
						$order_by = $sort_params['column_nm'] .' '. $sort_order;
					} else if ( $sort_params['table'] == 'terms' && $this->terms_sort_join === true ) {
						$order_by = ' taxonomy_sort.term_name '.$sort_order ;
					}	
				}
			}

			//Condition for sorting of postmeta_cols
			if ( !empty( $sort_params ) &&  !empty( $sort_params['sort_by_meta_key'] ) ) {
				$sort_order = ( !empty( $sort_params['sortOrder'] ) ) ? $sort_params['sortOrder'] : 'ASC';

				$post_type = ( ! empty( $this->post_type ) ) ? $this->post_type : $this->req_params['active_module'];
				$post_type = ( ! is_array( $post_type ) ) ? array( $post_type ) : $post_type;

				$meta_value = ( !empty( $sort_params['column_nm'] ) && $sort_params['column_nm'] == 'meta_value_num' ) ? 'pm.meta_value+0' : 'pm.meta_value';

				$post_ids = $wpdb->get_col( "SELECT DISTINCT p.ID 
											FROM {$wpdb->prefix}posts AS p
												LEFT JOIN {$wpdb->prefix}postmeta AS pm
													ON (p.ID = pm.post_id
														AND pm.meta_key = '". $sort_params['sort_by_meta_key'] ."')
											WHERE p.post_type IN ('". implode("','", $post_type) ."')
											ORDER BY ". $meta_value ." ". $sort_order );

				$option_name = 'sm_data_model_sorted_ids';
				update_option( $option_name, implode( ',', $post_ids ) );

				$limit = ( isset( $wp_query_obj->query['posts_per_page'] ) && isset( $wp_query_obj->query['offset'] ) && $wp_query_obj->query['posts_per_page'] > 0 ) ? ( " LIMIT ". $wp_query_obj->query['offset'] .", ". $wp_query_obj->query['posts_per_page'] ) : '';

				$order_by = " FIND_IN_SET( ".$wpdb->prefix."posts.ID, ( SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name = '".$option_name."' ) ) ";
			}

			return $order_by;
		}

		public function get_default_store_model() {

			global $wpdb;

			$col_model = array();

			$ignored_col = array('post_type');

			$query_posts_col = "SHOW COLUMNS FROM {$wpdb->prefix}posts";
			$results_posts_col = $wpdb->get_results($query_posts_col, 'ARRAY_A');
			$posts_num_rows = $wpdb->num_rows;
			$last_position = 0;

			if ($posts_num_rows > 0) {
				foreach ($results_posts_col as $posts_col) {
					
					$temp = array();
					$field_nm = (!empty($posts_col['Field'])) ? $posts_col['Field'] : '';

					if( in_array($field_nm, $ignored_col) ) {
						continue;
					}

					$temp ['src'] = 'posts/'.$field_nm;
					$temp ['data'] = strtolower(str_replace(array('/','='), '_', $temp ['src'])); // generate slug using the wordpress function if not given 
					$temp ['name'] = ucwords( str_replace('_', ' ', $field_nm) );
					$temp ['name'] = __(str_replace('Post', '', $temp ['name']), 'smart-manager-for-wp-e-commerce');

					$temp ['table_name'] = $wpdb->prefix.'posts';
					$temp ['col_name'] = $field_nm;

					$temp ['key'] = $temp ['name']; //for advanced search

					$type = 'text';
					$temp ['width'] = 100;
					$temp ['align'] = 'left';

					if (!empty($posts_col['Type'])) {
						$type_strpos = strrpos($posts_col['Type'],'(');
						if ($type_strpos !== false) {
							$type = substr($posts_col['Type'], 0, $type_strpos);
						} else {
							$types = explode( " ", $posts_col['Type'] ); // for handling types with attributes (biginit unsigned)
							$type = ( ! empty( $types ) ) ? $types[0] : $posts_col['Type']; 
						}

						if (substr($type,-3) == 'int') {
							$type = 'numeric';
							$temp ['min'] = 0;
							$temp ['width'] = 50;
							$temp ['align'] = 'right';
						} else if ($type == 'text') {
							$temp ['width'] = 130;
						} else if (substr($type,-4) == 'char' || substr($type,-4) == 'text') {
							if ($type == 'longtext') {
								$type = 'sm.longstring';
								$temp ['width'] = 150;
							} else {
								$type = 'text';
							}
						} else if (substr($type,-4) == 'blob') {
							$type = 'sm.longstring';
						} else if ($type == 'datetime' || $type == 'timestamp') {
							$type = 'sm.datetime';
							$temp ['width'] = 102;
						} else if ($type == 'date' || $type == 'year') {
							$type = 'sm.date';
						} else if ($type == 'decimal' || $type == 'float' || $type == 'double' || $type == 'real') {
							$type = 'numeric';
							$temp ['min'] = 0;
							$temp ['width'] = 50;
							$temp ['align'] = 'right';
						} else if ($type == 'boolean') {
							$type = 'checkbox';
							$temp ['width'] = 30;
						}

					}

					$temp ['hidden']			= false;
					$temp ['batch_editable']	= true; // flag for enabling the batch edit for the column
					$temp ['sortable']			= true;
					$temp ['resizable']			= true;
					$temp ['frozen']			= false; //For disabling frozen
					$temp ['wordWrap']			= false; //For disabling word-wrap

					$temp ['allow_showhide']	= true;
					$temp ['exportable']		= true; //default true. flag for enabling the column in export
					$temp ['searchable']		= true;

					//Code for handling the positioning of the columns
					if ($field_nm == 'ID') {
						$type = 'text';
						$temp ['position'] = 1;
						$temp ['editor'] = false;
						$temp ['batch_editable'] = false;
						$temp ['align'] = 'left';
						// $temp ['frozen'] = true;
					} else if ($field_nm == 'post_title') {
						$temp ['width'] = 200;
						$temp ['position'] = 2; // based on order of definition if not given or more than one column having same position
						// $temp ['frozen'] = true;
					} else if ($field_nm == 'post_content') {
						$temp ['position'] = 3;
					} else if ($field_nm == 'post_status') {
						$temp ['position'] = 4;
					} else if ($field_nm == 'post_date') {
						$temp ['position'] = 5;
						// $temp ['searchable'] = false;
						$temp ['name'] = $this->dashboard_title . ' Created Date';
						$temp ['key'] = $temp ['name'];
					} else if ($field_nm == 'post_name') {
						$last_position = $temp ['position'] = 6;
					} else if ($field_nm == 'post_date_gmt') {
						$temp ['name'] = $this->dashboard_title . ' Created Date Gmt';
						$temp ['key'] = $temp ['name'];
					}  else if ($field_nm == 'post_excerpt') {
						$type = 'sm.longstring';
					}

					$temp ['editor'] = $type;
					$temp ['type'] = $type;

					$temp ['values'] = array();
					if ($field_nm == 'post_status') {
						$temp ['type'] = 'dropdown';
						$temp ['strict'] = true;
						$temp ['allowInvalid'] = false;

						$temp ['values'] = ( 'page' === $this->dashboard_key ) ? get_page_statuses() : get_post_statuses();
						$temp ['search_values'] = array();
						$temp ['defaultValue'] = 'draft';

						$temp['search_values'] = array();

						if( !empty( $temp['values'] ) ) {
							foreach( $temp['values'] as $key => $value ) {
								$temp['search_values'][] = array( 'key' => $key, 'value' => $value );
							}
						}

						$temp ['editor'] = 'select';
						$temp ['selectOptions'] = $temp['values'];
						$temp ['renderer'] = 'selectValueRenderer';

						// $temp ['colorCodes'] = array( 'green' => array( 'publish' ),
						// 								'orange' => array( 'draft' ),
						// 								'blue' => array() );

					}

					if ( $field_nm == 'ID' || $field_nm == 'post_title' || $field_nm == 'post_date' || $field_nm == 'post_name'
						 || $field_nm == 'post_status' || $field_nm == 'post_content') {
						$temp ['hidden'] = false;
					} else {
						$temp ['hidden'] = true;
					}

					if( $temp ['type'] == 'sm.longstring' ) {
						$temp ['editable'] = false;
					}

					$temp ['category'] = ''; //for advanced search
					$temp ['placeholder'] = ''; //for advanced search

					$col_model [] = $temp;

				}
			}

			//Code to get columns from postmeta table

			$post_type_cond = (is_array($this->post_type)) ? " AND {$wpdb->prefix}posts.post_type IN ('". implode("','", $this->post_type) ."')" : " AND {$wpdb->prefix}posts.post_type = '". $this->post_type ."'";

			$query_postmeta_col = "SELECT DISTINCT {$wpdb->prefix}postmeta.meta_key,
											{$wpdb->prefix}postmeta.meta_value
										FROM {$wpdb->prefix}postmeta 
											JOIN {$wpdb->prefix}posts ON ({$wpdb->prefix}posts.id = {$wpdb->prefix}postmeta.post_id)
										WHERE {$wpdb->prefix}postmeta.meta_key != '' 
											AND {$wpdb->prefix}postmeta.meta_key NOT LIKE 'free-%'
											AND {$wpdb->prefix}postmeta.meta_key NOT LIKE '_oembed%'
											$post_type_cond
										GROUP BY {$wpdb->prefix}postmeta.meta_key";
			$results_postmeta_col = $wpdb->get_results ($query_postmeta_col , 'ARRAY_A');
			$num_rows = $wpdb->num_rows;

			if ($num_rows > 0) {

				$meta_keys = array();

				foreach ($results_postmeta_col as $key => $postmeta_col) {
					if ( empty( $postmeta_col['meta_value'] ) || $postmeta_col['meta_value'] == '1' || $postmeta_col['meta_value'] == '0.00' ) {
						$meta_keys [] = $postmeta_col['meta_key']; //TODO: if possible store in db instead of using an array
					}

					unset($results_postmeta_col[$key]);
					$results_postmeta_col[$postmeta_col['meta_key']] = $postmeta_col;
				}

				//not in 0 added for handling empty date columns
				if (!empty($meta_keys)) {
					$query_meta_value = "SELECT {$wpdb->prefix}postmeta.meta_key,
													{$wpdb->prefix}postmeta.meta_value
												FROM {$wpdb->prefix}postmeta 
													JOIN {$wpdb->prefix}posts ON ({$wpdb->prefix}posts.id = {$wpdb->prefix}postmeta.post_id)
												WHERE {$wpdb->prefix}posts.post_type  = '". $this->dashboard_key ."'
													AND {$wpdb->prefix}postmeta.meta_value NOT IN ('','0','0.00','1')
													AND {$wpdb->prefix}postmeta.meta_key IN ('".implode("','",$meta_keys)."')
												GROUP BY {$wpdb->prefix}postmeta.meta_key";
					$results_meta_value = $wpdb->get_results ($query_meta_value , 'ARRAY_A');
					$num_rows_meta_value = $wpdb->num_rows;

					if ($num_rows_meta_value > 0) {
						foreach ($results_meta_value as $result_meta_value) {
							if (isset($results_postmeta_col [$result_meta_value['meta_key']])) {
								$results_postmeta_col [$result_meta_value['meta_key']]['meta_value'] = $result_meta_value['meta_value'];
							}
						}
					}
				}

				//Filter to add custom postmeta columns for custom plugins
				$results_postmeta_col = apply_filters('sm_default_dashboard_model_postmeta_cols', $results_postmeta_col);

				$index = sizeof($col_model);

				$meta_count = 0;

				//Code for pkey column for postmeta

				$col_model [$index] = array();
				$col_model [$index]['src'] = 'postmeta/post_id';
				$col_model [$index]['data'] = strtolower(str_replace(array('/','='), '_', $col_model [$index]['src'])); // generate slug using the wordpress function if not given 
				$col_model [$index]['name'] = __(ucwords(str_replace('_', ' ', 'post_id')), 'smart-manager-for-wp-e-commerce');
				$col_model [$index]['key'] = $col_model [$index]['name']; //for advanced search
				$col_model [$index]['type'] = 'numeric';
				$col_model [$index]['hidden']	= true;
				$col_model [$index]['allow_showhide'] = false;
				$col_model [$index]['editor']	= false;

				$col_model [$index]['table_name'] = $wpdb->prefix.'postmeta';
				$col_model [$index]['col_name'] = 'post_id';

				$col_model [$index] ['category'] = ''; //for advanced search
				$col_model [$index] ['placeholder'] = ''; //for advanced search

				foreach ($results_postmeta_col as $postmeta_col) {

					$temp = array();
					$type = 'text';

					$meta_key = ( !empty( $postmeta_col['meta_key'] ) ) ? $postmeta_col['meta_key'] : '';
					$meta_value = ( !empty( $postmeta_col['meta_value'] ) || $postmeta_col['meta_value'] == 0 ) ? $postmeta_col['meta_value'] : '';

					$temp ['src'] = 'postmeta/meta_key='.$meta_key.'/meta_value='.$meta_key;
					$temp ['data'] = strtolower(str_replace(array('/','='), '_', $temp ['src'])); // generate slug using the wordpress function if not given 
					$temp ['name'] = __(ucwords(str_replace('_', ' ', $meta_key)), 'smart-manager-for-wp-e-commerce');
					$temp ['key'] = $temp ['name']; //for advanced search

					$temp ['table_name'] = $wpdb->prefix.'postmeta';
					$temp ['col_name'] = $meta_key;

					$temp ['width'] = 100;
					$temp ['align'] = 'left';

					if ( $meta_value == 'yes' || $meta_value == 'no' ) {
						$type = 'checkbox';
						$temp ['checkedTemplate'] = 'yes';
      					$temp ['uncheckedTemplate'] = 'no';
						$temp ['width'] = 30;
					} else if ( $meta_value == 'true' || $meta_value == 'false' ) {
						$type = 'checkbox';
						$temp ['width'] = 30;
					} else if ( strpos($meta_key, '_phone') !== false || strpos($meta_key, '_tel') !== false || strpos($meta_key, 'phone_') !== false || strpos($meta_key, 'tel_') !== false ) {
						$type = 'text';
						$temp ['width'] = 50;
						$temp ['validator'] = 'customPhoneTextEditor';
					} else if ( is_numeric($meta_value) && ( $meta_value === '0' || $meta_value === '1' )  ) {
						$type = 'checkbox';
						$temp ['checkedTemplate'] = 1;
      					$temp ['uncheckedTemplate'] = 0;
						$temp ['width'] = 30;
					} else if( is_numeric( $meta_value ) ) {

						if( function_exists('isTimestamp') ) {
							if( isTimestamp( $meta_value ) ) {
								$type = 'sm.datetime';
								$temp ['width'] = 102;
								$temp ['date_type'] = 'timestamp';
							}
						}

						if( $type != 'sm.datetime' ) {
							$type = 'numeric';
							$temp ['min'] = 0;
							$temp ['width'] = 50;
							$temp ['align'] = 'right';	
						}
					} else if (is_serialized($meta_value) === true) {
						$type = 'sm.serialized';
						$temp ['width'] = 200;
					} else if ( DateTime::createFromFormat('Y-m-d H:i:s', $meta_value) !== FALSE ) {
					  	$type = 'sm.datetime';
					  	$temp ['width'] = 102;
					} else if ( DateTime::createFromFormat('Y-m-d', $meta_value) !== FALSE ) {
					  	$type = 'sm.date';
					}

					$temp ['type'] = $type;
					$temp ['values'] = array();
					// $temp ['search_values'] = array();

					$temp ['hidden'] = false;
					$hidden_col_array = array('_edit_lock','_edit_last');

					// if ( array_search($meta_key,$hidden_col_array) !== false ) {
					if ( array_search($meta_key,$hidden_col_array) !== false || $meta_count > 5 ) {
						$temp ['hidden'] = true;	
					}

					
					$temp ['editor']			= $temp ['type'];
					$temp ['batch_editable']	= true; // flag for enabling the batch edit for the column
					$temp ['sortable']			= true;
					$temp ['resizable']			= true;
					$temp ['frozen']			= false;
					$temp ['allow_showhide']	= true;
					$temp ['exportable']		= true; //default true. flag for enabling the column in export
					$temp ['searchable']		= true;
					$temp ['wordWrap']			= false; //For disabling word-wrap
					$temp ['editor_schema']		= false;

					$temp ['category'] = ''; //for advanced search
					$temp ['placeholder'] = ''; //for advanced search


					if( $temp ['type'] == 'sm.longstring' ) {
						$temp ['editable'] = false;
					}

					// Handling for _thumnail_id => image column
					if( '_thumbnail_id' === $meta_key ){
						$temp ['name'] = $temp ['key']= __('Featured Image', 'smart-manager-for-wp-e-commerce');
						$temp ['width']= 25;
						$temp ['align']= 'center';
						$temp ['type']= 'sm.image';
						$temp ['searchable']= false;
						$temp ['editable']= false;
						$temp ['batch_editable']= true;
						$temp ['sortable']= false;
						$temp ['resizable']= true;
					}

					$col_model [] = $temp;

					$meta_count++;
				}
			}

			//Code to get columns from terms

			//Code to get all relevant taxonomy for the post type
			$taxonomy_nm = get_object_taxonomies($this->post_type);

			if (!empty($taxonomy_nm)) {

				$terms_val = array();
				$terms_val_search = array();
				$terms_val_parent = array();

				$index = sizeof($col_model);

				$terms_count = 0;
				//Code for pkey column for terms

				$col_model [$index] = array();
				$col_model [$index]['src'] = 'terms/object_id';
				$col_model [$index]['data'] = strtolower(str_replace(array('/','='), '_', $col_model [$index]['src'])); // generate slug using the wordpress function if not given 
				$col_model [$index]['name'] = __(ucwords(str_replace('_', ' ', 'object_id')), 'smart-manager-for-wp-e-commerce');
				$col_model [$index]['key'] = $col_model [$index]['name']; //for advanced search
				$col_model [$index]['type'] = 'numeric';
				$col_model [$index]['allow_showhide'] = false;
				$col_model [$index]['hidden']	= true;
				$col_model [$index]['editor']	= false;

				$col_model [$index]['table_name'] = $wpdb->prefix.'terms';
				$col_model [$index]['col_name'] = 'object_id';

				$col_model [$index]['category'] = ''; //for advanced search
				$col_model [$index]['placeholder'] = ''; //for advanced search

				$taxonomy_terms = get_terms($taxonomy_nm, array('hide_empty'=> 0,'orderby'=> 'name'));

				if (!empty($taxonomy_terms)) {
					// Code for storing the parent taxonomies titles
					$taxonomy_parents = array();
					foreach ($taxonomy_terms as $term_obj) {
						if( empty( $term_obj->parent ) ){
							$taxonomy_parents[$term_obj->term_id] = $term_obj->name;
						}
					}
					foreach ($taxonomy_terms as $term_obj) {

						if (empty($terms_val[$term_obj->taxonomy])) {
							$terms_val[$term_obj->taxonomy] = array();
						}

						$title = ucwords( ( ! empty( $taxonomy_parents[$term_obj->parent] ) ) ? ( $taxonomy_parents[$term_obj->parent] . ' — ' . $term_obj->name ) : $term_obj->name );

						$terms_val[$term_obj->taxonomy][$term_obj->term_id] = $title;
						$terms_val_search[$term_obj->taxonomy][$term_obj->slug] = $title; //for advanced search
						$this->terms_val_parent[$term_obj->taxonomy][$term_obj->term_id] = array();
						$this->terms_val_parent[$term_obj->taxonomy][$term_obj->term_id]['term'] = $term_obj->name;
						$this->terms_val_parent[$term_obj->taxonomy][$term_obj->term_id]['parent'] = $term_obj->parent;
						$this->terms_val_parent[$term_obj->taxonomy][$term_obj->term_id]['title'] = $title;
					}	
				}

				//Code for defining the col model for the terms
				foreach ($taxonomy_nm as $taxonomy) {

					$terms_col = array();

					$terms_col ['src'] 				= 'terms/'.$taxonomy;
					$terms_col ['data'] 			= strtolower(str_replace(array('/','='), '_', $terms_col ['src'])); // generate slug using the wordpress function if not given 
					$terms_col ['name'] 			= __(ucwords(str_replace('_', ' ', $taxonomy)), 'smart-manager-for-wp-e-commerce');
					$terms_col ['key'] 				= $terms_col ['name']; //for advanced search

					$terms_col ['table_name'] = $wpdb->prefix.'terms';
					$terms_col ['col_name'] = $taxonomy;

					$terms_col ['width'] = 200;
					$terms_col ['align'] = 'left';
					$terms_col ['editable']	= true;

					if (!empty($terms_val[$taxonomy])) {
						$terms_col ['type'] 		= 'sm.multilist';
						$terms_col ['strict'] 		= true;
						$terms_col ['allowInvalid'] = false;
						$terms_col ['values'] 		= $terms_val[$taxonomy];
						$terms_col ['editable']		= false;

						//code for handling values for advanced search
						$terms_col['search_values'] = array();
						foreach ($terms_val_search[$taxonomy] as $key => $value) {
							$terms_col['search_values'][] = array('key' => $key, 'value' =>  ucwords($value));
						}

						// $terms_col ['editor'] = 'select';
						// $terms_col ['selectOptions'] = $temp['values'];
						// $terms_col ['renderer'] = 'selectValueRenderer';

					} else {
						$terms_col ['type'] 		= 'text';
					}

					$terms_col ['hidden'] 			= false;
					$terms_col ['editor']			= $terms_col ['type'];
					$terms_col ['batch_editable']	= true; // flag for enabling the batch edit for the column
					$terms_col ['sortable']			= true;
					$terms_col ['resizable']		= true;
					$terms_col ['frozen']			= false;
					$terms_col ['allow_showhide']	= true;
					$terms_col ['exportable']		= true; //default true. flag for enabling the column in export
					$terms_col ['searchable']		= true;
					$terms_col ['wordWrap']			= false; //For disabling word-wrap

					$terms_col ['category'] = ''; //for advanced search
					$terms_col ['placeholder'] = ''; //for advanced search

					if( $terms_count > 5 ) {
						$terms_col ['hidden'] = true;	
					} else {
						$last_position++;
						$terms_col ['position'] = $last_position;
					}

					$col_model [] = $terms_col;

					$terms_count++;
				}
			}

			$last_position++;
			$col_model [] = array(
								'src' => 'custom/edit_link',
								'data' => 'custom_edit_link',
								'name' => __('Edit', 'smart-manager-for-wp-e-commerce'),
								'key'  => __('Edit', 'smart-manager-for-wp-e-commerce'),
								'type' => 'text',
								'renderer'=> 'html',
								'frozen' => false,
								'sortable' => false,
								'exportable' => true,
								'searchable' => false,
								'editable' => false,
								'editor' => false,
								'batch_editable' => false,
								'hidden' => false,
								'allow_showhide' => true,
								'width' => 200,
								'position' => $last_position
							);

			if( !empty( $this->req_params['is_public'] ) ) {
				$last_position++;
				$col_model [] = array(
									'src' => 'custom/view_link',
									'data' => 'custom_view_link',
									'name' => __('View', 'smart-manager-for-wp-e-commerce'),
									'key' => __('View', 'smart-manager-for-wp-e-commerce'),
									'type' => 'text',
									'renderer'=> 'html',
									'frozen' => false,
									'sortable' => false,
									'exportable' => true,
									'searchable' => false,
									'editable' => false,
									'editor' => false,
									'batch_editable' => false,
									'hidden' => false,
									'allow_showhide' => true,
									'width' => 200,
									'position' => $last_position
								);
			}

			//defining the default col model

			$this->default_store_model = array ();
			$this->default_store_model[$this->dashboard_key] = array( 
																	'display_name' => __(ucwords(str_replace('_', ' ', $this->dashboard_key)), 'smart-manager-for-wp-e-commerce'),
																	'tables' => array(
																					'posts' 				=> array(
																													'pkey' => 'ID',
																													'join_on' => '',
																													'where' => array( 
																																	'post_type' 	=> $this->post_type,
																																	'post_status' 	=> 'any' // will get all post_status except 'trash' and 'auto-draft'
																																	
																																	// 'post_status' 	=> array('publish', 'draft') // comma seperated for multiple values
																																	//For any other whereition specify, colname => colvalue
																																   )
																												),

																					'postmeta' 				=> array(
																													'pkey' => 'post_id',
																													'join_on' => 'postmeta.post_ID = posts.ID', // format current_table.pkey = joinning table.pkey
																													'where' => array( // provide a wp_query [meta_query]
																																// 'relation' => 'AND', // AND or OR
																																// 	array(
																																// 		'key'     => '',
																																// 		'value'   => '',
																																// 		'compare' => '',
																																// 	),
																																// 	array(
																																// 		'key'     => '',
																																// 		'value'   => 0,
																																// 		'type'    => '',
																																// 		'compare' => '',
																																// 	)
																																)
																												),

																					'term_relationships' 	=> array(
																													'pkey' => 'object_id',
																													'join_on' => 'term_relationships.object_id = posts.ID',
																													'where' => array()
																												),

																					'term_taxonomy' 		=> array(
																													'pkey' => 'term_taxonomy_id',
																													'join_on' => 'term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id',
																													'where' => array()
																												),

																					'terms' 				=> array(
																													'pkey' => 'term_id',
																													'join_on' => 'terms.term_id = term_taxonomy.term_id',
																													'where' => array(
																															// 'relation' => 'AND', // AND or OR
																															// array(
																															// 	'taxonomy' => '',
																															// 	'field'    => '',
																															// 	'terms'    => ''
																															// ),
																														)
																												)

																					), 
																	'columns' => $col_model,
																	'sort_params' 	=> array ( //WP_Query array structure
																							'orderby' => 'ID', //multiple list separated by space
																							'order' => 'DESC',
																							'default' => true ),

																	// 'sort_params' 		=> array( 'post_parent' => 'ASC', 'ID' => 'DESC' ),
																	'per_page_limit' 	=> '', // blank, 0, -1 all values refer to infinite scroll
																	'treegrid'			=> false // flag for setting the treegrid
										);

			//Filter to modify the default dashboard model
			$this->default_store_model = apply_filters('sm_default_dashboard_model', $this->default_store_model);
		}

		//Function to handle the user specific column model mapping to store model
		public function map_column_to_store_model( $store_model = array(), $column_model_transient = array() ) {
			if( !empty( $store_model['columns'] ) ) {

				$enabled_cols = $enabled_cols_position_blank = $disabled_cols = array();

				$column_transient = ( ! empty( $column_model_transient['columns'] ) ) ? $column_model_transient['columns'] : array();
				$column_transient_formatted = array();
				if( ! empty( $column_transient ) ){
					array_walk(
						$column_transient,
						function ( $col_obj, $col ) use( &$column_transient_formatted ) {
							$column_transient_formatted[ strtolower( $col ) ] = $col_obj;
						}
					);
				}

				foreach( $store_model['columns'] as $key => $col ) {

					$col_data = ( ! empty( $col['data'] ) ) ? strtolower( $col['data'] ) : ''; //did if the columns are stored as uppercase

					$store_model['columns'][$key]['width'] = ( !empty( $store_model['columns'][$key]['width'] ) ) ? $store_model['columns'][$key]['width'] : '';
					$store_model['columns'][$key]['position'] = ( !empty( $store_model['columns'][$key]['position'] ) ) ? $store_model['columns'][$key]['position'] : '';

					if( !empty( $col_data ) && !empty( $column_transient_formatted[ $col_data ] ) ) {
						$store_model['columns'][$key]['hidden'] = false; 
						$store_model['columns'][$key]['width'] = ( !empty( $column_transient_formatted[ $col_data ]['width'] ) ? $column_transient_formatted[ $col_data ]['width'] : $store_model['columns'][$key]['width'] );
						$store_model['columns'][$key]['position'] = ( !empty( $column_transient_formatted[ $col_data ]['position'] ) ? $column_transient_formatted[ $col_data ]['position'] : $store_model['columns'][$key]['position'] );

						if( !empty( $store_model['columns'][$key]['position'] ) ) {
							$enabled_cols[ (int)$store_model['columns'][$key]['position'] ] = $store_model['columns'][$key];
						} else {
							$enabled_cols_position_blank[] = $store_model['columns'][$key];
						}
						
					} else {
						$store_model['columns'][$key]['hidden'] = true;
						$disabled_cols[] = $store_model['columns'][$key];
					}
				}

				usort($enabled_cols, function ($item1, $item2) {
				    if ($item1['position'] == $item2['position']) return 0;
				    return $item1['position'] < $item2['position'] ? -1 : 1;
				});

				$store_model['columns'] = array_merge( $enabled_cols, $enabled_cols_position_blank, $disabled_cols );
			}			

			$store_model['sort_params'] = ( !empty( $store_model['sort_params'] ) ) ? $store_model['sort_params'] : array();
			$store_model['sort_params'] = ( !empty( $column_model_transient['sort_params'] ) ? $column_model_transient['sort_params'] : $store_model['sort_params'] );

			$store_model = apply_filters( 'sm_map_column_state_to_store_model', $store_model, $column_model_transient );

			return $store_model;
		}

		//Function to get the dashboard model
		public function get_dashboard_model( $return_store_model = false ) {

			global $wpdb, $current_user;

			$col_model = array();
			$old_col_model = array();

			$search_params = array();
			$column_model_transient = get_user_meta(get_current_user_id(), 'sa_sm_'.$this->dashboard_key, true);

			// Code for handling views
			if( ( defined('SMPRO') && true === SMPRO ) && ! empty( $this->req_params['is_view'] ) && ! empty( $this->req_params['active_view'] ) ) {
				if( class_exists( 'Smart_Manager_Pro_Views' ) ) {
					$view_obj = Smart_Manager_Pro_Views::get_instance();
					if( is_callable( array( $view_obj, 'get' ) ) ){
						$view_slug = $this->req_params['active_view'];
						$view_data = $view_obj->get($view_slug);
						if( ! empty( $view_data ) ) {
							$this->dashboard_key = $view_data['post_type'];
							$column_model_transient = get_user_meta(get_current_user_id(), 'sa_sm_'.$view_slug, true);
							$column_model_transient = json_decode( $view_data['params'], true );
							if( !empty( $column_model_transient['search_params'] ) ) {
								if( ! empty( $column_model_transient['search_params']['isAdvanceSearch'] ) ) { // For advanced search
									if( ! empty( $column_model_transient['search_params']['params'] ) && is_array( $column_model_transient['search_params']['params'] ) ) {
										

										// code forporting from old structure
										$search_query = $column_model_transient['search_params']['params'];
										if( empty( $search_query[0]['condition'] ) ){

											$rule_groups = array();
											$search_operators = array_flip( $this->advance_search_operators );

											foreach( $search_query as $query ) {

												if( empty( $query ) || ! is_array( $query ) ) {
													continue;
												}

												$rules = array();

												// iterate over each rule
												foreach( $query as $rule ) {
													$rules[] = array(
																	'type' => $rule['table_name'] .'.'. $rule['col_name'],
																	'operator' => strtolower( ( ! empty( $search_operators[ $rule['operator'] ] ) ) ? $search_operators[ $rule['operator'] ] : $rule['operator'] ),
																	'value' => $rule['value']
													);
												}

												$rule_groups[] = array( 'condition' => "AND", 'rules' => $rules );
											}

											$column_model_transient['search_params']['params'] = array( array( 'condition' => 'OR', 'rules' => $rule_groups ) );

											// code to upate the view new structure at db level
											$result = $wpdb->query( // phpcs:ignore
												$wpdb->prepare( // phpcs:ignore
													"UPDATE {$wpdb->prefix}sm_views
																		SET params = %s
																		WHERE slug = %s",
													json_encode( $column_model_transient ),
													$view_slug
												)
											);
										}
									}
								}
								$search_params = $column_model_transient['search_params'];
							}
						}
					}
				}
			}

			// Load from cache
			$store_model_transient = get_transient( 'sa_sm_'.$this->dashboard_key );

			if( ! empty( $store_model_transient ) && !is_array( $store_model_transient ) ) {
				$store_model_transient = json_decode( $store_model_transient, true );
			}

			// Code to move the column transients at user meta level
			// since v5.0.0
			if( empty( $column_model_transient ) ) {
				$key = 'sm_beta_'.$current_user->user_email.'_'.$this->dashboard_key;
				$column_model_transient  = get_option( $key );
				if( ! empty( $column_model_transient ) ) {
					update_user_meta(get_current_user_id(), 'sa_sm_'.$this->dashboard_key, $column_model_transient);
					delete_option( $key );
				}
			}

			if( empty( $column_model_transient ) ) { //for getting the old structure
				$column_model_transient = get_transient( 'sa_sm_'.$current_user->user_email.'_'.$this->dashboard_key );

				if( !empty( $column_model_transient ) ) {
					delete_transient( 'sa_sm_'.$current_user->user_email.'_'.$this->dashboard_key );
				}
			}

			if( !empty( $column_model_transient ) && !empty( $column_model_transient['tables'] ) ) { //for porting the old structure

				$saved_col_model = $column_model_transient;
				$column_model_transient = sa_sm_generate_column_state( $saved_col_model );

				if( empty( $store_model_transient ) ) {
					$store_model_transient = $saved_col_model;
				}
			}

			//Check if upgrading from old mapping
			if( false !== $store_model_transient ) {
				if( empty( $store_model_transient['columns'][0]['data'] ) || false === get_option( '_sm_update_414' ) || false === get_option( '_sm_update_419'.'_'.$this->dashboard_key ) ) {

					if( ! empty( $store_model_transient['columns'] ) ) {
						foreach( $store_model_transient['columns'] as $col ) {
							$old_col_model[ $col['src'] ] = $col;
						}
					}

					delete_transient( 'sa_sm_'.$this->dashboard_key );

					if( false === get_option( '_sm_update_414' ) ) {
						update_option( '_sm_update_414', 1 );
					}

					if( false === get_option( '_sm_update_419'.'_'.$this->dashboard_key ) ) {
						update_option( '_sm_update_419'.'_'.$this->dashboard_key, 1 );
					}
				}

				if( false === get_option( '_sm_update_411' ) ) { //Code for handling mapping changes in v4.1.1
					foreach( $store_model_transient['columns'] as $key => $col ) {
						if( $this->dashboard_key == 'user' && !empty( $col['col_name'] ) && $col['col_name'] == 'wp_capabilities' ) {
							$store_model_transient['columns'][$key]['col_name'] = $wpdb->prefix.'capabilities';
						}

						if( $this->dashboard_key == 'product' && !empty( $col['col_name'] ) && $col['col_name'] == 'post_name' ) {
							$store_model_transient['columns'][$key]['key'] = $store_model_transient['columns'][$key]['name_display'] = __('Slug', 'smart-manager-for-wp-e-commerce');
						}

						if( !empty( $col['col_name'] ) && $col['col_name'] == 'post_excerpt' ) {
							$store_model_transient['columns'][$key]['type'] = $store_model_transient['columns'][$key]['editor'] = 'sm.longstring';
						}
					}

					if( !empty( $store_model_transient['sort_params'] ) ) {
						if( $store_model_transient['sort_params']['orderby'] == 'ID' && $store_model_transient['sort_params']['order'] == 'DESC' ) {
							$store_model_transient['sort_params']['default'] = true;
						}
					}

					delete_transient( 'sa_sm_'.$this->dashboard_key );
					update_option( '_sm_update_411', 1 );
				}

				if( false === get_option( '_sm_update_415' ) ) { //Code for handling mapping changes in v4.1.1
					
					$add_view_col = true;
					foreach( $store_model_transient['columns'] as $key => $col ) {
						if( $this->dashboard_key == 'product' && !empty( $col['col_name'] ) && $col['col_name'] == 'product_shop_url' ) {
							unset( $store_model_transient['columns'][$key] );
							$store_model_transient['columns'] = array_values($store_model_transient['columns']);
						}
						
						if( !empty( $col['data'] ) && $col['data'] == 'custom_view_link' ) {
							$add_view_col = false;
						}
					}

					if( !empty( $add_view_col ) ) {

						$link_type = ( !empty( $this->req_params['is_public'] ) ) ? 'view' : 'edit'; 

						$index = sizeof( $store_model_transient['columns'] );

						$store_model_transient['columns'][$index] = array(
																		'src' => 'custom/link',
																		'data' => 'custom_'. $link_type .'_link',
																		'name' => ucwords($link_type),
																		'type' => 'text',
																		'renderer'=> 'html',
																		'frozen' => false,
																		'sortable' => false,
																		'exportable' => true,
																		'searchable' => false,
																		'editable' => false,
																		'batch_editable' => false,
																		'hidden' => true,
																		'allow_showhide' => true
																	);
					}

					delete_transient( 'sa_sm_'.$this->dashboard_key );
					update_option( '_sm_update_415', 1 );
				}

				if( false === get_option( '_sm_update_425' ) ) { //Code for handling mapping changes in v4.2.5
					if( $this->dashboard_key == 'product' ) {
						foreach( $store_model_transient['columns'] as $key => $col ) {
							if( !empty( $col['data'] ) && $col['data'] == 'custom_product_attributes' ) {
								$store_model_transient['columns'][$key]['allow_showhide'] = true;
								$store_model_transient['columns'][$key]['exportable'] = true;
							}

							if( !empty( $col['data'] ) && $col['data'] == 'postmeta_meta_key__product_attributes_meta_value__product_attributes' ) {
								$store_model_transient['columns'][$key]['hidden']= true;
								$store_model_transient['columns'][$key]['allow_showhide']= false;
								$store_model_transient['columns'][$key]['exportable']= false;
							}
						}
						delete_transient( 'sa_sm_'.$this->dashboard_key );
						update_option( '_sm_update_425', 1 );
					}
				}

				if( false === get_option( '_sm_update_426' ) ) { //Code for handling mapping changes in v4.2.6
					if( $this->dashboard_key == 'shop_subscription' ) {
						delete_transient( 'sa_sm_'.$this->dashboard_key );
						update_option( '_sm_update_426', 1 );
					}
				}

				if( false === get_option( '_sm_update_427' ) ) { //Code for handling mapping changes in v4.2.7
					if( $this->dashboard_key != 'user' ) {
						foreach( $store_model_transient['columns'] as $key => $col ) {
							if( !empty( $col['col_name'] ) && $col['col_name'] == 'post_status' && empty( $col['colorCodes'] ) ) {
		
								if( $this->dashboard_key == 'shop_order' ) {

									$color_codes = array( 'green' => array( 'wc-completed', 'wc-processing' ),
															'red' => array( 'wc-cancelled', 'wc-failed', 'wc-refunded' ),
															'orange' => array( 'wc-on-hold', 'wc-pending' ) );

								} else if( $this->dashboard_key == 'shop_subscription' ) {

									$color_codes = array( 'green' => array( 'wc-active' ),
															'red' => array( 'wc-expired', 'wc-cancelled' ),
															'orange' => array( 'wc-on-hold', 'wc-pending' ),
															'blue' => array( 'wc-switched', 'wc-pending-cancel' ) );

								} else {
									$color_codes = array();
								}
								
								$store_model_transient['columns'][$key]['colorCodes'] = $color_codes;

								delete_transient( 'sa_sm_'.$this->dashboard_key );
								update_option( '_sm_update_427', 1 );
							}

							if( $this->dashboard_key == 'product' && !empty( $col['col_name'] ) && ( in_array($col['col_name'], array( '_stock_status', '_backorders' )) ) && empty( $col['colorCodes'] ) ) {
								if( $col['col_name'] == '_stock_status' ) {
									$color_codes = array( 'green' => array( 'instock' ),
															'red' => array( 'outofstock' ),
															'blue' => array( 'onbackorder' ) );
								} else {
									$color_codes = array( 'green' => array( 'yes', 'notify' ),
															'red' => array( 'no' ),
															'blue' => array() );
								}

								$store_model_transient['columns'][$key]['colorCodes'] = $color_codes;

								delete_transient( 'sa_sm_'.$this->dashboard_key );
								update_option( '_sm_update_427', 1 );

							}
						}
					}
				}

				if( false === get_option( '_sm_update_4210'.'_'.$this->dashboard_key ) ) { //Code for handling mapping changes in v4.2.10
					if( $this->dashboard_key == 'shop_order' ) {
						$custom_columns = array( 'shipping_method', 'coupons_used', 'line_items', 'details' );
						foreach( $store_model_transient['columns'] as $key => $col ) {
							$data = ( !empty( $col['data'] ) ) ? substr( $col['data'], 7 ) : '';
							if( !empty( $data ) && in_array( $data , $custom_columns ) ) {
								$store_model_transient['columns'][$key]['editor'] = false;
							}
						}

						delete_transient( 'sa_sm_'.$this->dashboard_key );
					} else if( $this->dashboard_key == 'product' ) {
						delete_transient( 'sa_sm_'.$this->dashboard_key );
					} else if( $this->dashboard_key == 'wc_booking' ) {
						delete_transient( 'sa_sm_'.$this->dashboard_key );
					} else if( $this->dashboard_key == 'wc_membership_plan' ) {
						delete_transient( 'sa_sm_'.$this->dashboard_key );
					} else if( $this->dashboard_key == 'wc_user_membership' ) {
						delete_transient( 'sa_sm_'.$this->dashboard_key );
					}
					update_option( '_sm_update_4210'.'_'.$this->dashboard_key, 1 );
				}

				// Have to delete transient of those dashboard which are having atleaset on zero_one checkbox model because checkedtemplate & uncheckedtemplate logic is swapped in version 4.2.11
				if( false === get_option( '_sm_update_4211'.'_'.$this->dashboard_key ) ) { //Code for handling mapping changes in v4.2.11
					$is_checkbox = false;
					if ( ! empty( $store_model_transient['columns'] ) ) {
						foreach( $store_model_transient['columns'] as $key => $col ) {
							if ( ! empty( $col['type'] ) && 'checkbox' === $col['type'] && isset( $col['checkedTemplate'] ) && absint( $col['checkedTemplate'] ) === 0 ) {
								$is_checkbox = true;
								break;
							}
						}
					}
					if ( true === $is_checkbox ) {
						delete_transient( 'sa_sm_'.$this->dashboard_key );
					}
					update_option( '_sm_update_4211'.'_'.$this->dashboard_key, 1, 'no' );
				}

				if( false === get_option( '_sm_update_44'.'_'.$this->dashboard_key ) ) {
					delete_transient( 'sa_sm_'.$this->dashboard_key );
					update_option( '_sm_update_44'.'_'.$this->dashboard_key, 1, 'no' );
				}

				if( false === get_option( '_sm_update_442_users' ) ) {
					delete_transient( 'sa_sm_users' );
					update_option( '_sm_update_442_users', 1, 'no' );
				}

				if( false === get_option( '_sm_update_443_product' ) ) {
					delete_transient( 'sa_sm_product' );
					update_option( '_sm_update_443_product', 1, 'no' );
				}

				if( false === get_option( '_sm_update_461'.'_'.$this->dashboard_key ) ) { //Code for handling date cols mapping changes in v4.6.1
					$date_cols = array( 'posts_post_date', 'posts_post_date_gmt', 'posts_post_modified', 'posts_post_modified_gmt' );
					foreach( $store_model_transient['columns'] as $key => $col ) {
						$data = ( !empty( $col['data'] ) ) ? $col['data'] : '';
						if( !empty( $data ) && in_array( $data , $date_cols ) ) {
							$display_name = $this->dashboard_title . ' '. ( ( strpos( $data, 'modified' ) !== false ) ? 'Modified' : 'Created' ) . ' Date' . ( ( strpos( $data, 'gmt' ) !== false ) ? ' GMT' : '' );
							$store_model_transient['columns'][$key]['searchable'] = true;
							$store_model_transient['columns'][$key]['name'] = $display_name;
							$store_model_transient['columns'][$key]['key'] = $store_model_transient['columns'][$key]['name'];
						}
					}
					delete_transient( 'sa_sm_'.$this->dashboard_key );
					update_option( '_sm_update_461'.'_'.$this->dashboard_key, 1 );
				}

				if( false === get_option( '_sm_update_520'.'_'.$this->dashboard_key ) ) {
					delete_transient( 'sa_sm_'.$this->dashboard_key );
					update_option( '_sm_update_520'.'_'.$this->dashboard_key, 1, 'no' );
				}

				if( false === get_option( '_sm_update_530_shop_order' ) ) {
					delete_transient( 'sa_sm_shop_order' );
					update_option( '_sm_update_530_shop_order', 1, 'no' );
				}

				if( false === get_option( '_sm_update_5110_users' ) ) {
					delete_transient( 'sa_sm_users' );
					update_option( '_sm_update_5110_users', 1, 'no' );
				}

				if( false === get_option( '_sm_update_5120_users' ) ) {
					delete_transient( 'sa_sm_users' );
					update_option( '_sm_update_5120_users', 1, 'no' );
				}

				if( false === get_option( '_sm_update_5120_product' ) ) {
					delete_transient( 'sa_sm_product' );
					update_option( '_sm_update_5120_product', 1, 'no' );
				}

				if( false === get_option( '_sm_update_5140'.'_'.$this->dashboard_key ) ) {
					delete_transient( 'sa_sm_'.$this->dashboard_key );
					update_option( '_sm_update_5140'.'_'.$this->dashboard_key, 1, 'no' );
				}

				if( false === get_option( '_sm_update_5160'.'_'.$this->dashboard_key ) ) {
					delete_transient( 'sa_sm_'.$this->dashboard_key );
					update_option( '_sm_update_5160'.'_'.$this->dashboard_key, 1, 'no' );
				}

				if( false === get_option( '_sm_update_5180'.'_'.$this->dashboard_key ) ) {
					delete_transient( 'sa_sm_'.$this->dashboard_key );
					update_option( '_sm_update_5180'.'_'.$this->dashboard_key, 1, 'no' );
				}

				if( false === get_option( '_sm_update_5190'.'_'.$this->dashboard_key ) ) {
					delete_transient( 'sa_sm_'.$this->dashboard_key );
					$store_model_transient = false;
					update_option( '_sm_update_5190'.'_'.$this->dashboard_key, 1, 'no' );
				}

				if( false === get_option( '_sm_update_5191'.'_'.$this->dashboard_key ) ) {
					delete_transient( 'sa_sm_'.$this->dashboard_key );
					$store_model_transient = false;
					update_option( '_sm_update_5191'.'_'.$this->dashboard_key, 1, 'no' );
				}

				if( false === get_option( '_sm_update_5250_product' ) ) {
					delete_transient( 'sa_sm_product' );
					update_option( '_sm_update_5250_product', 1, 'no' );
				}

				if( false === get_option( '_sm_update_5260_product' ) ) {
					delete_transient( 'sa_sm_product' );
					update_option( '_sm_update_5260_product', 1, 'no' );
				}
				if( false === get_option( '_sm_update_600_user' ) ) {
					delete_transient( 'sa_sm_user' );
					$store_model_transient = false;
					update_option( '_sm_update_600_user', 1, 'no' );
				}
			}

			$store_model = $store_model_transient;

			// Valid cache not found
			if ( false === $store_model ) {
				$this->get_default_store_model();
				$store_model = ( !empty( $this->default_store_model[$this->dashboard_key] ) ) ? $this->default_store_model[$this->dashboard_key] : array();

				foreach ($store_model['columns'] as $key => $value) {
					$store_model['columns'][$key]['save_state'] = true;
				}
			}

			//Filter to modify the dashboard model
			$store_model = apply_filters('sm_dashboard_model', $store_model, $store_model_transient);
			
			//Code for porting to new mapping
			if( !empty($old_col_model) ) {

				$new_col_model = $store_model['columns'];

				foreach( $new_col_model as $index => $new_col ) {
					if( !empty( $new_col['src'] ) && !empty( $old_col_model[$new_col['src']] ) ) {
						$new_col_model[$index]['width'] = 80;

						$new_col_model[$index]['width'] = ( !empty( $old_col_model[$new_col['src']]['width'] ) ) ? $old_col_model[$new_col['src']]['width'] : $new_col_model[$index]['width'];
						$new_col_model[$index]['hidden'] = ( !empty( $old_col_model[$new_col['src']]['hidden'] ) ) ? $old_col_model[$new_col['src']]['hidden'] : $new_col_model[$index]['hidden'];

						//Code for posting the column position
						if( !isset( $old_col_model[$new_col['src']]['position'] ) && isset( $new_col_model[$index]['position'] ) ) { //unset the position if not there
							unset( $new_col_model[$index]['position'] );
						} else if( isset( $old_col_model[$new_col['src']]['position'] ) ) {
							$new_col_model[$index]['position'] = $old_col_model[$new_col['src']]['position'];
						}
					}
				}

				$store_model['columns'] = $new_col_model;
			}

			//code to show/hide columns as per stored transient only if atleast one column is enabled
			if( !empty( $column_model_transient ) && ! empty( $column_model_transient['columns'] ) ) {
				$store_model = $this->map_column_to_store_model( $store_model, $column_model_transient );
			} else { //for setting the custom column dashboard transient for the user
				$column_model_transient = sa_sm_generate_column_state( $store_model );
			}

			//Code for re-arranging the columns in the final column model based on the set position
			$final_column_model = (!empty($store_model['columns'])) ? $final_column_model = &$store_model['columns'] : '';

			if (!empty($final_column_model)) {

				$priority_columns = array();

				foreach ($final_column_model as $key => &$column_model) {

					//checking for multilist datatype
					if (!empty($column_model['type']) && $column_model['type'] == 'sm.multilist') {

						$col_exploded = (!empty($column_model['src'])) ? explode("/", $column_model['src']) : array();
						
						if ( sizeof($col_exploded) > 2) {
							$col_meta = explode("=",$col_exploded[1]);
							$col_nm = $col_meta[1];
						} else {
							$col_nm = $col_exploded[1];
						}

						$column_model['values'] = (!empty($this->terms_val_parent[$col_nm])) ? $this->terms_val_parent[$col_nm] : $column_model['values'];
					}

					if( !isset( $column_model['position']) ) continue;
						
					$priority_columns[] = $column_model;
					unset($final_column_model[$key]);
				}

				if (!empty($priority_columns)) {

					usort( $priority_columns, "sm_position_compare" ); //code for sorting as per the position

					$final_column_model = array_values($final_column_model);

					foreach ($final_column_model as $col_model) {
						$priority_columns[] = $col_model;
					}

					ksort($priority_columns);
					$store_model['columns'] = $priority_columns;
				}
			}

			// Valid cache not found
			if ( false === get_transient( 'sa_sm_'.$this->dashboard_key ) ) {
				set_transient( 'sa_sm_'.$this->dashboard_key, wp_json_encode( $store_model ), WEEK_IN_SECONDS );
			}

			if ( false === get_user_meta(get_current_user_id(), 'sa_sm_'.$this->dashboard_key, true) ) {
				update_user_meta(get_current_user_id(), 'sa_sm_'.$this->dashboard_key, $column_model_transient);
			}

			// Code to handle display of 'trash' value for 'post_status' -- not to be saved in transient
			if( ! empty( $this->is_show_trash_records() ) && 'user' !== $this->dashboard_key ) {
				foreach( $store_model['columns'] as $key => $col ) {
					if( ! empty( $col['col_name'] ) && 'post_status' === $col['col_name'] ){
						$store_model['columns'][$key]['values']['trash'] = __( 'Trash', 'smart-manager-for-wp-e-commerce' );
						$store_model['columns'][$key]['selectOptions']['trash'] = __( 'Trash', 'smart-manager-for-wp-e-commerce' );
						$store_model['columns'][$key]['search_values'][] = array( 'key' => 'trash', 'value' => __( 'Trash', 'smart-manager-for-wp-e-commerce' ) );

						// Code for handling color code for 'trash' if enabled
						if( ! empty( $store_model['columns'][$key]['colorCodes'] ) ){
							if( ! is_array( $store_model['columns'][$key]['colorCodes']['red'] ) ){
								$store_model['columns'][$key]['colorCodes']['red'] = array();
							}
							$store_model['columns'][$key]['colorCodes']['red'][] = 'trash';
						}
						break;
					}
				}
			}

			do_action('sm_dashboard_model_saved');

			if( ! empty( $search_params ) ) {
				$store_model['search_params'] = $search_params;
			}

			if( !$return_store_model ) {
				wp_send_json( $store_model );
			} else {
				return $store_model;
			}
			
		}

		public function process_search_cond($params = array()) {

			global $wpdb, $wp_version;


			if( empty($params) || empty($params['search_query']) ) {
				return;
			}

			$rule_groups = ( ! empty( $params['search_query'] ) ) ? $params['search_query'][0]['rules'] : array();

			if( empty( $rule_groups ) ) {
				return;
			}

			$wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp"); // query to reset advanced search temp table

            $advanced_search_query = array();
            $i = 0;

			$search_cols_type = ( ! empty( $params['search_cols_type'] ) ) ? $params['search_cols_type'] : array();

            foreach ( $rule_groups as $rule_group ) {

                if ( ! empty( $rule_group )) {

                		// START FROM HERE

                        $advanced_search_query[$i] = array();
                        $advanced_search_query[$i]['cond_posts'] = '';
                        $advanced_search_query[$i]['cond_postmeta'] = '';
                        $advanced_search_query[$i]['cond_terms'] = '';

                        $advanced_search_query[$i]['cond_postmeta_col_name'] = '';
                        $advanced_search_query[$i]['cond_postmeta_col_value'] = '';
                        $advanced_search_query[$i]['cond_postmeta_operator'] = '';

                        $advanced_search_query[$i]['cond_terms_col_name'] = '';
                        $advanced_search_query[$i]['cond_terms_col_value'] = '';
                        $advanced_search_query[$i]['cond_terms_operator'] = '';

                        $search_value_is_array = 0; //flag for array of search_values

						$rule_group = apply_filters('sm_before_search_string_process', $rule_group);
						$rules = ( ! empty( $rule_group['rules'] ) ) ? $rule_group['rules'] : array();

                        foreach( $rules as $rule ) {

							if( ! empty( $rule['type'] ) ) {
								$field = explode( '.', $rule['type'] );
								$rule['table_name'] = ( ! empty( $field[0] ) ) ? $field[0] : '';
								$rule['col_name'] = ( ! empty( $field[1] ) ) ? $field[1] : '';
							}

                            $search_col = (!empty($rule['col_name'])) ? $rule['col_name'] : '';
							$search_operator = (!empty($rule['operator'])) ? $rule['operator'] : '';
							$search_operator = ( ! empty( $this->advance_search_operators[$search_operator] ) ) ? $this->advance_search_operators[$search_operator] : $search_operator;
                            $search_data_type = ( ! empty( $search_cols_type[$rule['type']] ) ) ? $search_cols_type[$rule['type']] : 'text';
                            $search_value = (isset($rule['value']) && $rule['value'] != "''") ? $rule['value'] : ( ( in_array( $search_data_type, array( "number", "numeric" ) ) ) ? "''" : '');

                            $search_params = array('search_string' => $rule,
													'search_col' => $search_col,
													'search_operator' => $search_operator, 
													'search_data_type' => $search_data_type, 
													'search_value' => $search_value,
													'SM_IS_WOO30' => (!empty($params['SM_IS_WOO30'])) ? $params['SM_IS_WOO30'] : '');

                            if( !empty( $params['data_col_params'] ) ) {
                            	$search_value = ( in_array($search_col, $params['data_col_params']['data_cols_timestamp']) ) ? strtotime($search_value) : $search_value;
                            }

                            if (!empty($rule['table_name']) && $rule['table_name'] == $wpdb->prefix.'posts') {

                            	$search_col = apply_filters('sm_search_format_query_posts_col_name', $search_col, $search_params);
                                $search_value = apply_filters('sm_search_format_query_posts_col_value', $search_value, $search_params);

                                if( in_array( $search_data_type, array( "number", "numeric" ) ) ) {
									$val = ( empty( $search_value ) && '0' != $search_value ) ? "''" : $search_value;
									$posts_cond = "( ".$rule['table_name'].".".$search_col . " ". $search_operator ." " . $val ." )";
									// if( 0 == $rule['value'] ) {
									// 	$posts_cond = "( ". $posts_cond ." OR ( ".$rule['table_name'].".".$search_col . " ". $search_operator ." 0 ) )";
									// } 
                                } else if ( $search_data_type == "date" || $search_data_type == "sm.datetime" ) {
                                	$posts_cond = "( ".$rule['table_name'].".".$search_col . " ". $search_operator ." '" . $search_value ."' AND ". $rule['table_name'] .".". $search_col ." NOT IN ('0', '1970-01-01 00:00:00', '1970-01-01', '', 0) )";
                                } else {
									if ($search_operator == 'is') {
                                        $posts_cond = "( ".$rule['table_name'].".".$search_col . " LIKE '" . $search_value . "' )";
                                    } else if ($search_operator == 'is not') {
                                        $posts_cond = "( ".$rule['table_name'].".".$search_col . " NOT LIKE '" . $search_value . "' )";
                                    } else {
                                        $posts_cond = "( ".$rule['table_name'].".".$search_col . " ". $search_operator ." '%" . $search_value . "%' )";
                                    }
                                }

                                $posts_cond = apply_filters('sm_search_posts_cond', $posts_cond, $search_params);

                                $advanced_search_query[$i]['cond_posts'] .= $posts_cond ." && ";

                            } else if (!empty($rule['table_name']) && $rule['table_name'] == $wpdb->prefix.'postmeta') {

                                $advanced_search_query[$i]['cond_postmeta_col_name'] .= $search_col;
                                $advanced_search_query[$i]['cond_postmeta_col_value'] .= $search_value;

                                $search_col = apply_filters('sm_search_format_query_postmeta_col_name', $search_col, $search_params);
                                $search_value = apply_filters('sm_search_format_query_postmeta_col_value', $search_value, $search_params);

                                if( in_array( $search_data_type, array( "number", "numeric" ) ) ) {
									$val = ( empty( $search_value ) && '0' != $search_value ) ? "''" : $search_value;
                                    
									//Condition for exact matching of '0' numeric values
                                    if( '0' == $search_value && ( '=' === $search_operator || '!=' === $search_operator ) ) {
										$val = "'". $val . "'";
									}
									
									$postmeta_cond = "( ". $rule['table_name'].".meta_key LIKE '". $search_col . "' AND ". $rule['table_name'] .".meta_value ". $search_operator ." " . $val . " )";
									// if( 0 == $rule['value'] ) {
									// 	$postmeta_cond = "( ". $postmeta_cond ." OR ( " .$rule['table_name']. ".meta_key LIKE '". $search_col . "' AND ". $rule['table_name'] .".meta_value ". $search_operator ." '0' ) )";
									// }
									$advanced_search_query[$i]['cond_postmeta_operator'] .= $search_operator;
                                } else if( $search_data_type == "date" || $search_data_type == "sm.datetime" ) {
                                	$postmeta_cond = "( ". $rule['table_name'].".meta_key LIKE '". $search_col . "' AND ". $rule['table_name'] .".meta_value ". $search_operator ." '" . $search_value . "' AND ". $rule['table_name'] .".meta_value NOT IN ('0', '1970-01-01 00:00:00', '1970-01-01', '', 0) )";
                                    $advanced_search_query[$i]['cond_postmeta_operator'] .= $search_operator;
                                } else {
                                    if ($search_operator == 'is') {
                                        $advanced_search_query[$i]['cond_postmeta_operator'] .= 'LIKE';
                                        $postmeta_cond = "( ". $rule['table_name'].".meta_key LIKE '". $search_col . "' AND ". $rule['table_name'] .".meta_value LIKE '" . $search_value . "'" . " )";
                                    } else if ($search_operator == 'is not') {

                                        $advanced_search_query[$i]['cond_postmeta_operator'] .= 'NOT LIKE';
                                        $postmeta_cond = "( ". $rule['table_name'].".meta_key LIKE '". $search_col . "' AND ". $rule['table_name'] .".meta_value NOT LIKE '" . $search_value . "'" . " )";

                                    } else {

                                        $advanced_search_query[$i]['cond_postmeta_operator'] .= $search_operator;
                                        $postmeta_cond = "( ". $rule['table_name'].".meta_key LIKE '". $search_col . "' AND ". $rule['table_name'] .".meta_value ". $search_operator ." '%" . $search_value . "%'" . " )";
                                    }
                                    
                                }

                                $postmeta_cond = apply_filters('sm_search_postmeta_cond', $postmeta_cond, $search_params);

                                if( ( empty( $rule['value'] ) && '0' !== $rule['value'] ) || $rule['value'] == "''" ) {
                                	$empty_search_value = ( $search_operator == 'is' || $search_operator == '=' ) ? 'IS NULL' : 'IS NOT NULL';
									$postmeta_cond = "( ". $postmeta_cond ." OR ( ". $rule['table_name'] .".meta_key LIKE '". $search_col . "' AND ". $rule['table_name'] .".meta_value " . $empty_search_value ." )
													OR ( ". $rule['table_name'] .".post_id ". ( ( $search_operator == 'is' || $search_operator == '=' ) ? 'NOT IN' : 'IN' ) ." ( SELECT DISTINCT p.id 
																																															FROM {$wpdb->prefix}postmeta as pm
																																																JOIN {$wpdb->prefix}posts as p
																																																	ON(p.id = pm.post_id
																																																		AND p.post_type IN ('". implode( "','", $params['post_type'] ) ."') )
																																															WHERE pm.meta_key = '". $search_col . "' ) ) )";
								}

                                $advanced_search_query[$i]['cond_postmeta'] .= $postmeta_cond ." && ";
                                $advanced_search_query[$i]['cond_postmeta_col_name'] .= " && ";
                                $advanced_search_query[$i]['cond_postmeta_col_value'] .= " && ";
                                $advanced_search_query[$i]['cond_postmeta_operator'] .= " && ";

                            } else if (!empty($rule['table_name']) && $rule['table_name'] == $wpdb->prefix.'terms') {

                                $advanced_search_query[$i]['cond_terms_col_name'] .= $search_col;
                                $advanced_search_query[$i]['cond_terms_col_value'] .= $search_value;

                                $search_col = apply_filters('sm_search_format_query_terms_col_name', $search_col, $search_params);
                                $search_value = apply_filters('sm_search_format_query_terms_col_value', $search_value, $search_params);

                                if ($search_operator == 'is') {
                                    if( $rule['value'] == "''" ) { //for handling empty search strings
                                        $terms_cond = "( ". $wpdb->prefix ."term_taxonomy.taxonomy NOT LIKE '". $search_col . "' AND ". $wpdb->prefix ."term_taxonomy.taxonomy NOT LIKE 'product_type' )";
                                        $advanced_search_query[$i]['cond_terms_operator'] .= 'NOT LIKE';
                                    } else {                                        
                                        $terms_cond = "( ". $wpdb->prefix ."term_taxonomy.taxonomy LIKE '". $search_col . "' AND ". $wpdb->prefix ."terms.slug LIKE '" . $search_value . "'" . " )";
                                        $advanced_search_query[$i]['cond_terms_operator'] .= 'LIKE';
                                            
                                    }
                                } else if ($search_operator == 'is not') {
                                    if( $rule['value'] == "''" ) { //for handling empty search strings
                                        $terms_cond = "( ". $wpdb->prefix ."term_taxonomy.taxonomy LIKE '". $search_col . "' )";
                                        $advanced_search_query[$i]['cond_terms_operator'] .= 'LIKE';
                                    } else {
                                    	$terms_cond = "( ". $wpdb->prefix ."term_taxonomy.taxonomy NOT LIKE '". $search_col . "' AND ". $wpdb->prefix ."terms.slug NOT LIKE '" . $search_value . "'" . " )";
                                        $advanced_search_query[$i]['cond_terms_operator'] .= 'NOT LIKE';
                                    }
                                } else {
                                    $terms_cond = "( ". $wpdb->prefix ."term_taxonomy.taxonomy LIKE '". $search_col . "' AND ". $wpdb->prefix ."terms.slug ". $search_operator ." '%" . $search_value . "%'" . " )";
                                    $advanced_search_query[$i]['cond_terms_operator'] .= $search_operator;
                                }

                                $terms_cond = apply_filters('sm_search_terms_cond', $terms_cond, $search_params);

                                $advanced_search_query[$i]['cond_terms'] .= $terms_cond ." && ";
                                $advanced_search_query[$i]['cond_terms_col_name'] .= " && ";
                                $advanced_search_query[$i]['cond_terms_col_value'] .= " && ";
                                $advanced_search_query[$i]['cond_terms_operator'] .= " && ";

                            }

                            $advanced_search_query[$i] = apply_filters('sm_search_query_formatted', $advanced_search_query[$i], $search_params);
                        }

                        $advanced_search_query[$i]['cond_posts'] = (!empty($advanced_search_query[$i]['cond_posts'])) ? substr( $advanced_search_query[$i]['cond_posts'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_postmeta'] = (!empty($advanced_search_query[$i]['cond_postmeta'])) ? substr( $advanced_search_query[$i]['cond_postmeta'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_terms'] = (!empty($advanced_search_query[$i]['cond_terms'])) ? substr( $advanced_search_query[$i]['cond_terms'], 0, -4 ) : '';

                        $advanced_search_query[$i]['cond_postmeta_col_name'] = (!empty($advanced_search_query[$i]['cond_postmeta_col_name'])) ? substr( $advanced_search_query[$i]['cond_postmeta_col_name'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_postmeta_col_value'] = (!empty($advanced_search_query[$i]['cond_postmeta_col_value'])) ? substr( $advanced_search_query[$i]['cond_postmeta_col_value'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_postmeta_operator'] = (!empty($advanced_search_query[$i]['cond_postmeta_operator'])) ? substr( $advanced_search_query[$i]['cond_postmeta_operator'], 0, -4 ) : '';

                        $advanced_search_query[$i]['cond_terms_col_name'] = (!empty($advanced_search_query[$i]['cond_terms_col_name'])) ? substr( $advanced_search_query[$i]['cond_terms_col_name'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_terms_col_value'] = (!empty($advanced_search_query[$i]['cond_terms_col_value'])) ? substr( $advanced_search_query[$i]['cond_terms_col_value'], 0, -4 ) : '';
                        $advanced_search_query[$i]['cond_terms_operator'] = (!empty($advanced_search_query[$i]['cond_terms_operator'])) ? substr( $advanced_search_query[$i]['cond_terms_operator'], 0, -4 ) : '';

                    }

                    $i++;
				}

                //Code for handling advanced search conditions
		        if (!empty($advanced_search_query)) {

		            $index_search_string = 1; // index to keep a track of flags in the advanced search temp 
		            $search_params = array();

		            foreach ($advanced_search_query as &$advanced_search_query_string) {

		                //Condn for terms

		                if (!empty($advanced_search_query_string['cond_terms'])) {

		                    $cond_terms_array = explode(" && ",$advanced_search_query_string['cond_terms']);

		                    $cond_terms_col_name = (!empty($advanced_search_query_string['cond_terms_col_name'])) ? explode(" && ",$advanced_search_query_string['cond_terms_col_name']) : '';
		                    $cond_terms_col_value = (!empty($advanced_search_query_string['cond_terms_col_value'])) ?  explode(" && ",$advanced_search_query_string['cond_terms_col_value']) : '';
		                    $cond_terms_operator = (!empty($advanced_search_query_string['cond_terms_operator'])) ?  explode(" && ",$advanced_search_query_string['cond_terms_operator']) : '';

		                    $cond_terms_post_ids = '';
		                    $cond_cat_post_ids = array(); // array for storing the cat post ids

		                    $index = 0;
		                    $terms_cat_search_taxonomy_ids = array();
		                    $terms_att_search_flag = 0;

		                    $query_terms_search_count_array = array();

		                    $terms_advanced_search_from = '';
		                    $terms_advanced_search_where = '';
		                    $result_terms_search = '';

		                    $product_visibility_visible_flag = 0;                    

		                    foreach ($cond_terms_array as $cond_terms) {

		                    	$search_params = array('cond_terms_col_name' => $cond_terms_col_name[$index],
		                    							'cond_terms_col_value' => $cond_terms_col_value[$index],
		                    							'cond_terms_operator' => $cond_terms_operator[$index],
		                    							'SM_IS_WOO30' => (!empty($params['SM_IS_WOO30'])) ? $params['SM_IS_WOO30'] : '',
		                    							'post_type' => (!empty($params['post_type'])) ? $params['post_type'] : array());

		                    	$cond_terms = apply_filters('sm_search_terms_condition_start', $cond_terms, $search_params);

		                        $query_advanced_search_taxonomy_id = "SELECT {$wpdb->prefix}term_taxonomy.term_taxonomy_id
		                                                              FROM {$wpdb->prefix}term_taxonomy
		                                                                JOIN {$wpdb->prefix}terms
		                                                                    ON ( {$wpdb->prefix}terms.term_id = {$wpdb->prefix}term_taxonomy.term_id)
		                                                              WHERE ".$cond_terms;
		                        $result_advanced_search_taxonomy_id = $wpdb->get_col ( $query_advanced_search_taxonomy_id );

		                        //Query to get the child taxonomy ids 
		                        $query_advanced_search_parent_id = "SELECT {$wpdb->prefix}term_taxonomy.term_taxonomy_id
		                                                            FROM {$wpdb->prefix}term_taxonomy
		                                                                JOIN {$wpdb->prefix}terms 
		                                                                ON ( {$wpdb->prefix}term_taxonomy.parent = {$wpdb->prefix}terms.term_id )    
		                                                            WHERE {$wpdb->prefix}terms.slug  = '". trim($cond_terms_col_value[$index]) ."'"; 

		                        $result_advanced_search_parent_id = $wpdb->get_col( $query_advanced_search_parent_id);

		                        if (!empty($result_advanced_search_taxonomy_id))  {

		                            $terms_search_result_flag = ( $index == (sizeof($cond_terms_array) - 1) ) ? ', '.$index_search_string : ', 0';
		                            $terms_advanced_search_select = "SELECT DISTINCT ".$wpdb->prefix."posts.id, ". $index_search_string;

		                            $search_params['terms_search_result_flag'] = $terms_search_result_flag;


		                            $result_taxonomy_ids = implode(",",$result_advanced_search_taxonomy_id);
		                            $result_taxonomy_ids .= (!empty($result_advanced_search_parent_id)) ? ','.implode(',',$result_advanced_search_parent_id) : ''; //condition added for displaying child taxonomies when searching for parent taxonomies

		                            $search_params['result_taxonomy_ids'] = $result_taxonomy_ids;

		                            $terms_advanced_search_from = "FROM {$wpdb->prefix}posts
		                                                            JOIN {$wpdb->prefix}term_relationships
		                                                                ON ({$wpdb->prefix}term_relationships.object_id = {$wpdb->prefix}posts.id
		                                                            		AND {$wpdb->prefix}posts.post_type IN ('". implode( "','", $params['post_type'] ) ."') )";

	                                $terms_advanced_search_where = "WHERE {$wpdb->prefix}term_relationships.term_taxonomy_id IN (". $result_taxonomy_ids .")";

	                                //Code for handling blank taxonomy search conditions
	                                if( !empty($search_params['cond_terms_operator']) && $search_params['cond_terms_operator'] == 'NOT LIKE' ) {

										$tt_ids_to_exclude = array();
									 	$taxonomy = apply_filters('sm_search_format_query_terms_col_name', $search_params['cond_terms_col_name'], $search_params);
	                                	if( ( $search_params['cond_terms_col_value'] == "''" || empty( $search_params['cond_terms_col_value'] ) ) ) {

	                                		if (version_compare ( $wp_version, '4.5', '>=' )) {
	                                			$tt_ids_to_exclude = get_terms( array(
																	 	   'taxonomy' => $taxonomy,
																	    	'fields' => 'tt_ids',
																	));	
	                                		} else {
	                                			$tt_ids_to_exclude = get_terms( $taxonomy, array(
																	    	'fields' => 'tt_ids',
																	));	
	                                		}
	                                		
	                                	} else {
	                                		$term_meta = get_term_by( 'slug', $search_params['cond_terms_col_value'], $taxonomy );
											if ( ! is_wp_error( $term_meta ) && ! empty( $term_meta->term_taxonomy_id ) ) {
												$tt_ids_to_exclude[] = $term_meta->term_taxonomy_id;
											}
	                                	}

										if( ! empty( $tt_ids_to_exclude ) ) {
											$terms_advanced_search_where .= " AND {$wpdb->prefix}posts.ID NOT IN ( SELECT object_id 
		        																			FROM {$wpdb->prefix}term_relationships
		        																			WHERE term_taxonomy_id IN (". implode(",", $tt_ids_to_exclude) .") )";
										}
									}

	                                $terms_advanced_search_select_old = $terms_advanced_search_select;
									$terms_advanced_search_select = apply_filters('sm_search_query_terms_select', $terms_advanced_search_select, $search_params);
									$terms_advanced_search_from	= apply_filters('sm_search_query_terms_from', $terms_advanced_search_from, $search_params);
									$terms_advanced_search_where	= apply_filters('sm_search_query_terms_where', $terms_advanced_search_where, $search_params);

									if( $terms_advanced_search_select_old == $terms_advanced_search_select ) {
										$terms_advanced_search_select .= " ,1  ";
									}

		                            //Query to find if there are any previous conditions
		                            $count_temp_previous_cond = $wpdb->query("UPDATE {$wpdb->base_prefix}sm_advanced_search_temp 
		                                                                        SET flag = 0
		                                                                        WHERE flag = ". $index_search_string);

		                            //Code to handle condition if the ids of previous cond are present in temp table
		                            if (($index == 0 && $count_temp_previous_cond > 0) || (!empty($result_terms_search))) {
		                                $terms_advanced_search_from .= " JOIN ".$wpdb->base_prefix."sm_advanced_search_temp
		                                                                    ON (".$wpdb->base_prefix."sm_advanced_search_temp.product_id = ".$wpdb->prefix."posts.id)";

		                                $terms_advanced_search_where .= "AND ".$wpdb->base_prefix."sm_advanced_search_temp.flag = 0";
		                            }

		                            $result_terms_search = array();

		                            if (!empty($terms_advanced_search_select ) && !empty($terms_advanced_search_from ) && !empty($terms_advanced_search_where )) {
		                                $query_terms_search = "REPLACE INTO {$wpdb->base_prefix}sm_advanced_search_temp
		                                                            (".$terms_advanced_search_select . " " .
		                                                                $terms_advanced_search_from . " " .
		                                                                $terms_advanced_search_where . " " .")";
		                                $result_terms_search = $wpdb->query ( $query_terms_search );
		                            }

		                            do_action('sm_search_terms_condition_complete',$result_terms_search,$search_params);
		                        }

		                        //Code to delete the unwanted post_ids
		                    	$wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp WHERE flag = 0");

		                        $index++;
		                    }

		                    do_action('sm_search_terms_conditions_array_complete',$search_params);

		                    //Query to reset the cat_flag
		                    $wpdb->query("UPDATE {$wpdb->base_prefix}sm_advanced_search_temp SET cat_flag = 0");
		                }

		                //Cond for postmeta
		                if (!empty($advanced_search_query_string['cond_postmeta'])) {

		                    $cond_postmeta_array = explode(" && ",$advanced_search_query_string['cond_postmeta']);

		                    $cond_postmeta_col_name = (!empty($advanced_search_query_string['cond_postmeta_col_name'])) ? explode(" && ",$advanced_search_query_string['cond_postmeta_col_name']) : '';
		                    $cond_postmeta_col_value = (!empty($advanced_search_query_string['cond_postmeta_col_value'])) ? explode(" && ",$advanced_search_query_string['cond_postmeta_col_value']) : '';
		                    $cond_postmeta_operator = (!empty($advanced_search_query_string['cond_postmeta_operator'])) ? explode(" && ",$advanced_search_query_string['cond_postmeta_operator']) : '';

		                    $index = 0;
		                    $cond_postmeta_post_ids = '';
		                    $result_postmeta_search = '';

		                    foreach ($cond_postmeta_array as $cond_postmeta) {

		                        // $postmeta_search_result_flag = ( $index == (sizeof($cond_postmeta_array) - 1) ) ? ', '.$index_search_string : ', 0';
		                        $postmeta_search_result_flag = ', '.$index_search_string;

		                        $cond_postmeta_col_name1 = (!empty($cond_postmeta_col_name[$index])) ? trim($cond_postmeta_col_name[$index]) : '';
		                        $cond_postmeta_col_value1 = (!empty($cond_postmeta_col_value[$index])) ? trim($cond_postmeta_col_value[$index]) : '';
		                        $cond_postmeta_operator1 = (!empty($cond_postmeta_operator[$index])) ? trim($cond_postmeta_operator[$index]) : '';

		                        $search_params = array( 'cond_postmeta_col_name' => $cond_postmeta_col_name1,
		                    							'cond_postmeta_col_value' => $cond_postmeta_col_value1,
		                    							'cond_postmeta_operator' => $cond_postmeta_operator1,
		                    							'SM_IS_WOO30' => (!empty($params['SM_IS_WOO30'])) ? $params['SM_IS_WOO30'] : '',
		                    							'post_type' => (!empty($params['post_type'])) ? $params['post_type'] : array() );

		                        $cond_postmeta = apply_filters('sm_search_postmeta_condition_start', $cond_postmeta, $search_params);

		                        $search_params['cond_postmeta'] = $cond_postmeta;

		                        $postmeta_advanced_search_select = 'SELECT DISTINCT '.$wpdb->prefix.'postmeta.post_id '. $postmeta_search_result_flag .' ,0 ';
		                        $postmeta_advanced_search_from = "FROM ".$wpdb->prefix."postmeta 
		                        									JOIN ".$wpdb->prefix."posts 
		                        									ON( ".$wpdb->prefix."posts.id = ".$wpdb->prefix."postmeta.post_id
		                        										AND ".$wpdb->prefix."posts.post_type IN ('". implode( "','", $params['post_type'] ) ."') )";
		                        $postmeta_advanced_search_where = 'WHERE '.$cond_postmeta;

		                        $postmeta_advanced_search_select = apply_filters('sm_search_query_postmeta_select', $postmeta_advanced_search_select, $search_params);
								$postmeta_advanced_search_from	= apply_filters('sm_search_query_postmeta_from', $postmeta_advanced_search_from, $search_params);
								$postmeta_advanced_search_where	= apply_filters('sm_search_query_postmeta_where', $postmeta_advanced_search_where, $search_params);

		                        //Query to find if there are any previous conditions
		                        $count_temp_previous_cond = $wpdb->query("UPDATE {$wpdb->base_prefix}sm_advanced_search_temp 
		                                                                    SET flag = 0
		                                                                    WHERE flag = ". $index_search_string);

		                        //Code to handle condition if the ids of previous cond are present in temp table
		                        if (($index == 0 && $count_temp_previous_cond > 0) || (!empty($result_postmeta_search))) {
		                            $postmeta_advanced_search_from .= " JOIN ".$wpdb->base_prefix."sm_advanced_search_temp
		                                                                ON (".$wpdb->base_prefix."sm_advanced_search_temp.product_id = {$wpdb->prefix}postmeta.post_id)";

		                            $postmeta_advanced_search_where .= " AND ".$wpdb->base_prefix."sm_advanced_search_temp.flag = 0";
		                        }

		                        $result_postmeta_search = array();

		                        if (!empty($postmeta_advanced_search_select ) && !empty($postmeta_advanced_search_from ) && !empty($postmeta_advanced_search_where )) {
			                        $query_postmeta_search = "REPLACE INTO {$wpdb->base_prefix}sm_advanced_search_temp
			                                                        (". $postmeta_advanced_search_select ."
			                                                        ". $postmeta_advanced_search_from ."
			                                                        ".$postmeta_advanced_search_where.")";
			                        $result_postmeta_search = $wpdb->query ( $query_postmeta_search );
			                    }

			                    do_action('sm_search_postmeta_condition_complete',$result_postmeta_search,$search_params, array(
									'select' => $postmeta_advanced_search_select,
									'from' => $postmeta_advanced_search_from,
									'where' => $postmeta_advanced_search_where
								));

			                    //Query to delete the unwanted post_ids
		                    	$wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp WHERE flag = 0");

		                        $index++;
		                    }

		                    do_action('sm_search_postmeta_conditions_array_complete',$search_params);
		                }

		                //Cond for posts
		                if (!empty($advanced_search_query_string['cond_posts'])) {

		                    $cond_posts_array = explode(" && ",$advanced_search_query_string['cond_posts']);

		                    $index = 0;
		                    $cond_posts_post_ids = '';
		                    $result_posts_search = '';

		                    foreach ( $cond_posts_array as $cond_posts ) {

		                        // $posts_search_result_flag = ( $index == (sizeof($cond_posts_array) - 1) ) ? ', '.$index_search_string : ', 0';
		                        $posts_search_result_flag = ', '.$index_search_string;
		                        $posts_search_result_cat_flag = ( $index == (sizeof($cond_posts_array) - 1) ) ? ", 999" : ', 0';

		                        $cond_posts .= ( !empty( $params['post_type'] ) ) ? " AND ".$wpdb->prefix."posts.post_type IN ('". implode( "','", $params['post_type'] ) ."') " : '';

		                        $cond_posts = apply_filters('sm_search_posts_condition_start', $cond_posts);

		                        $search_params = array('cond_posts' => $cond_posts,
		                    							'SM_IS_WOO30' => (!empty($params['SM_IS_WOO30'])) ? $params['SM_IS_WOO30'] : '',
		                    							'post_type' => (!empty($params['post_type'])) ? $params['post_type'] : '');

		                        $posts_advanced_search_select = "SELECT DISTINCT ".$wpdb->prefix."posts.id ". $posts_search_result_flag ." ". $posts_search_result_cat_flag ." ";
		                        $posts_advanced_search_from = " FROM ".$wpdb->prefix."posts ";
		                        $posts_advanced_search_where = " WHERE ". $cond_posts ." ";

		                        $posts_advanced_search_select = apply_filters('sm_search_query_posts_select', $posts_advanced_search_select, $search_params);
								$posts_advanced_search_from	= apply_filters('sm_search_query_posts_from', $posts_advanced_search_from, $search_params);
								$posts_advanced_search_where	= apply_filters('sm_search_query_posts_where', $posts_advanced_search_where, $search_params);

		                        //Query to find if there are any previous conditions
		                        $count_temp_previous_cond = $wpdb->query("UPDATE {$wpdb->base_prefix}sm_advanced_search_temp 
		                                                                    SET flag = 0
		                                                                    WHERE flag = ". $index_search_string);


		                        //Code to handle condition if the ids of previous cond are present in temp table
		                        if ( ($index == 0 && $count_temp_previous_cond > 0) || (!empty($result_posts_search)) || $index > 0 ) {
		                            $posts_advanced_search_from .= " JOIN ".$wpdb->base_prefix."sm_advanced_search_temp
		                                                                ON (".$wpdb->base_prefix."sm_advanced_search_temp.product_id = {$wpdb->prefix}posts.id) ";
		                            $posts_advanced_search_where .= " AND ".$wpdb->base_prefix."sm_advanced_search_temp.flag = 0 ";
		                        }

		                        $result_posts_search = array();

		                        if (!empty($posts_advanced_search_select ) && !empty($posts_advanced_search_from ) && !empty($posts_advanced_search_where )) {
			                        $query_posts_search = "REPLACE INTO {$wpdb->base_prefix}sm_advanced_search_temp
			                                                        ( ". $posts_advanced_search_select ."
			                                                        ". $posts_advanced_search_from ."
																	". $posts_advanced_search_where .")";
			                        $result_posts_search = $wpdb->query ( $query_posts_search );
			                    }
		                     
			                    //Query to delete the unwanted post_ids
		                    	$wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp WHERE flag = 0");

			                    do_action('sm_search_posts_condition_complete',$result_posts_search,$search_params);

		                        $index++;
		                    }

		                    //condition for handling ANDing with att and other fields

		                    $child_where_cond = '';

		                    if ( !empty( $advanced_search_query_string['cond_terms'] ) || !empty( $advanced_search_query_string['cond_postmeta'] ) ) {
		                        $child_where_cond = " WHERE ".$wpdb->prefix."posts.id IN (SELECT product_id FROM {$wpdb->base_prefix}sm_advanced_search_temp ) ";
		                    }

		                    //Query to get the variations of the parent product in result set
		                    $query_posts_search = "REPLACE INTO {$wpdb->base_prefix}sm_advanced_search_temp
		                                                    (SELECT DISTINCT {$wpdb->prefix}posts.id ,". $index_search_string .", 0
		                                                    FROM {$wpdb->prefix}posts 
		                                                        JOIN {$wpdb->base_prefix}sm_advanced_search_temp 
		                                                            ON ({$wpdb->base_prefix}sm_advanced_search_temp.product_id = {$wpdb->prefix}posts.post_parent
		                                                                AND {$wpdb->base_prefix}sm_advanced_search_temp.cat_flag = 999
		                                                                AND {$wpdb->base_prefix}sm_advanced_search_temp.flag = ".$index_search_string.")
		                                                    ". $child_where_cond .")";
		                    $result_posts_search = $wpdb->query ( $query_posts_search );

		                    do_action('sm_search_posts_conditions_array_complete',$search_params);
		                }
		                $index_search_string++;
		            }
		        }
		}

		//Function to get the data model for the dashboard
		public function get_data_model() {

			global $wpdb, $current_user;

			$data_model = array(); 

			$column_model_transient = get_user_meta(get_current_user_id(), 'sa_sm_'.$this->dashboard_key, true);

			// Code for handling views
			if( ( defined('SMPRO') && true === SMPRO ) && ! empty( $this->req_params['is_view'] ) && ! empty( $this->req_params['active_view'] ) ) {
				if( class_exists( 'Smart_Manager_Pro_Views' ) ) {
					$view_obj = Smart_Manager_Pro_Views::get_instance();
					if( is_callable( array( $view_obj, 'get' ) ) ){
						$view_slug = $this->req_params['active_view'];
						$view_data = $view_obj->get($view_slug);
						if( ! empty( $view_data ) ) {
							$this->dashboard_key = $view_data['post_type'];
							$column_model_transient = get_user_meta(get_current_user_id(), 'sa_sm_'.$view_slug, true);
							$column_model_transient = json_decode( $view_data['params'], true );
							
							if( !empty( $column_model_transient['search_params'] ) ) {
								if( ! empty( $column_model_transient['search_params']['isAdvanceSearch'] ) && "true" == $column_model_transient['search_params']['isAdvanceSearch'] ) { // For advanced search
									if( ! empty( $column_model_transient['search_params']['params'] ) && is_array( $column_model_transient['search_params']['params'] ) ) {
										// array_walk(
										// 	$column_model_transient['search_params']['params'],
										// 	function ( &$value ) {
										// 		$value = ( ! empty( $value ) ) ? addslashes( json_encode( $value ) ) : '';
										// 	}
										// );
										$this->req_params['advanced_search_query'] = addslashes( json_encode( $column_model_transient['search_params']['params']) );
									}
								} else { //for simple search
									$this->req_params['search_text'] = $column_model_transient['search_params']['params'];
								}
							}
						}
					}
				}
			}

			// code for assigning the sort params
			if( ! empty( $column_model_transient ) && empty( $this->req_params['sort_params'] ) ){
				$this->req_params['sort_params'] = ( ! empty( $column_model_transient['sort_params'] ) ) ? $column_model_transient['sort_params'] : array();
			}
			
			$store_model_transient = get_transient( 'sa_sm_'.$this->dashboard_key );

			if( ! empty( $store_model_transient ) && !is_array( $store_model_transient ) ) {
				$store_model_transient = json_decode( $store_model_transient, true );
			} else {
				$store_model_transient = $this->get_dashboard_model( true );
			}

			if( !empty( $column_model_transient ) && !empty( $store_model_transient ) ) {
				$store_model_transient = $this->map_column_to_store_model( $store_model_transient, $column_model_transient );
			}

			$col_model = (!empty($store_model_transient['columns'])) ? $store_model_transient['columns'] : array();

			$required_cols = apply_filters('sm_required_cols', array());

			$load_default_data_model = true;
			$load_default_data_model = apply_filters('sm_beta_load_default_data_model', $load_default_data_model);

			//Code for getting the relevant columns
			$data_cols_dropdown = array();
			$data_cols_multilist = array();
			$data_cols_longstring = array();
			$data_cols_serialized = array();
			$data_cols_checkbox = array();
			$data_cols_unchecked_template = array();
			$data_cols_timestamp = array();
			$data_date_cols_timestamp = array();
			$data_time_cols_timestamp = array();
			$data_cols_datetime = array();
			$data_cols_multi_select2 = array();
			$numeric_postmeta_cols_decimal_places = array();
			$view_edit_cols = array( 'custom_view_link', 'custom_edit_link' );

			$data_cols = array('ID');
			$postmeta_cols = array();
			$numeric_postmeta_cols = array();
			$image_postmeta_cols = array();
			$terms_visible_cols = array();

			$search_cols_type = array(); //array for col & its type for advanced search

			if (!empty($col_model)) {
				foreach ($col_model as $col) {

					$validator = ( !empty( $col['validator'] ) ) ? $col['validator'] : '';
					$type = ( !empty( $col['type'] ) ) ? $col['type'] : '';

					if( ! empty( $col['table_name'] ) && ! empty( $col['col_name'] ) ){
						// added $validator condition for spl cols like '_regular_price', '_sale_price', etc.
						$search_cols_type[ $col['table_name'] .'.'. $col['col_name'] ] = ( "customNumericTextEditor" === $validator && "text" == $type ) ? 'numeric' : $type;
					}

					if( !empty( $col['hidden'] ) && !empty( $col['data'] ) && array_search($col['data'], $required_cols) === false ) {
						continue;
					}

					$col_exploded = (!empty($col['src'])) ? explode("/", $col['src']) : array();

					if (empty($col_exploded)) continue;
					
					if ( sizeof($col_exploded) > 2) {
						$col_meta = explode("=",$col_exploded[1]);
						$col_nm = $col_meta[1];
					} else {
						$col_nm = $col_exploded[1];
					}

					$editor = ( !empty( $col['editor'] ) ) ? $col['editor'] : '';
					$data_cols[] = ( in_array( $col['data'], $view_edit_cols ) ) ? $col['data'] : $col_nm;

					if( !empty( $col_exploded[0] ) && $col_exploded[0] == 'postmeta' && $col_nm != 'post_id' ) {
						$postmeta_cols[] = $col_nm;

						if( ( $type == 'number' || $type == 'numeric' || $validator == 'customNumericTextEditor' ) && 'sm.image' !== $type ) {
							if( isset( $col['decimalPlaces'] ) ) {
								$numeric_postmeta_cols_decimal_places[ $col_nm ] = $col['decimalPlaces'];
							}
							$numeric_postmeta_cols[] = $col_nm;
						}

						if( 'sm.image' === $type ){
							$image_postmeta_cols[] = $col_nm;
						}

					}

					// Code for storing the serialized cols
					if( $type == 'sm.serialized' ) {
						$data_cols_serialized[] = $col_nm;
						if( $editor == 'text' ) {
							$data_cols_serialized_text_editor[ $col_nm ] = ( !empty( $col['separator'] ) ? $col['separator'] : ',' );
						}

					} if( $type == 'sm.longstring' ) {
						$data_cols_longstring[] = $col_nm;
					} else if( $type == 'sm.multilist' ) {
						$data_cols_multilist[] = $col_nm;
					} else if( $editor == 'select2' && !empty( $col['select2Options']['multiple'] ) ) {
						$data_cols_multi_select2[ $col['data'] ] = ( !empty( $col['separator'] ) ? $col['separator'] : '' );
					} else if( $type == 'dropdown' ) {
						$data_cols_dropdown[] = $col_nm;
					} else if( $type == 'checkbox' ) {
						$data_cols_checkbox[] = $col_nm;
						if( !empty( $col['uncheckedTemplate'] ) ) {
							$data_cols_unchecked_template[$col_nm] = $col['uncheckedTemplate'];
						}
					} else if( $type == 'sm.datetime' && !empty( $col['date_type'] ) && $col['date_type'] == 'timestamp' ) {
						$data_cols_timestamp[] = $col_nm;
					} else if( $type == 'sm.date' && !empty( $col['date_type'] ) && $col['date_type'] == 'timestamp' ) {
						$data_date_cols_timestamp[] = $col_nm;
					} else if( $type == 'sm.time' && !empty( $col['date_type'] ) && $col['date_type'] == 'timestamp' ) {
						$data_time_cols_timestamp[] = $col_nm;
					} else if( $type == 'sm.datetime' && empty( $col['date_type'] ) ) {
						$data_cols_datetime[] = $col_nm;
					}

					// Code for saving the taxonomy names
					if ($col_exploded[0] == 'terms' && $col_nm != 'object_id' ) {
						$terms_visible_cols[$col_nm] = ( ! empty( $col['values'] ) ) ? $col['values'] : array();
					}
				}
			}

			$data_col_params = array( 'data_cols' => $data_cols,
										'required_cols' => $required_cols,
										'data_cols_serialized' => $data_cols_serialized,
									 	'data_cols_longstring' => $data_cols_longstring,
									 	'data_cols_multilist' => $data_cols_multilist,
									 	'data_cols_dropdown' => $data_cols_dropdown,
									 	'data_cols_checkbox' => $data_cols_checkbox,
									 	'data_cols_timestamp' => $data_cols_timestamp,
									 	'data_date_cols_timestamp' => $data_date_cols_timestamp,
									 	'data_time_cols_timestamp' => $data_time_cols_timestamp,
									 	'data_cols_datetime' => $data_cols_datetime,
									 	'data_cols_multi_select2' => $data_cols_multi_select2,
									 	'data_cols_numeric_decimal_places' => $numeric_postmeta_cols_decimal_places );

			if( $load_default_data_model ) { //condition to skip the default data model
				$start = (!empty($this->req_params['start'])) ? $this->req_params['start'] : 0;
				$limit = (!empty($this->req_params['sm_limit'])) ? $this->req_params['sm_limit'] : ( ( !empty( $this->req_params['cmd'] ) && $this->req_params['cmd'] == 'get_export_csv' ) ? -1 : 50 );
				$current_page = (!empty($this->req_params['sm_page'])) ? $this->req_params['sm_page'] : '1';

				$start_offset = ($current_page > 1) ? (($current_page - 1) * $limit) : $start;

				$this->req_params['table_model'] = ( empty( $this->req_params['table_model'] ) && ! empty( $store_model_transient['tables'] ) ) ? $store_model_transient['tables'] : $this->req_params['table_model'];

				$post_cond = (!empty($this->req_params['table_model']['posts']['where'])) ? $this->req_params['table_model']['posts']['where'] : array('post_type' => $this->dashboard_key, 'post_status' => 'any' );
				$meta_query = (!empty($this->req_params['table_model']['postmeta']['where'])) ? $this->req_params['table_model']['postmeta']['where'] : '';
				$tax_query = (!empty($this->req_params['table_model']['terms']['where'])) ? $this->req_params['table_model']['terms']['where'] : '';

				// Condition to handle display of 'trash' records
				if( ! empty( $this->is_show_trash_records() ) && ! empty( $post_cond['post_status'] ) ){
					$post_cond['post_status'] = ( ! is_array( $post_cond['post_status'] ) ) ? array( $post_cond['post_status'] ) : $post_cond['post_status'];
					$post_cond['post_status'] = array_merge( $post_cond['post_status'], array( 'trash' ) );
				}

				//Code for advanced search
				$search = "";
				$search_condn = "";

		        //Code to clear the advanced search temp table
		        if( empty($this->req_params['advanced_search_query'])) {
		            $wpdb->query("DELETE FROM {$wpdb->base_prefix}sm_advanced_search_temp");
		            delete_option('sm_advanced_search_query');
		        }

				// TODO: revise this code as per new advanced search
		        // if( !empty($this->req_params['date_filter_query']) && ( defined('SMPRO') && true === SMPRO ) ) {

		        // 	if( empty($this->req_params['search_query']) ) {
		        // 		$this->req_params['search_query'] = array( $this->req_params['date_filter_query'] );
		        // 	} else {

		        // 		$date_filter_array = json_decode(stripslashes($this->req_params['date_filter_query']),true);

		        // 		foreach( $this->req_params['search_query'] as $key => $search_string_array ) {
		        // 			$search_string_array = json_decode(stripslashes($search_string_array),true);

		        // 			foreach( $date_filter_array as $date_filter ) {
				// 				$search_string_array[] = $date_filter;		
		        // 			}

		        // 			$this->req_params['search_query'][$key] = addslashes(json_encode($search_string_array));
		        // 		}
		        // 	}
		        // }

		        $sm_advanced_search_results_persistent = 0; //flag to handle persistent search results

		        //Code fo handling advanced search functionality
		        if( !empty( $this->req_params['advanced_search_query'] ) && $this->req_params['advanced_search_query'] != '[]' ) {

					$this->req_params['advanced_search_query'] = json_decode(stripslashes($this->req_params['advanced_search_query']), true);

		            if (!empty($this->req_params['advanced_search_query'])) {

		            	if( !empty( $this->req_params['table_model']['posts']['where']['post_type'] ) ) {
		            		$post_type = ( is_array( $this->req_params['table_model']['posts']['where']['post_type'] ) ) ? $this->req_params['table_model']['posts']['where']['post_type'] : array( $this->req_params['table_model']['posts']['where']['post_type'] );
						}

						$this->process_search_cond( array( 'post_type' => $post_type,
														'search_query' => (!empty($this->req_params['advanced_search_query'])) ? $this->req_params['advanced_search_query'] : array(),
		            									'SM_IS_WOO30' => (!empty($this->req_params['SM_IS_WOO30'])) ? $this->req_params['SM_IS_WOO30'] : '',
														'search_cols_type' => $search_cols_type,
														'data_col_params' => $data_col_params ) );

		            }

		        }

				// Code for handling sorting of the postmeta
				$sort_by_meta_key = '';

		        if( !empty( $this->req_params['sort_params'] ) ) {
		        	if( !empty( $this->req_params['sort_params']['column'] ) && !empty( $this->req_params['sort_params']['sortOrder'] ) ) {

		        		$col_exploded = explode( "/", $this->req_params['sort_params']['column'] );

		        		$this->req_params['sort_params']['table'] = $col_exploded[0];

						if ( sizeof($col_exploded) > 2) {
							$col_meta = explode("=",$col_exploded[1]);
							$this->req_params['sort_params']['column_nm'] = $col_meta[0];

							if( $this->req_params['sort_params']['column_nm'] == 'meta_key' ) {
								$this->req_params['sort_params']['sort_by_meta_key'] = $col_meta[1];
								$this->req_params['sort_params']['column_nm'] = ( !empty( $numeric_postmeta_cols ) && in_array( $col_meta[1], $numeric_postmeta_cols ) ) ? 'meta_value_num' : 'meta_value';
							}
						} else {
							$this->req_params['sort_params']['column_nm'] = ( !empty( $col_exploded[1] ) ) ? $col_exploded[1] : '';
						}

						$this->req_params['sort_params']['sortOrder'] = strtoupper( $this->req_params['sort_params']['sortOrder'] );
		        	}
		        }

				//WP_Query to get all the relevant post_ids
				$args = array(
					            'posts_per_page' => $limit,
					            'offset' => $start_offset,
					            'meta_query' => array( $meta_query ),
					            'tax_query' => array( $tax_query ),
					            'orderby' => ( !empty( $this->req_params['sort_params']['column_nm'] ) ? $this->req_params['sort_params']['column_nm'] : '' ),
					            'order' => ( !empty( $this->req_params['sort_params']['sortOrder'] ) ? $this->req_params['sort_params']['sortOrder'] : '' ),
								'sm_sort_params' => ( !empty( $this->req_params['sort_params'] ) ? $this->req_params['sort_params'] : array() )
							);

				$args = array_merge($args, $post_cond);

				//Code for saving the post_ids in case of simple search
				if( ( defined('SMPRO') && true === SMPRO ) && !empty( $this->req_params['search_text'] ) || (!empty($this->req_params['advanced_search_query']) && $this->req_params['advanced_search_query'] != '[]') ) {
					$search_query_args = array_merge( $args, array( 'posts_per_page' => -1,
																	'fields' => 'ids' ) );
					unset( $search_query_args['offset'] );
					$search_results = new WP_Query( $search_query_args );
					$post_ids = implode( ",",$search_results->posts );

					set_transient( 'sa_sm_search_post_ids', $post_ids , WEEK_IN_SECONDS );
				}

				$result_posts = new WP_Query( $args );
	        	$items = array();
	        	$post_ids = array();
	        	$index_ids = array();

	        	$posts_data = $result_posts->posts;
	        	$total_count = $result_posts->found_posts;

	        	$index = 0;
	        	$total_pages = 1;

	        	if ($total_count > $limit) {
	        		$total_pages = ceil($total_count/$limit);
	        	}

	        	if ( !empty( $posts_data ) ) {
	        		foreach ($posts_data as $key => $value) {

	        			$post = (array) $value;

	        			foreach ($post as $post_key => $post_value) {

	        				if ( is_array( $data_cols ) && !empty( $data_cols ) ) {
	        					if ( array_search( $post_key, $data_cols ) === false ) {
	        						continue; //cond for checking col in col model	
	        					}
	        				}

	        				if ( is_array( $data_cols_checkbox ) && !empty( $data_cols_checkbox ) ) {
	        					if( array_search( $post_key, $data_cols_checkbox ) !== false && $post_value == '' ) { //added for bad_value checkbox
	        						$post_value = $data_cols_unchecked_template[$post_key];
	        					}
	        				}

							if( is_array( $data_cols_serialized ) && !empty( $data_cols_serialized ) ) {
        						if( in_array( $post_key, $data_cols_serialized ) ) {
									$post_value = maybe_unserialize( $post_value );
									if( !empty( $post_value ) ) {
										$post_value = ( !empty( $data_cols_serialized_text_editor[$post_key] ) ) ? implode($data_cols_serialized_text_editor[$post_key], $post_value) : json_encode( $post_value );
									}
		        				}
        					}

	        				$key = 'posts_'.strtolower(str_replace(' ', '_', $post_key));
	        				$items [$index][$key] = $post_value;
	        			}

	        			//Code for generating the view & edit links for the post
	        			if ( is_array( $data_cols ) && !empty( $data_cols ) ) {
	        				foreach( $view_edit_cols as $col ) {
	        					if ( array_search( $col, $data_cols ) ) {
        							$link = ( 'custom_view_link' === $col ) ? get_permalink($value->ID) : get_edit_post_link($value->ID);
	        						$items [$index]['custom_'. ( ( 'custom_view_link' === $col ) ? 'view' : 'edit' ) .'_link'] = ( !empty( $this->req_params['cmd'] ) && $this->req_params['cmd'] != 'get_export_csv' && $this->req_params['cmd'] != 'get_print_invoice' ) ? '<a href="'.$link.'" target="_blank" style="text-decoration:none !important; color:#5850ecc2 !important;"><span class="dashicons dashicons-external"></span></a>' : $link;
	        					}
	        				}
        				}

	        			$post_ids[] = $value->ID; //storing the post ids for fetching the terms
	        			$index_ids[ $value->ID ] = $index;
	        			$index++;
	        		}
	        	}

	        	//Code for getting the postmeta data
	        	if( !empty( $post_ids ) && !empty( $postmeta_cols ) ) {

	        		if( !empty( $items ) ) { //Code to create and initialize all the meta columns
	        			foreach ( $items as $key => $item ) {
	        				foreach ( $postmeta_cols as $col ) {
	        					$meta_key = 'postmeta_meta_key_'.$col.'_meta_value_'.$col;
	        					$meta_value = '';

	        					//Code for handling checkbox data
	        					if( is_array( $data_cols_checkbox ) && !empty( $data_cols_checkbox ) && is_array( $data_cols_unchecked_template ) && !empty( $data_cols_unchecked_template ) ) {
	        						if( in_array( $col, $data_cols_checkbox ) && !empty( $data_cols_unchecked_template[ $col ] ) ) { //added for bad_value checkbox
			        					$meta_value = $data_cols_unchecked_template[ $col ];
			        				}
	        					}

	        					$items [$key][$meta_key] = $meta_value;	
	        				}
	        			}
	        		}

					$postmeta_data = array();
					//TODO: Check Not working on client site
					if( count( $post_ids ) > 100 ) {
						// $current_user_id = get_current_user_id();
						// $temp_db_key = 'sm_export_post_ids_' . $current_user_id;

						// // Store order ids temporarily in table.
						// update_option( $temp_db_key, implode( ',', $post_ids ) );

						// $postmeta_data = $wpdb->get_results( $wpdb->prepare( "SELECT post_id as post_id,
						// 					                              meta_key AS meta_key,
						// 					                              meta_value AS meta_value
						// 					                    FROM {$wpdb->prefix}postmeta as prod_othermeta 
						// 					                    WHERE FIND_IN_SET ( post_id, ( SELECT option_value 
						// 															FROM {$wpdb->prefix}options 
						// 															WHERE option_name = %s ) ) 
						// 					                    	AND meta_key IN ('". implode("','", $postmeta_cols) ."')
						// 					                    	AND 1=%d
						// 										GROUP BY post_id, meta_key", $temp_db_key, 1 ), 'ARRAY_A' );

						// delete_option( $temp_db_key );

						$post_id_chunks = array_chunk( $post_ids, 100 );

						foreach( $post_id_chunks as $id_chunk ){
							$results = $wpdb->get_results( $wpdb->prepare( "SELECT post_id as post_id,
											                              meta_key AS meta_key,
											                              meta_value AS meta_value
											                    FROM {$wpdb->prefix}postmeta as prod_othermeta 
											                    WHERE post_id IN (". implode(",",$id_chunk) .")
											                    	AND meta_key IN ('". implode("','", $postmeta_cols) ."')
											                    	AND 1=%d
																GROUP BY post_id, meta_key", 1 ), 'ARRAY_A' );
							
							if( ! empty( $results ) ) {
								$postmeta_data = array_merge( $postmeta_data, $results );
							}
						}
				
					} else {
						$postmeta_data = $wpdb->get_results( $wpdb->prepare( "SELECT post_id as post_id,
											                              meta_key AS meta_key,
											                              meta_value AS meta_value
											                    FROM {$wpdb->prefix}postmeta as prod_othermeta 
											                    WHERE post_id IN (". implode(",",$post_ids) .")
											                    	AND meta_key IN ('". implode("','", $postmeta_cols) ."')
											                    	AND 1=%d
																GROUP BY post_id, meta_key", 1 ), 'ARRAY_A' );
					}

	        		if( !empty( $postmeta_data ) ) {

	        			foreach( $postmeta_data as $data ) {
	        				$index = ( isset( $index_ids[$data['post_id']] ) ) ? $index_ids[$data['post_id']] : '';

	        				if( '' === $index ) {
	        					continue;
	        				}

	        				$items [$index]['postmeta_post_id'] = $data['post_id'];

	        				$meta_key = ( isset( $data['meta_key'] ) ) ? $data['meta_key'] : '';

	        				if( empty( $meta_key ) ) {
	        					continue;
	        				}
	        				
	        				if( !in_array( $data['meta_key'], $data_cols_checkbox ) ) {
								$meta_value = ( isset( $data['meta_value'] ) ) ? $data['meta_value'] : '';
	        				} else {
	        					$meta_value = ( isset( $data['meta_value'] ) && $data['meta_value'] != '' ) ? $data['meta_value'] : $items[$key]['postmeta_meta_key_'.$meta_key.'_meta_value_'.$meta_key];
	        				}

	        				//Code for handling serialized data
        					if( is_array( $data_cols_serialized ) && !empty( $data_cols_serialized ) ) {
        						if( in_array( $meta_key, $data_cols_serialized ) ) {
									$meta_value = maybe_unserialize( $meta_value );
									if( !empty( $meta_value ) ) {
										$meta_value = ( !empty( $data_cols_serialized_text_editor[$meta_key] ) ) ? implode($data_cols_serialized_text_editor[$meta_key], $meta_value) : json_encode( $meta_value );
									}
		        				}
        					}

        					//Code for handling timestamp data
        					if( ( is_array( $data_cols_timestamp ) && !empty( $data_cols_timestamp ) ) || ( is_array( $data_date_cols_timestamp ) && !empty( $data_date_cols_timestamp ) ) || ( is_array( $data_time_cols_timestamp ) && !empty( $data_time_cols_timestamp ) )  ) {
        						if( ( in_array( $meta_key, $data_cols_timestamp ) || in_array( $meta_key, $data_date_cols_timestamp ) || in_array( $meta_key, $data_time_cols_timestamp ) ) && !empty( $meta_value ) && is_numeric( $meta_value ) ) {

        							$date = new DateTime();
									$date->setTimestamp($meta_value);
        							$format = 'Y-m-d H:i:s';

        							if( in_array( $meta_key, $data_date_cols_timestamp ) ) {
        								$format = 'Y-m-d';
        							} else if ( in_array( $meta_key, $data_time_cols_timestamp ) ) {
        								$format = 'H:i';
        							}

									$meta_value = $date->format($format);
        						}
        					}

        					//Code for handling blank date values
        					if( is_array( $data_cols_datetime ) && !empty( $data_cols_datetime ) ) {
        						if( in_array( $meta_key, $data_cols_datetime ) && empty( $meta_value ) && is_numeric( $meta_value ) ) {
        							$meta_value = '-';
        						}
							}	

							// //Code for handling blank numeric fields
        					// if( in_array( $meta_key, $numeric_postmeta_cols ) && ! isset( $numeric_postmeta_cols_decimal_places[$meta_key] ) ) {
        					// 	$meta_value = ( ! empty( $meta_value ) ) ? $meta_value : 0;
        					// }
							
							//Code for handling image fields
        					if( in_array( $meta_key, $image_postmeta_cols ) ) {
								if( ! empty( $meta_value ) ){
									$attachment = wp_get_attachment_image_src($meta_value, 'full');
									if ( is_array( $attachment ) && ! empty( $attachment[0] ) ) {
										$meta_value = $attachment[0];
									} else {
										$meta_value = '';
									}
								}
							}
							

        					//Code for rounding of integer fields
        					if( isset( $numeric_postmeta_cols_decimal_places[$meta_key] ) && !empty( $meta_value ) ) {
        						$meta_value = round( $meta_value, $numeric_postmeta_cols_decimal_places[$meta_key] );
        					}

        					$meta_key = sanitize_title($meta_key);

	        				$meta_key = 'postmeta_meta_key_'.$meta_key.'_meta_value_'.$meta_key;
	        				$items [$index][$meta_key] = $meta_value;
	        			}
	    			}
	        	}

	        	//Code to get all relevant taxonomy for the post type
				// $terms_visible_cols = get_object_taxonomies($this->dashboard_key);

	        	$valid_term_visible_cols = array_filter( array_keys( $terms_visible_cols ), function( $taxonomy ) {
	        											return taxonomy_exists( $taxonomy );
	        										});

	        	$terms_objects = wp_get_object_terms( $post_ids, $valid_term_visible_cols, 'orderby=none&fields=all_with_object_id' );

	        	if( !empty( $items ) ) { //Code to create and initialize all the meta columns
        			foreach ( $items as $key => $item ) {
        				foreach ( array_keys( $terms_visible_cols ) as $col ) {
        					$terms_key = 'terms_'.strtolower(str_replace(' ', '_', $col));
        					$items [$key][$terms_key] = '';	
        				}
        			}
        		}

	        	$items_index_id_mapping = array();

	        	if( !empty( $terms_visible_cols ) && !empty( $terms_objects ) ) {
	        		
	        		//Code for creating the terms data array
					foreach ($terms_objects as $term_obj) {
						if (empty($terms_data[$term_obj->object_id])) {
							$terms_data[$term_obj->object_id] = array();
						}

						$taxonomy_nm = $term_obj->taxonomy;

						//Code for handling multilist data
	        			if ( is_array($data_cols_multilist) && array_search($taxonomy_nm, $data_cols_multilist) !== false && ( is_array( $postmeta_cols ) && array_search( $taxonomy_nm, $postmeta_cols ) === false )  ) { //added postmeta check condition for multilist columns
							$multilist_value = $term_obj->name;
							$multilist_separator = ', ';

							if( ! empty( $terms_visible_cols[$taxonomy_nm] ) ){
								if( ! empty( $terms_visible_cols[$taxonomy_nm][$term_obj->term_id] ) ){
									$multilist_value = ( ! empty( $terms_visible_cols[$taxonomy_nm][$term_obj->term_id]['title'] ) ) ? $terms_visible_cols[$taxonomy_nm][$term_obj->term_id]['title'] : $multilist_value;
								}
							}
							if (empty($terms_data[$term_obj->object_id][$taxonomy_nm])) {
	        					$terms_data[$term_obj->object_id][$taxonomy_nm] = $multilist_value;
	        				} else {
	        					$terms_data[$term_obj->object_id][$taxonomy_nm] .= $multilist_separator . "" . $multilist_value;
	        				}
	        			} else if( is_array($data_cols_dropdown) && array_search($taxonomy_nm, $data_cols_dropdown) !== false ) {
	        				$terms_data[$term_obj->object_id][$taxonomy_nm] = $term_obj->term_id;
	        			} else {
	        				$terms_data[$term_obj->object_id][$taxonomy_nm] = $term_obj->name;
	        			}

						// $terms_data[$term_obj->object_id][$term_obj->taxonomy] = $term_obj->term_taxonomy_id;
					}

	        		foreach( $items as $key => $item) {
	        			$id = (!empty($item['posts_id'])) ? $item['posts_id'] : '';
						if (empty($id)) continue;

						foreach( array_keys( $terms_visible_cols ) as $visible_taxonomy ) {
							$terms_key = 'terms_'.strtolower(str_replace(' ', '_', $visible_taxonomy));
							$items[$key][$terms_key] = ( !empty( $terms_data[$id][$visible_taxonomy] ) ) ? $terms_data[$id][$visible_taxonomy] : '';
						}
	        		}
	        	}

	        	foreach( $items as $key => $item ) {
	        		//Code for handling multi-select2 columns
	        		foreach( $data_cols_multi_select2 as $col => $separator ) {
						if( isset( $item[ $col ] ) ) {
							if( !empty( $separator ) ) {
								$val = explode( $separator, $item[ $col ] );
							} else { //for serialized strings
								$val = maybe_unserialize( $item[ $col ] );
							}

							if( is_array( $val ) ) {
								$items[$key][ $col ] = implode(',', $val);
							} else {
								$items[$key][ $col ] = '';
							}
						}
					}
	        	}

	        	$data_model ['items'] = (!empty($items)) ? $items : '';
	        	$data_model ['start'] = $start+$limit;
	        	$data_model ['page'] = $current_page;
	        	$data_model ['total_pages'] = $total_pages;
	        	$data_model ['total_count'] = $total_count;
			}

        	//Filter to modify the data model
			$data_model = apply_filters( 'sm_data_model', $data_model, $data_col_params );

			if( !empty( $this->req_params['cmd'] ) && ( $this->req_params['cmd'] == 'get_export_csv' || $this->req_params['cmd'] == 'get_print_invoice' ) ) {
				return $data_model;
			} else {
				wp_send_json( $data_model );
			}

		}

		public function get_batch_update_copy_from_record_ids( $args = array() ) {

			global $wpdb;
			$data = array();

			$dashboard_key = ( !empty( $args['dashboard_key'] ) ) ? $args['dashboard_key'] : $this->dashboard_key;
			$is_ajax = ( isset( $args['is_ajax'] )  ) ? $args['is_ajax'] : true;

			if( !empty( $dashboard_key ) || !empty( $this->req_params['table_model']['posts']['where']['post_type'] ) ) {
				$dashboards = ( !empty( $this->req_params['table_model']['posts']['where']['post_type'] ) && empty( $args['dashboard_key'] ) ) ? $this->req_params['table_model']['posts']['where']['post_type'] : $dashboard_key;
				$dashboards = ( is_array( $dashboards ) ) ? $dashboards : array( $dashboards );
				$search_term = ( ! empty( $this->req_params['search_term'] ) ) ? $this->req_params['search_term'] : ( ( ! empty( $args['search_term'] ) ) ? $args['search_term'] : '' );


				$select = apply_filters( 'sm_batch_update_copy_from_ids_select', "SELECT ID AS id, post_title AS title", $args );

				$search_cond = ( ! empty( $search_term ) ) ? " AND ( id LIKE '%".$search_term."%' OR post_title LIKE '%".$search_term."%' OR post_excerpt LIKE '%".$search_term."%' ) " : '';

				$search_cond_ids = ( !empty( $args['search_ids'] ) ) ? " AND id IN ( ". implode(",", $args['search_ids']) ." ) " : '';

				$results = $wpdb->get_results( $select . " FROM {$wpdb->prefix}posts WHERE post_status != 'trash' ". $search_cond ." ". $search_cond_ids ." AND post_type IN ('". implode("','", $dashboards) ."') ", 'ARRAY_A' );

				if( count( $results ) > 0 ) {
					foreach( $results as $result ) {
						$data[ $result['id'] ] = trim($result['title']);
					}
				}

				$data = apply_filters( 'sm_batch_update_copy_from_ids', $data );
			}

			if( $is_ajax ){
				wp_send_json( $data );
			} else {
				return $data;
			}
		}

		// Function to get the meta data for the given ids
		public function get_meta_data($ids, $meta_keys, $update_table, $update_table_key = 'post_id') {
			global $wpdb;

			$ids_format = implode(', ', array_fill(0, count($ids), '%s'));
			$meta_keys_format = implode(', ', array_fill(0, count($meta_keys), '%s'));
			$group_by = '';

			if ( $update_table == 'postmeta' ) {
				$group_by = 'GROUP BY '.$update_table_key.' , meta_id';
			}

			$old_meta_data_query = "SELECT *
								  FROM {$wpdb->prefix}$update_table
								  WHERE post_id IN (".implode(',',$ids).")
								  	AND meta_key IN ('".implode("','",$meta_keys)."')
								  	AND 1=%d
								  $group_by";

			$old_meta_data_results = $wpdb->get_results( $wpdb->prepare( $old_meta_data_query, 1 ), 'ARRAY_A');  // passed 1 to avoid the debug warning

			$old_meta_data = array();

			if ( count($old_meta_data_results) > 0) {
				foreach ($old_meta_data_results as $meta_data) {

					$post_id = $meta_data[$update_table_key];
					unset($meta_data[$update_table_key]);

					if ( empty($old_meta_data[$post_id]) ) {
						$old_meta_data[$post_id] = array();
					}
					
					$old_meta_data[$post_id][] = $meta_data;
				}
			}

			return $old_meta_data;
		}

		public function save_state() {

			$dashboard_type = '';
			$slug = ( ! empty( $this->req_params['active_module'] ) ) ? $this->req_params['active_module'] : '';
			
			if( ! empty( $slug ) && ! empty( $this->req_params['dashboard_states'] ) ) {

				$dashboard_type = ( ! empty( $this->req_params['is_view'] ) ) ? 'view' : 'post_type';
				
				// Code to update the dashboards column state
				foreach ($this->req_params['dashboard_states'] as $dashboard => $value) {
					$value = json_decode( stripslashes( $value ), true );			
					$column_model_transient = sa_sm_generate_column_state( $value );
					if( 1 !== intval($this->req_params['is_view']) ) {
						update_user_meta( get_current_user_id(), 'sa_sm_'.$dashboard, $column_model_transient );
					}
				}

				if( 1 === intval( $this->req_params['is_view'] ) ) {
					global $wpdb;	
					$result = $wpdb->query( // phpcs:ignore
											$wpdb->prepare( // phpcs:ignore
												"UPDATE {$wpdb->prefix}sm_views
																	SET params = %s
																	WHERE slug = %s",
												wp_json_encode( $column_model_transient ),
												$slug
											)
										);
				}			
			}
			
			if( ! empty( $dashboard_type ) ) {
				// Code to update the recent accessed dashboards
				sa_sm_update_recent_dashboards( $dashboard_type.'s', $slug );

				// code to update recently accessed dashboard type
				update_user_meta( get_current_user_id(), 'sa_sm_recent_dashboard_type', $dashboard_type );
			}
			
			wp_send_json( array( 'ACK'=> 'Success' ) );
		}

		// Function to reset the column state to default
		public function reset_state() {
			global $wpdb;

			$current_user_id = get_current_user_id();
			$slug = ( ! empty( $this->req_params['active_module'] ) ) ? $this->req_params['active_module'] : '';
			$is_view = ( isset( $this->req_params['is_view'] ) ) ? ( 1 === intval( $this->req_params['is_view'] ) ) : false;

			if( ! $is_view && ! empty( $slug ) ) {
			 	delete_user_meta( $current_user_id, 'sa_sm_'.$slug );
			} else {
				$column_model_transient = array();
				if( ! empty( $this->req_params['dashboard_key'] ) ) {
					$column_model_transient = get_user_meta( $current_user_id, 'sa_sm_'.$this->req_params['dashboard_key'], true);
					if( empty( $column_model_transient ) ) {
						$store_model_transient = get_transient( 'sa_sm_'.$this->req_params['dashboard_key'] );
						if( ! empty( $store_model_transient ) && !is_array( $store_model_transient ) ) {
							$column_model_transient = sa_sm_generate_column_state( json_decode( $store_model_transient, true ) );
						}	
					}
				}
			
			 	if( ! empty( $column_model_transient ) ) {
					$result = $wpdb->query( // phpcs:ignore
											$wpdb->prepare( // phpcs:ignore
												"UPDATE {$wpdb->prefix}sm_views
																	SET params = %s
																	WHERE slug = %s",
												wp_json_encode( $column_model_transient ),
												$slug
											)
										);
				}	
				
			}		
			wp_send_json( array( 'ACK'=> 'Success' ) );
		}

		public function inline_update() {
			global $wpdb, $current_user;

			$edited_data = (!empty($this->req_params['edited_data'])) ? json_decode(stripslashes($this->req_params['edited_data']), true) : array();
			
			$store_model_transient = get_transient( 'sa_sm_'.$this->dashboard_key );
			
			if( ! empty( $store_model_transient ) && !is_array( $store_model_transient ) ) {
				$store_model_transient = json_decode( $store_model_transient, true );
			} else {
				$store_model_transient = $this->get_dashboard_model( true );
			}

			$table_model = (!empty($store_model_transient['tables'])) ? $store_model_transient['tables'] : array();
			$col_model = (!empty($store_model_transient['columns'])) ? $store_model_transient['columns'] : array();

			if (empty($edited_data) || empty($table_model) || empty($col_model)) return;	
			
			$edited_data = apply_filters('sm_inline_update_pre', $edited_data);

			$data_cols_serialized = array();
			$data_cols_multiselect = array();
			$data_cols_multiselect_val = array();
			$data_cols_list = array();
			$data_cols_list_val = array();
			$data_cols_timestamp = array();
			$data_date_cols_timestamp = array();
			$data_time_cols_timestamp = array();
			$date_cols_site_timezone = array();

			//Code for storing the serialized cols
			foreach ($col_model as $col) {
				$col_exploded = (!empty($col['src'])) ? explode("/", $col['src']) : array();

				if (empty($col_exploded)) continue;
				
				if ( sizeof($col_exploded) > 2) {
					$col_meta = explode("=",$col_exploded[1]);
					$col_nm = $col_meta[1];
				} else {
					$col_nm = $col_exploded[1];
				}

				if ( !empty( $col['type'] ) ) {
					if( $col['type'] == 'sm.serialized' ) {
						$data_cols_serialized[] = $col_nm;
					} elseif( $col['type'] == 'sm.multilist' ) {
						$data_cols_multiselect[] = $col_nm;
						$data_cols_multiselect_val[$col_nm] = (!empty($col['values'])) ? $col['values'] : array();

						if (empty($data_cols_multiselect_val[$col_nm])) continue;

						$final_multiselect_val = array();

						foreach ($data_cols_multiselect_val[$col_nm] as $key => $value) {
							if( ! empty( $value['term'] ) ) {
								$final_multiselect_val[$key] = $value['term'];
							}
						}

						$data_cols_multiselect_val[$col_nm] = $final_multiselect_val;
					} elseif ( $col['type'] == 'dropdown' ) {
						$data_cols_list[] = $col_nm;
						$data_cols_list_val[$col_nm] = (!empty($col['values'])) ? $col['values'] : array();
					} else if( $col['type'] == 'sm.datetime' && !empty( $col['date_type'] ) && $col['date_type'] == 'timestamp' ) {
						$data_cols_timestamp[] = $col_nm;
					} else if( $col['type'] == 'sm.date' && !empty( $col['date_type'] ) && $col['date_type'] == 'timestamp' ) {
						$data_date_cols_timestamp[] = $col_nm;
					} else if( $col['type'] == 'sm.time' && !empty( $col['date_type'] ) && $col['date_type'] == 'timestamp' ) {
						$data_time_cols_timestamp[] = $col_nm;
					}

					if( ($col['type'] == 'sm.datetime' || $col['type'] == 'sm.date') && ( isset($col['is_utc']) && false === $col['is_utc'] ) ) {
						$date_cols_site_timezone[] = $col_nm;
					}
				}
			}

			$sm_default_inline_update = true;

			$data_col_params = array( 'data_cols_multiselect' => $data_cols_multiselect,
									 'data_cols_multiselect_val' => $data_cols_multiselect_val,
									 'data_cols_list' => $data_cols_list,
									 'data_cols_list_val' => $data_cols_list_val,
									 'data_cols_timestamp' => $data_cols_timestamp,
									 'data_date_cols_timestamp' => $data_date_cols_timestamp,
									 'data_time_cols_timestamp' => $data_time_cols_timestamp,
									 'col_model' => $col_model
									);

			$sm_default_inline_update = apply_filters('sm_beta_default_inline_update', $sm_default_inline_update);

			set_transient('sm_beta_skip_delete_dashboard_transients', 1, DAY_IN_SECONDS); // for preventing delete dashboard transients

			if( !empty($sm_default_inline_update) ) {
				$update_params_meta = array(); // for all tables with meta_key = meta_value like structure for updating the values
				$insert_params_meta = array(); // for all tables with meta_key = meta_value like structure for inserting the values
				$meta_data_edited = array();
				$meta_index = 0;
				$old_post_id = '';
				$meta_case_cond = 'CASE post_id ';
				$meta_keys_edited = array(); // array for storing the edited meta_keys
				$data_col_params['posts_fields'] = array(); // array for keeping track of all 'posts' table fields

				foreach ($edited_data as $id => $edited_row) {

					
					$update_params_custom = array(); // for custom tables
					$where_cond = array();
					$insert_post = 0;

					$temp_id = $id;
					$id = ( strpos($id, 'sm_temp_') !== false ) ? 0 : $id; //for newly added records

					//Code for inserting the post
					if ( empty($id) ) {
						$insert_params_posts = array();
						foreach ($edited_row as $key => $value) {
							$edited_value_exploded = explode("/", $key);
							
							if (empty($edited_value_exploded)) continue;

							$update_table = $edited_value_exploded[0];
							$update_column = $edited_value_exploded[1];

							if ($update_table == 'posts') {
								$insert_params_posts [$update_column] = $value;
							}
						}

						if( empty( $insert_params_posts['post_type'] ) ) {
							$insert_params_posts['post_type'] = $this->dashboard_key;
						}

						if ( !empty($insert_params_posts) ) {
							$inserted_id = wp_insert_post($insert_params_posts);
							if ( !is_wp_error( $inserted_id ) && !empty($inserted_id) ) {
								if( ! empty( $edited_data[$temp_id] ) ){
									unset( $edited_data[$temp_id] );
								} 
								$id = $inserted_id;
								$insert_post = 1; //Flag for determining whether post has been inserted	
								$edited_data[$id] =  $edited_row;
							} else {
								continue;
							}

						} else {
							continue;
						}
					}

					// if (empty($edited_row['posts/ID'])) continue;

					// $id = $edited_row['posts/ID'];

					foreach ($edited_row as $key => $value) {
						$edited_value_exploded = explode("/", $key);

						if (empty($edited_value_exploded)) continue;

						$update_cond = array(); // for handling the where condition
						$update_params_meta_flag = false; // flag for handling the query for meta_key = meta_value like structure

						$update_table = $edited_value_exploded[0];
						$update_column = $edited_value_exploded[1];

						if (empty($where_cond[$update_table])) {
							$where_cond[$update_table] = (!empty($table_model[$update_table]['pkey']) && $update_column == $table_model[$update_table]['pkey']) ? 'WHERE '. $table_model[$update_table]['pkey'] . ' = ' . $value : '';
						}

						if ( sizeof($edited_value_exploded) > 2) {
							$cond = explode("=",$edited_value_exploded[1]);

							if (sizeof($cond) == 2) {
								$update_cond [$cond[0]] = $cond[1];
							}

							$update_column_exploded = explode("=",$edited_value_exploded[2]);
							$update_column = $update_column_exploded[0];

							$update_params_meta_flag = true;
						}
						
						// handling the update array for posts table
						if ( $update_table == 'posts' && $insert_post != 1 ) {

							if ( ! empty( $id ) && empty($data_col_params['posts_fields'][$id][$table_model[$update_table]['pkey']]) ) {
								$data_col_params['posts_fields'][$id][$table_model[$update_table]['pkey']] = $id;
							}

							$data_col_params['posts_fields'][$id][$update_column] = $value;

						} else if ( $update_params_meta_flag === true ) {

							if (empty($id) || empty($update_cond['meta_key'])) continue;

							$meta_key = $update_cond['meta_key'];
							$updated_val = $value;

							//Code for handling serialized data
	    					if( in_array($meta_key, $data_cols_serialized) ) {
								if (!empty($value)) {
									$updated_val = json_decode($value,true);

									if( empty( $updated_val ) ) { // for comma separated string values
										$updated_val = explode(",", $value);

										if( empty($updated_val) ) {
											$updated_val = $value;
										}
									}
								}
	        				}

	        				//Code for handling timestamp data
	    					if( in_array($meta_key, $data_cols_timestamp) || in_array($meta_key, $data_date_cols_timestamp) || in_array($meta_key, $data_time_cols_timestamp) ) {

	    						if( in_array($meta_key, $data_time_cols_timestamp) ) {
	    							$value = '1970-01-01'.$value;
								}

								//Code for converting date & datetime values to localize timezone
								$value = !empty ( $value ) ? strtotime( $value ) : '';
								if( in_array( $meta_key, $date_cols_site_timezone ) ){
									$offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
									$updated_val = ( ! empty( $value ) ) ? $value + $offset : '';
								} else {
									$updated_val = $value;
								}
	    					}

							// update_post_meta($id, $meta_key, $value );

							//Code for forming the edited data array
							if ( empty($meta_data_edited[$update_table]) ) {
								$meta_data_edited[$update_table] = array();
							}

							if ( empty($meta_data_edited[$update_table][$id]) ) {
								$meta_data_edited[$update_table][$id] = array();
							}

							$meta_data_edited[$update_table][$id][$update_cond['meta_key']] = $updated_val;
							$meta_keys_edited [$update_cond['meta_key']] = '';

						} else if($update_table == 'terms') {
							//code for handling updates for terms

	    					$term_ids = array();

							//Code for handling multiselect data
	    					if (array_search($update_column, $data_cols_multiselect) !== false) {

	    						$actual_val = (!empty($data_cols_multiselect_val[$update_column])) ? $data_cols_multiselect_val[$update_column] : array();
	    						if(!empty($value) && !empty($actual_val)){
									$term_ids = array_map('intval', explode(",",$value));
								}

								$result = wp_set_object_terms($id, $term_ids, $update_column);

	    					} else if (array_search($update_column, $data_cols_list) !== false) {

	    						$actual_val = (!empty($data_cols_list_val[$update_column])) ? $data_cols_list_val[$update_column] : array();
	    						if(empty($value) || empty($actual_val)) continue;
								$edited_values = explode(", ",$value);
								if (empty($edited_values)) continue;

								if (!empty($edited_values)) {
									foreach ($edited_values as $edited_value) {
										$term_id = array_search($edited_value, $actual_val);
	
										if ( $term_id === false) {
											if( !isset( $actual_val[$edited_value] ) ) {
												continue;
											}
											$term_id = intval( $edited_value );
										}
										$term_ids[] = $term_id;
									}							
								}
								if (!empty($term_ids)) {
									$result = wp_set_object_terms($id, $term_ids, $update_column);
								}
	    					}
						}
					}
				}

				//Code for updating the meta tables
				if (!empty($meta_data_edited)) {

					foreach ($meta_data_edited as $update_table => $update_params) {

						if (empty($update_params)) continue;

						$post_ids = array_keys($update_params);
						$meta_keys_edited = (!empty($meta_keys_edited)) ? array_keys($meta_keys_edited) : '';

						$update_table_key = ''; //pkey for the update table

						if ( $update_table == 'postmeta' ) {
							$update_table_key = 'post_id';
						}

						//Code for getting the old values and meta_ids
						$old_meta_data = $this->get_meta_data($post_ids, $meta_keys_edited, $update_table, $update_table_key);

						$meta_data = array();

						if (!empty($old_meta_data)) {
							foreach ($old_meta_data as $key => $old_values) {
								foreach ($old_values as $data) {
									if ( empty($meta_data[$key]) ) {
										$meta_data[$key] = array();
									}
									$meta_data[$key][$data['meta_key']] = array();
									$meta_data[$key][$data['meta_key']]['meta_id'] = $data['meta_id'];
									$meta_data[$key][$data['meta_key']]['meta_value'] = $data['meta_value'];
								}
							}
						}

						$meta_index = 0;
						$insert_meta_index = 0;
						$index=0;
						$insert_index=0;
						$old_post_id = '';
						$update_params_index = 0;

						//Code for generating the query
						foreach ($update_params as $id => $updated_data) {

							$updated_data_index = 0;
							$update_params_index++;

							foreach ($updated_data as $key => $value) {
								
								$key = wp_unslash($key);
			    				$value = wp_unslash($value);
			    				$meta_type = 'post';
			    				if ( $update_table == 'postmeta' ) {
			    					$value = sanitize_meta( $key, $value, 'post' );	
			    				}
								
								$updated_data_index++;

								// Filter whether to update metadata of a specific type.
								$check = apply_filters( "update_{$meta_type}_metadata", null, $id, $key, $value, '' );
								if ( null !== $check ) {
									continue;
								}

								if( is_numeric( $value ) ) {
									$value = strval( $value );
								}

								// Code for handling if the meta key does not exist
								if ( empty($meta_data[$id][$key] ) ) {

									// Filter whether to add metadata of a specific type.
									$check = apply_filters( "add_{$meta_type}_metadata", null, $id, $key, $value, false );
									if ( null !== $check ) {
										continue;
									}

									if ( empty($insert_params_meta[$update_table]) ) {
										$insert_params_meta[$update_table] = array();
										$insert_params_meta[$update_table][$insert_meta_index] = array();
										$insert_params_meta[$update_table][$insert_meta_index]['values'] = array();
									}

									if ( $insert_index >= 5 ) { //Code to have not more than 5 value sets in single insert query
										$insert_index=0;
										$insert_meta_index++;							
									}

									$insert_params_meta[$update_table][$insert_meta_index]['values'][] = array('id' => $id,
																												'meta_key' => $key,
																												'meta_value' => $value);

									$value = maybe_serialize( $value );

									if ( empty($insert_params_meta[$update_table][$insert_meta_index]['query']) ) {
										$insert_params_meta[$update_table][$insert_meta_index]['query'] = "(".$id.", '".$key."', '".$value."')";
									} else {
										$insert_params_meta[$update_table][$insert_meta_index]['query'] .= ", (".$id.", '".$key."', '".$value."')";
									}

									$insert_index++;

									continue;

								} else {
									//Checking if edited value is same as old value
									if ( $meta_data[$id][$key]['meta_value'] === $value ) {	
										unset($meta_data[$id][$key]);
										if( empty($meta_data[$id]) ) {
											unset($meta_data[$id]);
										}
										continue;
									} else {
										$meta_data[$id][$key]['meta_value'] = $value;
									}
								}

								$value = maybe_serialize( $value );

								if ( empty($update_params_meta[$update_table]) ) {
									$update_params_meta[$update_table] = array();
									$update_params_meta[$update_table][$meta_index] = array();
									$update_params_meta[$update_table][$meta_index]['ids'] = array();
									$update_params_meta[$update_table][$meta_index]['query'] = '';
								}

								if ( $index >= 5 && $old_post_id != $id ) {
									$update_params_meta[$update_table][$meta_index]['query'] .= ' ELSE meta_value END END ';
									$index=0;
									$meta_index++;							
								}					

								if ( empty($update_params_meta[$update_table][$meta_index]['query']) ) {
									$update_params_meta[$update_table][$meta_index]['query'] = ' CASE post_id ';
								}

								if ( $old_post_id != $id ) {
									
									if ( !empty($index) ) {
										$update_params_meta[$update_table][$meta_index]['query'] .= ' ELSE meta_value END ';
									}

									$update_params_meta[$update_table][$meta_index]['query'] .= " WHEN '".$id."' THEN 
																						CASE meta_key ";

									$old_post_id = $id;
									$update_params_meta[$update_table][$meta_index]['ids'][] = $id;

									$index++;
								}

								$update_params_meta[$update_table][$meta_index]['query'] .= " WHEN '".$key."' THEN '". $value ."' ";

								//Code for the last condition
								if ( $update_params_index == sizeof($update_params) &&  $updated_data_index == sizeof($updated_data) ) {
									$update_params_meta[$update_table][$meta_index]['query'] .= ' ELSE meta_value END END ';
								}

							}
						}

						// Start here... update the actions and query in for loop
						if ( !empty($insert_params_meta) ) {
							foreach ($insert_params_meta as $insert_table => $data) {

								if ( empty($data) ) {
									continue;
								}

								$insert_table_key = 'post_id';

								foreach ( $data as $insert_params ) {

									if ( empty($insert_params['values']) || empty($insert_params['query']) ) {
										continue;
									}

									$insert_meta_query = "INSERT INTO {$wpdb->prefix}".$insert_table." (".$insert_table_key.",meta_key,meta_value)
															 VALUES ".$insert_params['query'];

									if ( $insert_table == 'postmeta' ) {
										// function to replicate wordpress add_metadata()
										$this->sm_add_post_meta('post', $insert_params['values'], $insert_meta_query);

									} else {
										$result_insert_meta = $wpdb -> query($insert_meta_query);
									}
								}
							}	
						}

						// Inline data updation for meta tables
						if ( !empty( $update_params_meta ) ) {
							foreach ( $update_params_meta as $update_table => $data ) {

								if ( empty( $data ) ) {
									continue;
								}

								$update_table_key = (empty($update_table_key)) ? 'post_id' : $update_table_key;

								foreach ( $data as $update_params ) {

									if ( empty($update_params['ids']) || empty($update_params['query']) ) {
										continue;
									}

									$update_meta_query = "UPDATE {$wpdb->prefix}$update_table
														SET meta_value = ".$update_params['query']."
														WHERE $update_table_key IN (".implode(',',$update_params['ids']).")";

									if ( $update_table == 'postmeta' ) {
										// function to replicate wordpress update_postmeta()
										$this->sm_update_post_meta('post', $update_params['ids'], $meta_data, $update_meta_query);

									} else {
										$result_update_meta = $wpdb -> query($update_meta_query);
									}
								}
							}	
						}
						
					}
				}

				//Code for updating the posts table
				if ( !empty( $data_col_params['posts_fields'] ) ) {
					foreach( $data_col_params['posts_fields'] as $post_params ){
						wp_update_post( $post_params );
					}
				}
			}

			do_action('sm_inline_update_post',$edited_data, $data_col_params);

			delete_transient('sm_beta_skip_delete_dashboard_transients', 1, DAY_IN_SECONDS); // for preventing delete dashboard transients

			$msg_str = '';

			if ( sizeof($edited_data) > 1 ) {
				$msg_str = 's';
			}

			if( isset( $this->req_params['pro'] ) && empty( $this->req_params['pro'] ) ) {
				$sm_inline_update_count = get_option( 'sm_inline_update_count', 0 );
				$sm_inline_update_count += sizeof($edited_data);
				update_option( 'sm_inline_update_count', $sm_inline_update_count );
				$resp = array( 'sm_inline_update_count' => $sm_inline_update_count,
								'msg' => sprintf( esc_html__( '%d record%s updated successfully!', 'smart-manager-for-wp-e-commerce'), sizeof( $edited_data ), $msg_str ) );
								
				$msg = json_encode($resp);
			} else {
				$msg = sprintf( esc_html__( '%d record%s updated successfully!', 'smart-manager-for-wp-e-commerce' ), sizeof( $edited_data ), $msg_str );
				
			}

			echo $msg;
			exit;
		}

		// Function to replicate wordpress add_metadata()
		// Chk if the function can be made static
		public function sm_add_post_meta($meta_type = 'post', $insert_values = array(), $insert_meta_query = '', $insert_table_key = 'post_id') {

			global $wpdb;

			if ( empty($insert_values) ) {
				return;
			}

			$insert_query_values = array();

			// Code for executing actions pre insert
			foreach ( $insert_values as $insert_value ) {
				do_action( "add_{$meta_type}_meta", $insert_value['id'], $insert_value['meta_key'], $insert_value['meta_value'] );
				
				if( empty($insert_meta_query) ) {
					$insert_query_values[] = " ( ". $insert_value['id'] .", '". $insert_value['meta_key'] ."', '". $insert_value['meta_value'] ."' ) ";
				}
			}

			if( empty($insert_meta_query) && !empty($insert_query_values) ) {
				$insert_meta_query = "INSERT INTO {$wpdb->prefix}". $meta_type ."meta(". $insert_table_key .", meta_key, meta_value) VALUES ". implode(",", $insert_query_values);
			}
			

			//Code for inserting the values
			$result_insert_meta = $wpdb->query($insert_meta_query);

			$mid = '';

			// Code for executing actions pre insert
			foreach ( $insert_values as $insert_value ) {
				
				if ( empty($first_insert_id) ) {
					$mid = $wpdb->insert_id;
				}

				wp_cache_delete($insert_value['id'], $meta_type . '_meta');
				do_action( "added_{$meta_type}_meta", $mid, $insert_value['id'], $insert_value['meta_key'], $insert_value['meta_value'] );

				$mid++;

			}
			return;
		}

		// Function to replicate wordpress update_postmeta()
		// Chk if the function can be made static
		public function sm_update_post_meta($meta_type = 'post', $update_ids = array(), $meta_data = array(), $update_meta_query = '', $update_table_key = 'post_id') {
			
			global $wpdb;

			if ( empty($update_ids) || empty($meta_data) ) {
				return;
			}

			$update_query_values = $update_query_ids = array();

			// Code for executing actions pre update
			foreach ( $update_ids as $id ) {
				
				if ( empty($meta_data[$id]) ) {
					continue;
				}

				$meta_key_update_values = '';

				foreach ( $meta_data[$id] as $meta_key => $value ) {

					do_action( "update_{$meta_type}_meta", $value['meta_id'], $id, $meta_key, $value['meta_value'] );
					$meta_value = maybe_serialize( $value['meta_value'] );

					if ( 'post' == $meta_type ) {
						do_action( 'update_postmeta', $value['meta_id'], $id, $meta_key, $value['meta_value'] );
					}

					if( empty($update_meta_query) ) {
						$meta_key_update_values .= " WHEN '". $meta_key ."' THEN '". $value['meta_value'] ."' ";
					}
				}

				if( empty($update_meta_query) && !empty($meta_key_update_values) ) {
					$update_query_ids[] = $id;
					$update_query_values[] = " WHEN '". $id ."' THEN CASE meta_key ". $meta_key_update_values ." ELSE meta_value END ";
				}
			}

			if( empty($update_meta_query) && !empty($update_query_values) ) {
				$update_meta_query = "UPDATE {$wpdb->prefix}". $meta_type ."meta SET meta_value = CASE ". $update_table_key ." ". implode(" ",$update_query_values) ." END 
									WHERE ". $update_table_key ." IN (". implode(",", $update_query_ids) ." ) ";
			}

			if( empty($update_meta_query) ) {
				return;
			}

			$result_update_meta = $wpdb -> query($update_meta_query);

			// Code for executing actions post update
			foreach ( $update_ids as $id ) {
				
				if ( empty($meta_data[$id]) ) {
					continue;
				}

				wp_cache_delete($id, $meta_type . '_meta');

				foreach ( $meta_data[$id] as $meta_key => $value ) {

					do_action( "updated_{$meta_type}_meta", $value['meta_id'], $id, $meta_key, $value['meta_value'] );
					$meta_value = maybe_serialize( $value['meta_value'] );

					if ( 'post' == $meta_type ) {
						do_action( 'updated_postmeta', $value['meta_id'], $id, $meta_key, $meta_value );
					}
					
				}
			}
			return;
		}

		// Function to handle the delete data functionality
		public function delete() {

			global $wpdb;

			$delete_ids = (!empty($this->req_params['ids'])) ? json_decode(stripslashes($this->req_params['ids']), true) : array();

			if (empty($delete_ids)) return;

			$deleter = apply_filters( 'sm_deleter', null, array( 'source' => $this ) );

			$is_callable = false;
			if ( ! empty( $deleter ) ) {
				if ( ! empty( $deleter['callable'] ) ) {
					if ( is_array( $deleter['callable'] ) ) {
						if ( is_callable( $deleter['callable'] ) ) {
							$is_callable = true;
						}
					} elseif ( is_string( $deleter['callable'] ) ) {
						if ( function_exists( $deleter['callable'] ) ) {
							$is_callable = true;
						}
					}
				}
				if ( ! empty( $deleter['delete_ids'] ) ) {
					$delete_ids = $deleter['delete_ids'];
				}
			}

			// Code for delete the data
			foreach ( $delete_ids as $delete_id ) {
				if ( true === $is_callable ) {
					call_user_func_array( $deleter['callback'], array( $delete_id ) );
				} else {
					wp_trash_post( $delete_id );
				}
			}

			$msg_str = '';

			if ( sizeof($delete_ids) > 1 ) {
				$msg_str = 's';
			}


			echo sprintf( esc_html__( '%d record%s deleted successfully!', 'smart-manager-for-wp-e-commerce' ), sizeof( $delete_ids ), $msg_str );

			exit;
		}

		/**
		 * Function to check if 'trash' records are to be shown or not
		 *
		 * @return boolean flag for whether 'trash' records are to be shown or not
		 */
		public function is_show_trash_records(){
			return ( 'yes' === get_option( 'sm_view_'.$this->dashboard_key.'_trash_records' ) || 'yes' === get_option( 'sm_view_trash_records' ) ) ? true : false;
		}

	}
	// $GLOBALS['smart_manager_base'] = Smart_Manager_Base::getInstance();
	// if ( !isset( $GLOBALS['smart_manager_base'] ) ) {
	// }
}
