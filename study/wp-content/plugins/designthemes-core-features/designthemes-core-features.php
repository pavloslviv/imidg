<?php
/*
 * Plugin Name:	DesignThemes Core Features Plugin 
 * URI: 	http://wedesignthemes.com/plugins/designthemes-core-features 
 * Description: A simple wordpress plugin designed to implements <strong>core features of DesignThemes</strong> 
 * Version: 	1.8
 * Author: 		DesignThemes 
 * Author URI:	http://themeforest.net/user/designthemes
 */
if (! class_exists ( 'DTCorePlugin' )) {
	
	/**
	 * Basic class to load Shortcodes & Custom Posts
	 *
	 * @author iamdesigning11
	 */
	class DTCorePlugin {

		function __construct() {
			
			// Add Hook into the 'init()' action
			add_action ( 'init', array ( $this,'dtLoadPluginTextDomain') );

			// Register Shortcodes
			require_once plugin_dir_path ( __FILE__ ) . '/shortcodes/register-shortcodes.php';
			
			if (class_exists ( 'DTCoreShortcodes' )) {
				$dt_core_shortcodes = new DTCoreShortcodes();
			}
			
			// Register Custom Post Types
			require_once plugin_dir_path ( __FILE__ ) . '/custom-post-types/register-post-types.php';
			
			if (class_exists ( 'DTCoreCustomPostTypes' )) {
				$dt_core_custom_posts = new DTCoreCustomPostTypes();
			}
			
			// Register Page Builder
			require_once plugin_dir_path ( __FILE__ ) . '/page-builder/register-page-builder.php';
			
			if (class_exists ( 'DTCorePageBuilder' )) {
				$dt_core_page_builder = new DTCorePageBuilder ();
			}
			
			// Add Hook into the 'admin_init()' action
			add_action ( 'admin_init', array ($this,'dt_admin_init') );
			
			require_once plugin_dir_path ( __FILE__ ) . '/functions.php';
		}
		
		/**
		 * To load text domain
		 */
		function dtLoadPluginTextDomain() {
			load_plugin_textdomain ( 'dt_themes', false, dirname ( plugin_basename ( __FILE__ ) ) . '/languages/' );
		}
		
		function dt_admin_init() {
			wp_enqueue_script( 'dt-admin-script', plugin_dir_url ( __FILE__ ) . 'js/admin.js', array (), false, true );
		}
		
		/**
		 */
		public static function dtCorePluginActivate() {
		}
		
		/**
		 */
		public static function dtCorePluginDectivate() {
		}
	}
}

if (class_exists ( 'DTCorePlugin' )) {
	
	register_activation_hook ( __FILE__, array ('DTCorePlugin','dtCorePluginActivate') );
	register_deactivation_hook ( __FILE__, array ('DTCorePlugin','dtCorePluginDectivate') );
	
	if( !function_exists( 'dttheme_option' ) ){
		function dttheme_option($key1, $key2 = '') {
			$options = get_option ( 'mytheme' );
			$output = NULL;
		
			if (is_array ( $options )) {
		
				if (array_key_exists ( $key1, $options )) {
					$output = $options [$key1];
					if (is_array ( $output ) && ! empty ( $key2 )) {
						$output = (array_key_exists ( $key2, $output ) && (! empty ( $output [$key2] ))) ? $output [$key2] : NULL;
					}
				} else {
					$output = $output;
				}
			}
			return $output;
		}
	}
	
	if( !function_exists( 'dttheme_is_plugin_active' ) ){
		function dttheme_is_plugin_active($plugin) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if (is_plugin_active($plugin) || is_plugin_active_for_network($plugin)) return true;
			else return false;
		}
	}
	
	if(!function_exists('dt_get_attachment_id_from_url')) {
		function dt_get_attachment_id_from_url( $attachment_url = '' ) {
		 
			global $wpdb;
			$attachment_id = false;
		 
			if ($attachment_url == '')
				return false;
		 
			$upload_dir_paths = wp_upload_dir();
		 
			if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
		 
				$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
				$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
		 
				$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
		 
			}
		 
			return $attachment_id;
			
		}
	}
	
	$dt_core_plugin = new DTCorePlugin();
	
}
?>