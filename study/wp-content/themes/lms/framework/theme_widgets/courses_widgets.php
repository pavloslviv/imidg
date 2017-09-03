<?php
/** MY Course Widget Widget
  * Objective:
  *		1.To list out course items
**/
class MY_Course_Widget extends WP_Widget {
	#1.constructor
	function __construct() {
		$widget_options = array("classname"=>'widget_popular_entries', 'description'=>'To list out course items');
		parent::__construct(false,IAMD_THEME_NAME.__(' Course','dt_themes'),$widget_options);
	}
	
	#2.widget input form in back-end
	function form($instance) {
		$instance = wp_parse_args( (array) $instance,array('title'=>'','_post_count'=>'','_post_categories'=>'','_enable_course_image'=>1) );
		$title = strip_tags($instance['title']);
		$_post_count = !empty($instance['_post_count']) ? strip_tags($instance['_post_count']) : "-1";
		$_post_categories = !empty($instance['_post_categories']) ? $instance['_post_categories']: array();
		$_enable_course_image = isset($instance['_enable_course_image']) ? (bool) $instance['_enable_course_image'] : false;
		?>
        
        <!-- Form -->
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','dt_themes');?> 
		   <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" 
            type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
           
	    <p><label for="<?php echo $this->get_field_id('_post_categories'); ?>">
			<?php _e('Choose the categories you want to display (multiple selection possible)','dt_themes');?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('_post_categories').'[]';?>" 
            	name="<?php echo $this->get_field_name('_post_categories').'[]';?>" multiple="multiple">
                <option value=""><?php _e("Select",'dt_themes');?></option>
           	<?php $cats = get_categories('taxonomy=course_category&hide_empty=1');
			foreach ($cats as $cat):
				$id = esc_attr($cat->term_id);
				$selected = ( in_array($id,$_post_categories)) ? 'selected="selected"' : '';
				$title = esc_html($cat->name);
				echo "<option value='{$id}' {$selected}>{$title}</option>";
			endforeach;?>
            </select></p>

        <p><input type="checkbox"  id="<?php echo $this->get_field_id('_enable_course_image');?>" name="<?php echo $this->get_field_name('_enable_course_image');?>"
	         <?php checked($_enable_course_image); ?> /> <?php _e("Show Image",'dt_themes');?></p>  

	    <p><label for="<?php echo $this->get_field_id('_post_count'); ?>"><?php _e('No.of posts to show:','dt_themes');?></label>
		   <input id="<?php echo $this->get_field_id('_post_count'); ?>" name="<?php echo $this->get_field_name('_post_count'); ?>" value="<?php echo $_post_count?>" type="text" /></p>
        <!-- Form end-->
<?php
	}
	#3.processes & saves the twitter widget option
	function update( $new_instance,$old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['_post_count'] = strip_tags($new_instance['_post_count']);
		$instance['_post_categories'] = $new_instance['_post_categories'];
		$instance['_enable_course_image'] = !empty($new_instance['_enable_course_image']) ? 1 : 0;
	return $instance;
	}
	
	#4.output in front-end
	function widget($args, $instance) {
		extract($args);
		global $post;
		$title = empty($instance['title']) ?'' : apply_filters('widget_title', $instance['title']);
		$_post_count = (int) $instance['_post_count'];
		$_post_categories = "";
		if(!empty($instance['_post_categories']) && is_array($instance['_post_categories'])):
			$_post_categories =  array_filter($instance['_post_categories']);
		elseif(!empty($instance['_post_categories'])):
			$_post_categories = explode(",",$instance['_post_categories']);
		endif;
		
		
		$_enable_course_image = ($instance['_enable_course_image'] == 1) ? 1:0;

		$arg = array('posts_per_page' => $_post_count ,'post_type' => 'dt_courses', 'orderby' => 'menu_order', 'order' => 'ASC');
		$arg = empty($_post_categories) ? $arg : array(
											'posts_per_page'=> $_post_count,
											'tax_query'		=> array(array( 'taxonomy'=>'course_category', 'field'=>'id', 'operator'=>'IN', 'terms'=>$_post_categories ) ), 
											'orderby' => 'menu_order', 'order' => 'ASC');
		echo $before_widget;
 	    if ( !empty( $title ) ) echo $before_title.$title.$after_title;
		
		if( dttheme_is_plugin_active('designthemes-core-features/designthemes-core-features.php') ):
		
			echo "<div class='recent-course-widget'><ul>";		
				 $cw_query = new WP_Query($arg);
				 if($cw_query->have_posts()) :
				 while($cw_query->have_posts()):
					$cw_query->the_post();
					$title = ( strlen(get_the_title()) > 20 ) ? substr(get_the_title(),0,15)."..." :get_the_title();
					echo "<li>";
						if(1 == $_enable_course_image):
						
							if(has_post_thumbnail()):
								$image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'dt-course-widget');
								echo '<img src="'.$image_url[0].'" alt="'.get_the_title().'" width="'.$image_url[1].'" height="'.$image_url[2].'" />';
							else:
								echo '<img src="http'.dttheme_ssl().'://placehold.it/110x90&text=Image" alt="'.get_the_title().'" />';
							endif;
							 
						endif;
						
						echo "<h6><a href='".get_permalink()."'>{$title}</a></h6>";
						$course_settings = get_post_meta(get_the_ID(), '_course_settings');
						
						$cost = '';
						
						$payment_method = dttheme_option('general','payment-method');
						
						if($payment_method == 'woocommerce') {
							
							$dt_course_product_id = get_post_meta( get_the_ID(), 'dt-course-product-id', true );
							
							if(!empty($dt_course_product_id)) {
								
								$product = dttheme_get_product_object($dt_course_product_id);
								$woo_price = $product->get_price_html();
								
								if($woo_price != '') {
									$cost = $woo_price;
								} else {
									$cost = __('Free','dt_themes');
								}
								
							} else {
								$cost = __('Free','dt_themes');
							}
							
						} else {
				
							$starting_price = dttheme_wp_kses(get_post_meta(get_the_ID(), 'starting-price', true));
							if($starting_price != '') {
								if(dttheme_option('dt_course','currency-position') == 'after-price') { 
									$cost = $starting_price.dttheme_wp_kses(dttheme_option('dt_course','currency')); 
								} else {
									$cost = dttheme_wp_kses(dttheme_option('dt_course','currency')).$starting_price; 
								}
							} else {
								$cost = __('Free', 'dt_themes'); 
							}
						
						}
						
						echo '<span class="dt-sc-course-price">
									<span class="amount">';
										echo $cost;
							echo '</span>
							</span>';
						
					echo "</li>";
				 endwhile;
				 else:
					echo "<li>".__('No Course Entries found','dt_themes')."</li>";
				 endif;
				 wp_reset_postdata();
			echo "</ul></div>";		
		
		else:
			echo esc_html__('Please activate designthemes core feature plugin.','dt_themes');
		endif;
			 
		echo $after_widget;
	}
}?>