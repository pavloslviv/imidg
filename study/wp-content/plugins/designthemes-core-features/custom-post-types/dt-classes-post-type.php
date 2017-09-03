<?php
if (! class_exists ( 'DTClassesPostType' )) {
	class DTClassesPostType {
				
		/**
		 */
		function __construct() {
			// Add Hook into the 'init()' action
			add_action ( 'init', array (
					$this,
					'dt_init' 
			) );
			
			// Add Hook into the 'admin_init()' action
			add_action ( 'admin_init', array (
					$this,
					'dt_admin_init' 
			) );
			
			add_filter ( 'template_include', array (
					$this,
					'dt_template_include' 
			) );
			
		}
		
		/**
		 * A function hook that the WordPress core launches at 'init' points
		 */
		function dt_init() {
			$this->createPostType ();
			add_action ( 'save_post', array (
					$this,
					'save_post_meta' 
			) );
		}
		
		/**
		 * A function hook that the WordPress core launches at 'admin_init' points
		 */
		function dt_admin_init() {
			wp_enqueue_script ( 'jquery-ui-sortable' );
						
			remove_filter( 'manage_posts_custom_column', 'likeThisDisplayPostLikes');
			
			add_action ( 'add_meta_boxes', array (
					$this,
					'dt_add_class_meta_box' 
			) );
			
			if(dttheme_is_plugin_active('woocommerce/woocommerce.php')) {
				
				$payment_method = dttheme_option('general','payment-method');
				
				if($payment_method == 'woocommerce') {
				
					add_action ( 'add_meta_boxes', array (
							$this,
							'dt_class_product_metabox' 
					) );
					
					if(dttheme_yith_subscription_plugin_active()) {
						
						add_action ( 'add_meta_boxes', array (
								$this,
								'dt_class_subscription_product_metabox' 
						) );
						
					}
					
				}
				
			}
			
			if(dttheme_is_plugin_active('the-events-calendar/the-events-calendar.php')) {
				
				add_action ( 'add_meta_boxes', array (
						$this,
						'dt_class_events_calendar_metabox' 
				) );
				
			}
			
			add_filter ( "manage_edit-dt_classes_columns", array (
					$this,
					"dt_classes_edit_columns" 
			) );
			
			add_action ( "manage_posts_custom_column", array (
					$this,
					"dt_classes_columns_display" 
			), 10, 2 );
		}
		
		/**
		 */
		function createPostType() {
			
			if(dttheme_option('dt_class','single-class-slug') != '') $class_slug = trim(stripslashes(dttheme_option('dt_class','single-class-slug')));
			else $class_slug = 'classes';
			
			$labels = array (
					'name' => __ ( 'Classes', 'dt_themes' ),
					'all_items' => __ ( 'All Classes', 'dt_themes' ),
					'singular_name' => __ ( 'Class', 'dt_themes' ),
					'add_new' => __ ( 'Add New', 'dt_themes' ),
					'add_new_item' => __ ( 'Add New Class', 'dt_themes' ),
					'edit_item' => __ ( 'Edit Class', 'dt_themes' ),
					'new_item' => __ ( 'New Class', 'dt_themes' ),
					'view_item' => __ ( 'View Class', 'dt_themes' ),
					'search_items' => __ ( 'Search Classes', 'dt_themes' ),
					'not_found' => __ ( 'No Classes found', 'dt_themes' ),
					'not_found_in_trash' => __ ( 'No Classes found in Trash', 'dt_themes' ),
					'parent_item_colon' => __ ( 'Parent Class:', 'dt_themes' ),
					'menu_name' => __ ( 'Classes', 'dt_themes' ) 
			);
			
			$args = array (
					'labels' => $labels,
					'hierarchical' => false,
					'description' => 'This is custom post type classes',
					'supports' => array (
							'title',
							'editor',
							'excerpt',
							'author',
							'comments',
							'page-attributes',
							'thumbnail'
					),
					
					'public' => true,
					'show_ui' => true,
					'show_in_menu' => 'dt_lms',
					
					'show_in_nav_menus' => true,
					'publicly_queryable' => true,
					'exclude_from_search' => false,
					'has_archive' => true,
					'query_var' => true,
					'can_export' => true,
					'rewrite' => array( 'slug' => $class_slug, 'hierarchical' => true, 'with_front' => false ),
					'capability_type' => 'post' 
			);
			
			register_post_type ( 'dt_classes', $args );
			
		}
		
		/**
		 */
		function dt_add_class_meta_box() {
			add_meta_box ( "dt-class-default-metabox", __ ( 'Classes Options', 'dt_themes' ), array (
					$this,
					'dt_default_metabox' 
			), 'dt_classes', "normal", "default" );
		}
		
		function dt_class_product_metabox() {
			add_meta_box ( "dt-class-product-metabox", __ ( 'WooCommerce Product', 'dt_themes' ), array (
					$this,
					'dttheme_class_product_metabox_function' 
			), 'dt_classes', "side", "core" );
		}
		
		function dt_class_subscription_product_metabox() {
			add_meta_box ( "dt-class-subscription-product-metabox", __ ( 'WooCommerce Subscription Product', 'dt_themes' ), array (
					$this,
					'dttheme_class_subscription_product_metabox_function' 
			), 'dt_classes', "side", "core" );
		}
		
		function dt_class_events_calendar_metabox() {
			add_meta_box ( "dt-class-events-calendar-metabox", __ ( 'Class Events', 'dt_themes' ), array (
					$this,
					'dttheme_class_events_calendar_metabox_function' 
			), 'dt_classes', "side", "core" );
		}
		
		/**
		 */
		function dt_default_metabox() {
			include_once plugin_dir_path ( __FILE__ ) . 'metaboxes/dt_class_default_metabox.php';
		}
		
		function dttheme_class_product_metabox_function() {
			include_once plugin_dir_path ( __FILE__ ) . 'metaboxes/dt_class_product_metabox.php';
		}
		
		function dttheme_class_subscription_product_metabox_function() {
			include_once plugin_dir_path ( __FILE__ ) . 'metaboxes/dt_class_subscription_product_metabox.php';
		}
		
		function dttheme_class_events_calendar_metabox_function() {
			include_once plugin_dir_path ( __FILE__ ) . 'metaboxes/dt_class_events_calendar_metabox.php';
		}
		
		/**
		 *
		 * @param unknown $columns
		 * @return multitype:
		 */
		function dt_classes_edit_columns($columns) {
			$newcolumns = array (
				"cb" => "<input type=\"checkbox\" />",
				"dt_class_thumb" => "Image",
				"title" => "Title",
				"date" => "Date"
			);
			
			if(dttheme_is_plugin_active('woocommerce/woocommerce.php')) {
				
				$payment_method = dttheme_option('general','payment-method');
				
				if($payment_method == 'woocommerce') {
				
					$newcolumns['woocommerce-product'] = 'WooCommerce Product';
					
					if(dttheme_yith_subscription_plugin_active()) {
						
						$newcolumns['woocommerce-subscription-product'] = 'WooCommerce Subscription Product';
						
					}
					
				}
				
			}
			
			$columns = array_merge ( $newcolumns, $columns );
			return $columns;
		}
		
		/**
		 *
		 * @param unknown $columns
		 * @param unknown $id        	
		 */
		function dt_classes_columns_display($columns, $id) {
			global $post;
			
			switch ($columns) {
				
				case "dt_class_thumb":
				    $image = wp_get_attachment_image(get_post_thumbnail_id($id), array(75,75));
					if(!empty($image))
					  	echo $image;
					else
						echo '<img src="http'.dttheme_ssl().'://placehold.it/75x75" alt="'.$id.'" />';
				break;

				case "woocommerce-product":
					$product_id = get_post_meta( $id, 'dt-class-product-id', true );
					if($product_id != '') {
						echo get_the_title($product_id);
					}
				break;
				
				case "woocommerce-subscription-product":
					$product_ids = get_post_meta( $id, 'dt-class-subscription-product-id', true );
					if(!empty($product_ids) && isset($product_ids)) {
						foreach($product_ids as $product_id) {
							if($product_id != '') {
								echo get_the_title($product_id)."<br>";
							}
						}
					}
				break;
				
			}
		}
		
		/**
		 */
		function save_post_meta($post_id) {
			
			if ( ! isset( $_POST['dtcore_plugin_class_metabox_nonce'] ) ) {
				return;
			}
		
			if ( ! wp_verify_nonce( $_POST['dtcore_plugin_class_metabox_nonce'], 'dtcore_plugin_class_metabox' ) ) {
				return;
			}
		
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
		
			if ( isset( $_POST['post_type'] ) && 'dt_classes' == $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
				}
			} else {
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}

			if ( (key_exists('post_type', $_POST)) && ('dt_classes' == $_POST['post_type']) ) {
				
				if( isset( $_POST ['dt-class-type'] ) && $_POST ['dt-class-type'] != ''){
					update_post_meta ( $post_id, "dt-class-type", stripslashes ( $_POST ['dt-class-type'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-type" );
				}
				
				if( isset( $_POST ['dt-class-teacher'] ) && $_POST ['dt-class-teacher'] != ''){
					update_post_meta ( $post_id, "dt-class-teacher", stripslashes ( $_POST ['dt-class-teacher'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-teacher" );
				}
				
				if( isset( $_POST ['dt-class-featured'] ) && $_POST ['dt-class-featured'] != ''){
					update_post_meta ( $post_id, "dt-class-featured", stripslashes ( $_POST ['dt-class-featured'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-featured" );
				}
				
				if( isset( $_POST ['dt-class-subtitle'] ) && $_POST ['dt-class-subtitle'] != ''){
					update_post_meta ( $post_id, "dt-class-subtitle", stripslashes ( $_POST ['dt-class-subtitle'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-subtitle" );
				}
				
				if( isset( $_POST ['dt-class-maintabtitle'] ) && $_POST ['dt-class-maintabtitle'] != ''){
					update_post_meta ( $post_id, "dt-class-maintabtitle", stripslashes ( $_POST ['dt-class-maintabtitle'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-maintabtitle" );
				}
				
				if( isset( $_POST ['dt-class-price'] ) && $_POST ['dt-class-price'] != ''){
					update_post_meta ( $post_id, "dt-class-price", stripslashes ( $_POST ['dt-class-price'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-price" );
				}
				
				if( isset( $_POST ['dt-class-content-options'] ) && $_POST ['dt-class-content-options'] != '' ) {
					update_post_meta ( $post_id, "dt-class-content-options", stripslashes ( $_POST ['dt-class-content-options'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-content-options" );
				}
				
				if( isset( $_POST ['dt-class-content-title'] ) && $_POST ['dt-class-content-title'] != '' ) {
					update_post_meta ( $post_id, "dt-class-content-title", stripslashes ( $_POST ['dt-class-content-title'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-content-title" );
				}
				
				if( isset( $_POST ['dt-class-courses'] ) && $_POST ['dt-class-courses'] != '' ) {
					update_post_meta ( $post_id, "dt-class-courses", array_filter ( $_POST ['dt-class-courses'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-courses" );
				}
				
				if( isset( $_POST ['dt-class-timetable-sc'] ) && $_POST ['dt-class-timetable-sc'] != '' ) {
					update_post_meta ( $post_id, "dt-class-timetable-sc", stripslashes ( $_POST ['dt-class-timetable-sc'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-timetable-sc" );
				}
				
				if( isset( $_POST ['dt-class-start-date'] ) && $_POST ['dt-class-start-date'] != ''){
					update_post_meta ( $post_id, "dt-class-start-date", stripslashes ( $_POST ['dt-class-start-date'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-start-date" );
				}
				
				if( isset( $_POST ['dt-class-capacity'] ) && $_POST ['dt-class-capacity'] != ''){
					update_post_meta ( $post_id, "dt-class-capacity", stripslashes ( $_POST ['dt-class-capacity'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-capacity" );
				}
				
				if( isset( $_POST ['dt-class-disable-purchases-regsitration'] ) && $_POST ['dt-class-disable-purchases-regsitration'] != ''){
					update_post_meta ( $post_id, "dt-class-disable-purchases-regsitration", stripslashes ( $_POST ['dt-class-disable-purchases-regsitration'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-disable-purchases-regsitration" );
				}
				
				if( isset( $_POST ['dt-class-enable-purchases'] ) && $_POST ['dt-class-enable-purchases'] != ''){
					update_post_meta ( $post_id, "dt-class-enable-purchases", stripslashes ( $_POST ['dt-class-enable-purchases'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-enable-purchases" );
				}
				
				if( isset( $_POST ['dt-class-enable-registration'] ) && $_POST ['dt-class-enable-registration'] != ''){
					update_post_meta ( $post_id, "dt-class-enable-registration", stripslashes ( $_POST ['dt-class-enable-registration'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-enable-registration" );
				}
				
				if( isset( $_POST ['dt-class-shyllabus-preview'] ) && $_POST ['dt-class-shyllabus-preview'] != ''){
					update_post_meta ( $post_id, "dt-class-shyllabus-preview", stripslashes ( $_POST ['dt-class-shyllabus-preview'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-shyllabus-preview" );
				}
				
				if( isset( $_POST ['dt-class-address'] ) && $_POST ['dt-class-address'] != ''){
					update_post_meta ( $post_id, "dt-class-address", stripslashes ( $_POST ['dt-class-address'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-address" );
				}
				
				if( isset( $_POST ['dt-class-gps'] ) && $_POST ['dt-class-gps'] != ''){
					update_post_meta ( $post_id, "dt-class-gps", array_filter ( $_POST ['dt-class-gps'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-gps" );
				}
				

				if( isset( $_POST ['dt-class-accessories-tabtitle'] ) && $_POST ['dt-class-accessories-tabtitle'] != ''){
					update_post_meta ( $post_id, "dt-class-accessories-tabtitle", stripslashes ( $_POST ['dt-class-accessories-tabtitle'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-accessories-tabtitle" );
				}

				if( isset( $_POST ['dt-class-accessories-icon'] ) && $_POST ['dt-class-accessories-icon'] != ''){
					update_post_meta ( $post_id, "dt-class-accessories-icon", array_filter ( $_POST ['dt-class-accessories-icon'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-accessories-icon" );
				}
				
				if( isset( $_POST ['dt-class-accessories-label'] ) && $_POST ['dt-class-accessories-label'] != ''){
					update_post_meta ( $post_id, "dt-class-accessories-label", array_filter ( $_POST ['dt-class-accessories-label'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-accessories-label" );
				}
				
				if( isset( $_POST ['dt-class-accessories-value'] ) && $_POST ['dt-class-accessories-value'] != ''){
					update_post_meta ( $post_id, "dt-class-accessories-value", array_filter ( $_POST ['dt-class-accessories-value'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-accessories-value" );
				}

				if( isset( $_POST ['dt-class-tabs-title'] ) && $_POST ['dt-class-tabs-title'] != ''){
					update_post_meta ( $post_id, "dt-class-tabs-title", array_filter ( $_POST ['dt-class-tabs-title'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-tabs-title" );
				}
				
				if( isset( $_POST ['dt-class-tabs-content'] ) && $_POST ['dt-class-tabs-content'] != ''){
					update_post_meta ( $post_id, "dt-class-tabs-content", array_filter ( $_POST ['dt-class-tabs-content'] ) );
				} else {
					delete_post_meta ( $post_id, "dt-class-tabs-content" );
				}
								
				if( isset( $_POST ['dt-class-product-id'] ) && $_POST ['dt-class-product-id'] != '' ) {
					update_post_meta ( $post_id, 'dt-class-product-id',  $_POST ['dt-class-product-id'] );
				} else {
					delete_post_meta ( $post_id, 'dt-class-product-id' );
				}
				
				if($_POST ['dt-class-type'] == 'online') {
					
					if( isset( $_POST ['dt-class-subscription-product-id'] ) && $_POST ['dt-class-subscription-product-id'] != '' ) {
						update_post_meta ( $post_id, 'dt-class-subscription-product-id',  $_POST ['dt-class-subscription-product-id'] );
					} else {
						delete_post_meta ( $post_id, 'dt-class-subscription-product-id' );
					}
				
				} else {
				
					delete_post_meta ( $post_id, 'dt-class-subscription-product-id' );
					
				}

				if( isset( $_POST ['dt-class-event-id'] ) && $_POST ['dt-class-event-id'] != '' ) {
					update_post_meta ( $post_id, 'dt-class-event-id',  $_POST ['dt-class-event-id'] );
				} else {
					delete_post_meta ( $post_id, 'dt-class-event-id' );
				}
				
			}

		}
		
		/**
		 * To load class pages in front end
		 *
		 * @param string $template        	
		 * @return string
		 */
		function dt_template_include($template) {
			if (is_singular( 'dt_classes' )) {
				if (! file_exists ( get_stylesheet_directory () . '/single-dt_classes.php' )) {
					$template = plugin_dir_path ( __FILE__ ) . 'templates/single-dt_classes.php';
				}
			} elseif( is_post_type_archive('dt_classes') ) {
				if (! file_exists ( get_stylesheet_directory () . '/archive-dt_classes.php' )) {
					$template = plugin_dir_path ( __FILE__ ) . 'templates/archive-dt_classes.php';
				}
			}
			return $template;
		}
	}
}
?>