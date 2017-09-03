<?php
if (! class_exists ( 'DTCoreCustomPostTypes' )) {
	
	/**
	 *
	 * @author iamdesigning11
	 *        
	 */
	class DTCoreCustomPostTypes {
		function __construct() {
			
			/* Portfolio Custom Post Type */
			require_once plugin_dir_path ( __FILE__ ) . '/dt-portfolio-post-type.php';
			if (class_exists ( 'DTPortfolioPostType' )) {
				
				new DTPortfolioPostType ();
			}
			
			/* Teachers Custom Post Type */
			require_once plugin_dir_path ( __FILE__ ) . '/dt-teachers-post-type.php';
			if (class_exists ( 'DTTeachersPostType' )) {				
				new DTTeachersPostType ();
			}
			
			$theme_options = get_option ( 'mytheme' );
			$theme_options_general = $theme_options['general'];
			 
			if(!array_key_exists('disable-theme-default-courses',$theme_options_general)) {

				/* Classes Custom Post Type */
				require_once plugin_dir_path ( __FILE__ ) . '/dt-classes-post-type.php';
				if (class_exists ( 'DTClassesPostType' )) {				
					new DTClassesPostType ();
				}

				/* Courses Custom Post Type */
				require_once plugin_dir_path ( __FILE__ ) . '/dt-courses-post-type.php';
				if (class_exists ( 'DTCoursesPostType' )) {				
					new DTCoursesPostType ();
				}
				
				/* Lesson Custom Post Type */
				require_once plugin_dir_path ( __FILE__ ) . '/dt-lessons-post-type.php';
				if (class_exists ( 'DTLessonsPostType' )) {				
					new DTLessonsPostType ();
				}
				
				/* Quizes Custom Post Type */
				require_once plugin_dir_path ( __FILE__ ) . '/dt-quizes-post-type.php';
				if (class_exists ( 'DTQuizesPostType' )) {				
					new DTQuizesPostType ();
				}
				
				/* Questions Custom Post Type */
				require_once plugin_dir_path ( __FILE__ ) . '/dt-questions-post-type.php';
				if (class_exists ( 'DTQuestionsPostType' )) {				
					new DTQuestionsPostType ();
				}
				
				/* Assignemnts Custom Post Type */
				require_once plugin_dir_path ( __FILE__ ) . '/dt-assignments-post-type.php';
				if (class_exists ( 'DTAssignmentsPostType' )) {				
					new DTAssignmentsPostType ();
				}
				
				/* Grading Custom Post Type */
				require_once plugin_dir_path ( __FILE__ ) . '/dt-gradings-post-type.php';
				if (class_exists ( 'DTGradingsPostType' )) {				
					new DTGradingsPostType ();
				}
				
				/* Payments History Custom Post Type */
				require_once plugin_dir_path ( __FILE__ ) . '/dt-payments-post-type.php';
				if (class_exists ( 'DTPaymentsPostType' )) {				
					new DTPaymentsPostType ();
				}
				
				/* All Certificates Custom Post Type */
				require_once plugin_dir_path ( __FILE__ ) . '/dt-certificates-post-type.php';
				if (class_exists ( 'DTCertificatesPostType' )) {				
					new DTCertificatesPostType ();
				}
				
				/* Alter Taxonomy to Radio Button */
				require_once plugin_dir_path ( __FILE__ ) . '/dt-alter-taxonomy-to-radio-button.php';
				if (class_exists ( 'DT_RadioButtonTaxonomy' )) {
					new DT_RadioButtonTaxonomy ('lesson_complexity');
				}	
				
				require_once plugin_dir_path ( __FILE__ ) . 'functions.php';
				require_once plugin_dir_path ( __FILE__ ) . 'woo-functions.php';
				require_once plugin_dir_path ( __FILE__ ) . 'utils.php';					
			
			}
						
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
			
			if(!array_key_exists('disable-theme-default-courses',$theme_options_general)) {
				
				// Add Hook into the 'admin_init()' action
				add_action ( 'admin_menu', array (
						$this,
						'dt_admin_menu' 
				) );
			
			}
			
			add_filter ( 'ws_plugin__s2member_add_meta_boxes_excluded_types', array (
					$this,
					's2_meta_box_exclude_post_types'
			) );
			
			add_action( 'parent_file', array (
				$this,
				'dt_change_active_menu'
			) );
			
		}
		
		/**
		 * A function hook to exclude certain post types from having s2member metabox
		 */
		function s2_meta_box_exclude_post_types($excluded_types = array(), $vars = array())
		{
			
			$exclude_these_types = array('dt_quizes', 'dt_questions', 'dt_gradings', 'dt_payments', 'dt_certificates', 'dt_assignments');
			return array_merge($excluded_types, $exclude_these_types);
	
		}	
		
		/**
		 * A function hook that the WordPress core launches at 'init' points
		 */
		function dt_init() {
			wp_enqueue_script ( 'dt-knob-script', plugin_dir_url ( __FILE__ ) . 'js/jquery.knob.js', array (), false, true );
			wp_enqueue_script ( 'dt-knob-custom-script', plugin_dir_url ( __FILE__ ) . 'js/jquery.knob.custom.js', array (), false, true );
			wp_enqueue_script ( 'dt-jquery-print', plugin_dir_url ( __FILE__ ) . 'js/jquery.print.js', array (), false, true );
			wp_enqueue_script ( 'dt-custom-script', plugin_dir_url ( __FILE__ ) . 'js/dt.custom.js', array (), false, true );
			wp_localize_script('dt-custom-script', 'object', array(
					'quizTimeout' => __('Timeout!', 'dt_themes'),
					'noResult' => __('No Results Found!', 'dt_themes'),
					'noGraph' => __('No enough data to generate graph!', 'dt_themes'),
					'onRefresh' => __('Refreshing this quiz page will mark this session as completed.', 'dt_themes'),
					'registrationSuccess' => __('You have successfully registered with our class!', 'dt_themes'),
					'locationAlert1' => __('To get GPS location please fill address.', 'dt_themes'),
					'locationAlert2' => __('Please add latitude and longitude', 'dt_themes')
				));
			$googlemapApiKey = dttheme_option('general', 'googlemap-api-key') ? dttheme_option('general', 'googlemap-api-key') : '';
			wp_enqueue_script ( 'dt-map','https://maps.googleapis.com/maps/api/js?key='.$googlemapApiKey,array(),false,true );
		}
		
		/**
		 * A function hook that the WordPress core launches at 'admin_init' points
		 */
		function dt_admin_init() {
			require_once plugin_dir_path ( __FILE__ ) . 'dt-class-registrations.php';
			require_once plugin_dir_path ( __FILE__ ) . 'dt-payments.php';
			require_once plugin_dir_path ( __FILE__ ) . 'dt-statistics.php';
			require_once plugin_dir_path ( __FILE__ ) . 'dt-settings.php';
			wp_enqueue_style ( 'dt-custom-post-css', plugin_dir_url ( __FILE__ ) . 'css/styles.css' );
			wp_enqueue_style ( 'dt-chosen-css', plugin_dir_url ( __FILE__ ) . 'css/chosen.css' );
			wp_enqueue_script ( 'dt-chosen-jquery', plugin_dir_url ( __FILE__ ) . 'js/chosen.jquery.min.js', array (), false, true );
			wp_enqueue_script ( 'dt-chart', plugin_dir_url ( __FILE__ ) . 'js/chart.js', array (), false, true );
			wp_enqueue_script ( 'dt-metabox-script', plugin_dir_url ( __FILE__ ) . 'js/dt.metabox.js', array (), false, true );
			$googlemapApiKey = dttheme_option('general', 'googlemap-api-key') ? dttheme_option('general', 'googlemap-api-key') : '';
			wp_enqueue_script ( 'dt-map','https://maps.googleapis.com/maps/api/js?key='.$googlemapApiKey,array(),false,true );
		}
		
		/**
		 * A function hook that the WordPress core launches at 'admin_menu' points
		 */
		function dt_admin_menu() {
			add_menu_page( __('Learning Management System','dt_themes'), __('Courses','dt_themes'), 'edit_posts', 'dt_lms', 'dt_lms_dashboard', 'dashicons-book', 9 );
			add_submenu_page( 'dt_lms', 'Course Category', 'Course Category', 'edit_posts', 'edit-tags.php?taxonomy=course_category&post_type=dt_courses' );
			add_submenu_page( 'dt_lms', 'Lesson Complexity', 'Lesson Complexity', 'edit_posts', 'edit-tags.php?taxonomy=lesson_complexity&post_type=dt_lessons' ); 
			add_submenu_page( 'dt_lms', 'Class Registrations', 'Class Registrations', 'manage_options', 'dt-classregistrations-options', 'dt_classregistrations_options' ); 
			add_submenu_page( 'dt_lms', 'Statistics', 'Statistics', 'manage_options', 'dt-statistics-options', 'dt_statistics_options' ); 
			add_submenu_page( 'dt_lms', 'Payments', 'Payments', 'manage_options', 'dt-payment-options', 'dt_payment_options' ); 
			add_submenu_page( 'dt_lms', 'Settings', 'Settings', 'manage_options', 'dt-settings-options', 'dt_settings_options' ); 
		}		
		
		/**
		 * A function hook that the WordPress core launches at 'parent_file' points
		 */
		function dt_change_active_menu($parent_file){
			global $submenu_file, $current_screen;
			$taxonomy = $current_screen->taxonomy;
			if ($taxonomy == 'course_category') {
				$submenu_file = 'edit-tags.php?taxonomy=course_category&post_type=dt_courses';
				$parent_file = 'dt_lms';
			}
			if ($taxonomy == 'lesson_complexity') {
				$submenu_file = 'edit-tags.php?taxonomy=lesson_complexity&post_type=dt_lessons';
				$parent_file = 'dt_lms';
			}
			return $parent_file;		
		}		
		
	}
}
?>