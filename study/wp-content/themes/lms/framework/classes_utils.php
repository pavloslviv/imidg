<?php
add_action( 'wp_ajax_dttheme_show_class_contents', 'dttheme_show_class_contents' );
add_action( 'wp_ajax_nopriv_dttheme_show_class_contents', 'dttheme_show_class_contents' );
function dttheme_show_class_contents(){
	
	$post_id = $_REQUEST['postid'];
	
	$classes_page_type = isset($_REQUEST['classes_page_type']) ? $_REQUEST['classes_page_type'] : '';
	
	if($classes_page_type == 'archive' || $classes_page_type == 'tax-archive') {
		
		$post_layout = dttheme_option('dt_class','archives-post-layout'); 
		$post_layout = !empty($post_layout) ? $post_layout : "one-half-column";
		
		$page_layout = dttheme_option('dt_class','archives-layout'); 
		$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";
		
		$post_per_page = get_option('posts_per_page');
	
	} else if($classes_page_type == 'shortcode') {
		
		$tpl_default_settings = get_post_meta( $post_id, '_tpl_default_settings', TRUE );
		$tpl_default_settings = is_array( $tpl_default_settings ) ? $tpl_default_settings  : array();
						
		if($GLOBALS['force_enable'] == true) {
			$page_layout = $GLOBALS['page_layout'];
		} else {
			$page_layout = array_key_exists( "layout", $tpl_default_settings ) ? $tpl_default_settings['layout'] : "content-full-width";
		}
		
		$post_layout = isset($_REQUEST['postlayout']) ? $_REQUEST['postlayout'] : "one-half-column";
		$post_per_page = isset($_REQUEST['postperpage']) ? $_REQUEST['postperpage'] : -1;
		
	} else {
		
		$tpl_default_settings = get_post_meta( $post_id, '_tpl_default_settings', TRUE );
		$tpl_default_settings = is_array( $tpl_default_settings ) ? $tpl_default_settings  : array();
				
		$post_layout = array_key_exists( "classes-post-layout", $tpl_default_settings ) ? $tpl_default_settings['classes-post-layout'] : "one-half-column";
		$post_per_page = isset($tpl_default_settings['classes-post-per-page']) ? $tpl_default_settings['classes-post-per-page'] : -1;
		
		if($GLOBALS['force_enable'] == true) {
			$page_layout = $GLOBALS['page_layout'];
		} else {
			$page_layout = array_key_exists( "layout", $tpl_default_settings ) ? $tpl_default_settings['layout'] : "content-full-width";
		}
	
	}
	
	
	if(defined('ICL_LANGUAGE_CODE') && !empty($_REQUEST['lang']))
	{
		global $sitepress;
		$sitepress->switch_lang($_REQUEST['lang'], true);
	}
	
	$grid_view = $list_view = $layout_class = $post_class = $post_thumbnail = '';
	
	switch($post_layout):
	
		case 'one-half-column';
			$post_class = "column dt-sc-one-half";
			$firstcnt = 2;
			$grid_view = 'active';
			$post_thumbnail = 'blogcourse-two-column';
			if($page_layout == 'with-left-sidebar' || $page_layout == 'with-right-sidebar') $post_thumbnail = 'course-two-column';
			else $post_thumbnail = 'blogcourse-two-column';
		break;
	
		case 'one-third-column':
			$post_class = "column dt-sc-one-third";
			$firstcnt = 3;
			$grid_view = 'active';
			$post_thumbnail = 'blogcourse-three-column';
		break;
	
	endswitch;
	
	switch ( $page_layout ) {
		case 'with-left-sidebar':
		case 'with-right-sidebar':
			$post_thumbnail .= "-single-sidebar";
		break;
	
		case 'both-sidebar':
			$post_thumbnail .= "-both-sidebar";
		break;
	}

	$curr_page = isset($_REQUEST['curr_page']) ? $_REQUEST['curr_page'] : 1;
	$offset = isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0;
	$view_type = isset($_REQUEST['view_type']) ? $_REQUEST['view_type'] : 'grid';
	$class_type = isset($_REQUEST['class_type']) ? $_REQUEST['class_type'] : 'all';
	$class_item_type = isset($_REQUEST['class_item_type']) ? $_REQUEST['class_item_type'] : 'all';
	
	/* Change b/w list and grid view */
	if( isset($view_type) && $view_type === "list" ) {
		$layout_class = "class-list-view";
		$firstcnt = 1;
		$list_view = 'active';
		$grid_view = '';
	} elseif( isset($view_type) && $view_type === "grid" ) {
		$layout_class = '';
		$grid_view = 'active';
		$list_view = '';
	} 
	
	/* Configured all datas here to access in ajax function */
	echo '<span id="dt-class-datas" data-postid="'.$post_id.'" data-view_type="'.$view_type.'" data-postperpage="'.$post_per_page.'" data-postlayout="'.$post_layout.'" data-curr_page="'.$curr_page.'" data-offset="'.$offset.'" data-class_type="'.$class_type.'" data-class_item_type="'.$class_item_type.'" style="display:none;"></span>';


	if($class_item_type != 'popular') {
		
		$args_cnt = array( 'posts_per_page' => -1,'post_type' => 'dt_classes','meta_query'=>array(), 'orderby' => 'menu_order', 'order' => 'ASC');
		if($class_type == 'onsite') {
			
			$args_cnt['meta_query'][] = array(
							'key'     => 'dt-class-type',
							'value'   => 'onsite',
							'compare' => '='
							);
							
		} else if($class_type == 'online') {
			
			$args_cnt['meta_query'][] = array(
							'key'     => 'dt-class-type',
							'value'   => 'online',
							'compare' => '='
							);
							
		}
		if($class_item_type == 'featured') {
			
			$args_cnt['meta_query'][] = array(
							'key'     => 'dt-class-featured',
							'compare' => 'EXISTS'
							);
								
		}
		$classes_posts = get_posts($args_cnt);
		$class_cnt = count($classes_posts);
			
		
		$args = array( 'offset'=>$offset, 'paged' => $curr_page ,'posts_per_page' => $post_per_page,'post_type' => 'dt_classes','meta_query'=>array(), 'orderby' => 'menu_order', 'order' => 'ASC');
	
		if($class_type == 'onsite') {
			
			$args['meta_query'][] = array(
							'key'     => 'dt-class-type',
							'value'   => 'onsite',
							'compare' => '='
							);
							
		} else if($class_type == 'online') {
			
			$args['meta_query'][] = array(
							'key'     => 'dt-class-type',
							'value'   => 'online',
							'compare' => '='
							);
							
		}
		
		if($class_item_type == 'featured') {
			
			$args['meta_query'][] = array(
							'key'     => 'dt-class-featured',
							'compare' => 'EXISTS'
							);
								
		}
			
		$class_query = new WP_Query($args);
		if( $class_query->have_posts() ): 
			echo '<div class="dt-sc-results-found">'.esc_html__('Records Found', 'dt_themes').' : <span>'.$class_query->found_posts.'</span></div>';
			$i = 1;
			while( $class_query->have_posts() ): $class_query->the_post();
			
				$class_loop_id = get_the_ID();
				
				$temp_class = '';
				if($i == 1) { $temp_class = $post_class.' first'; } else { $temp_class = $post_class; }
				if($i == $firstcnt) { $i = 1; } else { $i = $i + 1; }
								
				$class_type = get_post_meta($class_loop_id, 'dt-class-type', true);
				$dt_class_subtitle = get_post_meta($class_loop_id, 'dt-class-subtitle', true);
				$dt_class_content_options = get_post_meta($class_loop_id, 'dt-class-content-options', true);
				$dt_class_courses = get_post_meta($class_loop_id, 'dt-class-courses', true);
				$dt_class_start_date = get_post_meta($class_loop_id, 'dt-class-start-date', true);
				
				$dt_class_disable_purchases_regsitration = get_post_meta($class_loop_id, 'dt-class-disable-purchases-regsitration', true);
				$dt_class_enable_purchases = get_post_meta($class_loop_id, 'dt-class-enable-purchases', true);
				$dt_class_enable_registration = get_post_meta($class_loop_id, 'dt-class-enable-registration', true);
				
				$dt_class_featured = get_post_meta($class_loop_id, 'dt-class-featured', true);
				
				$dt_class_content_options = ($dt_class_content_options != '') ? $dt_class_content_options : 'course';
				
				if($class_type == 'online') {
					$class_type_icon = '<i class="fa fa-globe"></i>';	
				} else {
					$class_type_icon = '<i class="fa fa-building"></i>';	
				}
				
				$class_type_label = '';
				if($class_type != '') {
					$class_type_label = 'dt-'.$class_type.'-class';	
				}
				
				if( $grid_view == 'active' ) {
					echo '<div class="'.$temp_class.'">';
				}
				?>
				
				<article id="post-<?php echo $class_loop_id; ?>" class="<?php echo implode(" ", get_post_class('dt-sc-custom-class-type '.$class_type_label.' '.$layout_class, $class_loop_id)); ?>">
				
					<div class="dt-sc-class-thumb">
						
						<?php
						if($dt_class_featured == 'true') {
							?>
							<div class="featured-tag"><div><i class="fa fa-thumb-tack"></i><span><?php echo esc_html__('Featured', 'dt_themes'); ?></span></div></div>
							<?php
						}
						?>
						<a href="<?php echo the_permalink(); ?>" >
							<?php
							if(has_post_thumbnail()):
								$attachment_id = get_post_thumbnail_id($class_loop_id);
								$img_attributes = wp_get_attachment_image_src($attachment_id, $post_thumbnail);
								echo "<img src='".$img_attributes[0]."' width='".$img_attributes[1]."' height='".$img_attributes[2]."' />";
							endif;
							?>
						 </a>
						 <span class="dt-sc-class-type <?php echo $class_type; ?>"><?php echo $class_type_icon.$class_type; ?></span>
					</div>			
									
					<div class="dt-sc-class-details">	
					
						<?php
						if($dt_class_start_date != '') {
							?>
							<div class="dt-sc-class-startdate">
								<i class="fa fa-calendar"></i> 
								<?php echo date('F j, Y', strtotime($dt_class_start_date)); ?>
							</div>
							<?php
						}
						?>
						
						<h5><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h5>
						
						<?php
						if(function_exists('the_ratings') && !dttheme_option('general', 'disable-ratings-classes')) { 
							echo do_shortcode('[ratings id="'.$class_loop_id.'"]');
						}
						?>
					
						<?php
						if($dt_class_subtitle != '') {
							echo '<p>'.$dt_class_subtitle.'</p>';	
						}
						
						$payment_method = dttheme_option('general','payment-method');
						if($class_type == 'onsite') {
							
							if($dt_class_enable_purchases == 'true') {
								if($payment_method == 'woocommerce') {
									echo dttheme_get_class_details_linked_with_products($class_loop_id, 'archive');
								} else {
									echo dttheme_get_class_details_linked_with_s2member($class_loop_id, 'archive');
								}
							} else if($dt_class_enable_registration == 'true') {
								$seats_available = dttheme_get_onsite_class_seats_available($class_loop_id);
								if(dt_check_student_already_registered($class_loop_id)) {
									echo '<span class="dt-sc-class-price dt-sc-class-registration"><span class="amount">'.esc_html__('Registered', 'dt_themes').'</span></span>';	
								} else {
									if($seats_available > 0 || ($seats_available <= 0 && $dt_class_disable_purchases_regsitration != 'true')) {
										echo '<span class="dt-sc-class-price dt-sc-class-registration"><span class="amount">'.esc_html('Needs Registration', 'dt_themes').'</span></span>';
									} else {
										echo '<span class="dt-sc-class-price dt-sc-class-registration"><span class="amount">'.esc_html__('Registration Closed', 'dt_themes').'</span></span>';	
									}
								}
							}
								
						} else {
							
							if($payment_method == 'woocommerce') {
								echo dttheme_get_class_details_linked_with_products($class_loop_id, 'archive');
							} else {
								echo dttheme_get_class_details_linked_with_s2member($class_loop_id, 'archive');
							}
							
						}
						
						if($dt_class_content_options == 'course') {
							
							$dt_class_courses_cnt = 0;
							if(is_array($dt_class_courses) && !empty($dt_class_courses)) {
								$dt_class_courses_cnt = count($dt_class_courses);	
							}
	
							if($dt_class_courses_cnt > 0) {
								echo '<p class="dt-sc-total-courses-holder">'.$dt_class_courses_cnt.'&nbsp;'.__('Courses', 'dt_themes').'</p>';	
								echo '<div class="dt-sc-class-courses-list">';
									echo '<ul>';
									foreach($dt_class_courses as $dt_class_course) {
										echo '<li><a href="'.get_permalink($dt_class_course).'">'.get_the_title($dt_class_course).'</a></li>';
									}
									echo '</ul>';
								echo '</div>';
								if($dt_class_courses_cnt > 3) {
									echo '<a class="dt-sc-class-viewall-courses" href="#">'.esc_html__('View All Courses', 'dt_themes').'</a>';
								}
							}
						
						}
						?>
						
						<?php if($list_view == 'active') { ?>
							<div class="dt-sc-class-desc">
								<?php echo get_the_excerpt(); ?>
							</div>
						<?php } ?>
						
						<div class="dt-sc-view-class-holder">
							<a title="<?php echo get_the_title(); ?>" href="<?php echo the_permalink(); ?>" class="dt-sc-button small"> <?php echo __('View Class', 'dt_themes'); ?> </a>
						</div>
														
					</div>
				
				</article>
				
				<?php
				if( $grid_view == 'active' ) {
					echo '</div>';
				}
				?>    
			
				<?php 
				
			endwhile; 
		else:
			echo '<div class="dt-sc-info-box">'.__('No Classes Found!', 'dt_themes').'</div>';
		endif; 
		
		wp_reset_postdata();
		
		echo dtthemes_ajax_pagination($post_per_page, $curr_page, $class_cnt, $post_id);
		
	} else {
		
		/* Manually queried to list the popular courses based on wp-postratings(plugin) */
		
		global $wpdb;
		$table1 = $wpdb->prefix . "ratings";
		$table2 = $wpdb->prefix . "posts";
		$table3 = $wpdb->prefix . "term_relationships";
		$table4 = $wpdb->prefix . "term_taxonomy";
		
	
		if($classes_page_type == 'tax-archive') {
			
			$cp_qry1 = "SELECT b.* FROM $table1 a, $table2 b, $table3 tr, $table4 tt  WHERE a.rating_postid = b.ID and b.post_type='dt_classes' and b.post_status = 'publish' AND tt.term_id = {$post_id} AND tr.term_taxonomy_id = tt.term_taxonomy_id and b.ID =  tr.object_id group by a.rating_postid order by avg(a.rating_rating) desc";
			
			$cs_cnt = 0;
			$wp_class_cnt = $wpdb->get_results( $cp_qry1 );
			$cs_cnt = count($wp_class_cnt);
			
			if($post_per_page == -1 ) $post_per_page = $cs_cnt;
			
			$cp_qry2 = "SELECT b.* FROM $table1 a, $table2 b, $table3 tr, $table4 tt  WHERE a.rating_postid = b.ID and b.post_type='dt_classes' and b.post_status = 'publish' AND tt.term_id = {$post_id} AND tr.term_taxonomy_id = tt.term_taxonomy_id and b.ID =  tr.object_id group by a.rating_postid order by avg(a.rating_rating) desc LIMIT $offset, $post_per_page";
			
		} else {
			
			$cp_qry1 = "SELECT a.* FROM $table1 a, $table2 b WHERE a.rating_postid = b.ID and b.post_type='dt_classes' and b.post_status = 'publish' group by a.rating_postid order by avg(a.rating_rating) desc";
			
			$cs_cnt = 0;
			$wp_class_cnt = $wpdb->get_results( $cp_qry1 );
			$cs_cnt = count($wp_class_cnt);
			
			if($post_per_page == -1 ) $post_per_page = $cs_cnt;
			
			$cp_qry2 = "SELECT a.*, b.* FROM $table1 a, $table2 b WHERE a.rating_postid = b.ID and b.post_type='dt_classes' and b.post_status = 'publish' group by a.rating_postid order by avg(a.rating_rating) desc LIMIT $offset, $post_per_page";		
		
		}	
		
		$pholder = dttheme_option('general', 'disable-placeholder-images');
		
		$wp_class_qry = $wpdb->get_results( $cp_qry2 );
		
		$cs_num = 1;
		if(!empty($wp_class_qry)) {
			
			echo '<div class="dt-sc-results-found">'.esc_html__('Records Found', 'dt_themes').' : <span>'.$cs_cnt.'</span></div>';
			
			foreach($wp_class_qry as $class_item) :
				
				$class_loop_id = $class_item -> ID;
				
				$temp_class = '';
				if($cs_num == 1) { $temp_class = $post_class.' first'; } else { $temp_class = $post_class; }
				if($cs_num == $firstcnt) { $cs_num = 1; } else { $cs_num = $cs_num + 1; }
				
				$class_type = get_post_meta($class_loop_id, 'dt-class-type', true);
				$dt_class_subtitle = get_post_meta($class_loop_id, 'dt-class-subtitle', true);
				$dt_class_content_options = get_post_meta($class_loop_id, 'dt-class-content-options', true);
				$dt_class_courses = get_post_meta($class_loop_id, 'dt-class-courses', true);
				$dt_class_start_date = get_post_meta($class_loop_id, 'dt-class-start-date', true);
				
				$dt_class_disable_purchases_regsitration = get_post_meta($class_loop_id, 'dt-class-disable-purchases-regsitration', true);
				$dt_class_enable_purchases = get_post_meta($class_loop_id, 'dt-class-enable-purchases', true);
				$dt_class_enable_registration = get_post_meta($class_loop_id, 'dt-class-enable-registration', true);
				
				$dt_class_featured = get_post_meta($class_loop_id, 'dt-class-featured', true);
				
				$dt_class_content_options = ($dt_class_content_options != '') ? $dt_class_content_options : 'course';
				
				if($class_type == 'online') {
					$class_type_icon = '<i class="fa fa-globe"></i>';	
				} else {
					$class_type_icon = '<i class="fa fa-building"></i>';	
				}
				
				$class_type_label = '';
				if($class_type != '') {
					$class_type_label = 'dt-'.$class_type.'-class';	
				}
				
				if( $grid_view == 'active' ) {
					echo '<div class="'.$temp_class.'">';
				}
				?>
				<article id="post-<?php echo $class_loop_id; ?>" class="<?php echo implode(" ", get_post_class('dt-sc-custom-class-type '.$class_type_label.' '.$layout_class, $class_loop_id)); ?>">
				
					<div class="dt-sc-class-thumb">
						
						<?php
						if($dt_class_featured == 'true') {
							?>
							<div class="featured-tag"><div><i class="fa fa-thumb-tack"></i><span><?php echo esc_html__('Featured', 'dt_themes'); ?></span></div></div>
							<?php
						}
						?>
						<a href="<?php echo get_permalink($class_loop_id); ?>" >
							<?php
							if(has_post_thumbnail($class_loop_id)):
								$attachment_id = get_post_thumbnail_id($class_loop_id);
								$img_attributes = wp_get_attachment_image_src($attachment_id, $post_thumbnail);
								echo "<img src='".$img_attributes[0]."' width='".$img_attributes[1]."' height='".$img_attributes[2]."' />";
							endif;
							?>
						 </a>
						 <span class="dt-sc-class-type <?php echo $class_type; ?>"><?php echo $class_type_icon.$class_type; ?></span>
					</div>			
									
					<div class="dt-sc-class-details">	
					
						<?php
						if($dt_class_start_date != '') {
							?>
							<div class="dt-sc-class-startdate">
								<i class="fa fa-calendar"></i> 
								<?php echo date('F j, Y', strtotime($dt_class_start_date)); ?>
							</div>
							<?php
						}
						?>
						
						<h5><a href="<?php echo get_permalink($class_loop_id); ?>" title="<?php echo get_the_title($class_loop_id); ?>"><?php echo get_the_title($class_loop_id); ?></a></h5>
						
						<?php
						if(function_exists('the_ratings') && !dttheme_option('general', 'disable-ratings-classes')) { 
							echo do_shortcode('[ratings id="'.$class_loop_id.'"]');
						}
						?>
					
						<?php
						if($dt_class_subtitle != '') {
							echo '<p>'.$dt_class_subtitle.'</p>';	
						}
						
						$payment_method = dttheme_option('general','payment-method');
						if($class_type == 'onsite') {
							
							if($dt_class_enable_purchases == 'true') {
								if($payment_method == 'woocommerce') {
									echo dttheme_get_class_details_linked_with_products($class_loop_id, 'archive');
								} else {
									echo dttheme_get_class_details_linked_with_s2member($class_loop_id, 'archive');
								}
							} else if($dt_class_enable_registration == 'true') {
								$seats_available = dttheme_get_onsite_class_seats_available($class_loop_id);
								if(dt_check_student_already_registered($class_loop_id)) {
									echo '<span class="dt-sc-class-price dt-sc-class-registration"><span class="amount">'.esc_html__('Registered', 'dt_themes').'</span></span>';	
								} else {
									if($seats_available > 0 || ($seats_available <= 0 && $dt_class_disable_purchases_regsitration != 'true')) {
										echo '<span class="dt-sc-class-price dt-sc-class-registration"><span class="amount">'.esc_html('Needs Registration', 'dt_themes').'</span></span>';
									} else {
										echo '<span class="dt-sc-class-price dt-sc-class-registration"><span class="amount">'.esc_html__('Registration Closed', 'dt_themes').'</span></span>';	
									}
								}
							}
								
						} else {
							
							if($payment_method == 'woocommerce') {
								echo dttheme_get_class_details_linked_with_products($class_loop_id, 'archive');
							} else {
								echo dttheme_get_class_details_linked_with_s2member($class_loop_id, 'archive');
							}
							
						}
						
						if($dt_class_content_options == 'course') {
							
							$dt_class_courses_cnt = 0;
							if(is_array($dt_class_courses) && !empty($dt_class_courses)) {
								$dt_class_courses_cnt = count($dt_class_courses);	
							}
	
							if($dt_class_courses_cnt > 0) {
								echo '<p class="dt-sc-total-courses-holder">'.$dt_class_courses_cnt.'&nbsp;'.__('Courses', 'dt_themes').'</p>';	
								echo '<div class="dt-sc-class-courses-list">';
									echo '<ul>';
									foreach($dt_class_courses as $dt_class_course) {
										echo '<li><a href="'.get_permalink($dt_class_course).'">'.get_the_title($dt_class_course).'</a></li>';
									}
									echo '</ul>';
								echo '</div>';
								if($dt_class_courses_cnt > 3) {
									echo '<a class="dt-sc-class-viewall-courses" href="#">'.esc_html__('View All Courses', 'dt_themes').'</a>';
								}
							}
						
						}
						?>
						
						<?php if($list_view == 'active') { ?>
							<div class="dt-sc-class-desc">
								<?php echo get_the_excerpt(); ?>
							</div>
						<?php } ?>
						
						<div class="dt-sc-view-class-holder">
							<a title="<?php echo get_the_title($class_loop_id); ?>" href="<?php echo get_permalink($class_loop_id); ?>" class="dt-sc-button small"> <?php echo __('View Class', 'dt_themes'); ?> </a>
						</div>
														
					</div>
				
				</article>
				<?php
				if( $grid_view == 'active' ) {
					echo '</div>';
				}
				
				$cs_num++;
				
			endforeach;
			
			echo dtthemes_ajax_pagination($post_per_page, $curr_page, $cs_cnt, $post_id);
			
		} else {
			echo '<div class="dt-sc-info-box">'.__('No Classes Found!', 'dt_themes').'</div>';
		}		
		
	}
	
	die();
	
}

?>