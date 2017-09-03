<?php
/** MY Course Widget Widget
  * Objective:
  *		1.To list out course categories
**/
class MY_CourseCategory_Widget extends WP_Widget {
	#1.constructor
	function __construct() {
		$widget_options = array("classname"=>'widget_course_category', 'description'=>'To list out course categories');
		parent::__construct(false,IAMD_THEME_NAME.__(' Course Categories','dt_themes'),$widget_options);
	}
	
	#2.widget input form in back-end
	function form($instance) {
		$instance = wp_parse_args( (array) $instance,array('title'=>'', 'show_empty'=>'') );
		$title = strip_tags($instance['title']);
		$show_empty = !empty($instance['show_empty']) ? strip_tags($instance['show_empty']) : false;
		?>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','dt_themes');?> 
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>
           
        <p>
        	<input type="checkbox"  id="<?php echo $this->get_field_id('show_empty');?>" name="<?php echo $this->get_field_name('show_empty');?>"<?php checked($show_empty); ?> />
			<?php _e("Show Empty",'dt_themes');?>
        </p>  
		<?php
	}
	#3.processes & saves the twitter widget option
	function update( $new_instance,$old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['show_empty'] = !empty($new_instance['show_empty']) ? 1 : 0;
		return $instance;
	}
	
	#4.output in front-end
	function widget($args, $instance) {
		
		extract($args);
		
		global $post;
		
		$title = empty($instance['title']) ?'' : apply_filters('widget_title', $instance['title']);
		$show_empty = ($instance['show_empty'] == 1) ? 0:1;

		echo $before_widget;
		
 	    if(!empty($title)) {
			echo $before_title.$title.$after_title;
		}
		
		echo '<div class="dt-course-category-widget">';		
           	$cats = get_categories('taxonomy=course_category&hide_empty='.$show_empty);
			if(isset($cats)) {
				echo '<ul>';
				foreach($cats as $cat) {
					$id = $cat->term_id;
					$title = $cat->name;
					echo '<li><a href="'.get_term_link($id).'">'.$title.'</a></li>';
				}
				echo '</ul>';
			}
	 	echo '</div>';	
				 
		echo $after_widget;
		
	}
}?>