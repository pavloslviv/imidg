<?php

// Get lessons count
function dttheme_get_lessons_count($course_post_id) {

	$lesson_args = array('post_type' => 'dt_lessons', 'posts_per_page' => -1, 'post_status' => 'any', 'meta_key' => 'dt_lesson_course', 'meta_value' => $course_post_id );								
	$lessons_array = get_posts( $lesson_args );
	
	$count = count($lessons_array);
	
	return $count;
	
}

// Get lessons durations
function dttheme_get_lessons_durations($course_post_id, $style = '') {

	$lesson_args = array('post_type' => 'dt_lessons', 'posts_per_page' => -1, 'post_status' => 'any', 'meta_key' => 'dt_lesson_course', 'meta_value' => $course_post_id );								
	$lessons_array = get_posts( $lesson_args );
		
	$duration = 0;
	foreach($lessons_array as $lesson) {
		$lesson_data = get_post_meta($lesson->ID, '_lesson_settings');
		if(isset($lesson_data[0]['lesson-duration'])) {
			$duration = $duration + $lesson_data[0]['lesson-duration'];
		}
	}
	
	if($style == 'style2') {
		
		if($duration > 0) {
			$hours = floor($duration/60); 
			$mins = $duration % 60; 
			if(strlen($mins) == 1) $mins = '0'.$mins;
			if(strlen($hours) == 1) $hours = '0'.$hours;
			if($hours == 0) {
				$duration = '00 : '.$mins;
			} else {
				$duration = $hours . ' : ' . $mins; 				
			}
		}
	
	} else {
		
		if($duration > 0) {
			$hours = floor($duration/60); 
			$mins = $duration % 60; 
			if($hours == 0) {
				if(get_locale()=='uk'){$duration = $mins . __(' хв ', 'dt_themes');}else{$duration = $mins . __(' mins ', 'dt_themes');} 				
			} elseif($hours == 1) {
				$duration = $hours .  __(' hour ', 'dt_themes') . $mins . __(' mins ', 'dt_themes'); 				
			} else {
				$duration = $hours . __(' hours ', 'dt_themes') . $mins . __(' mins ', 'dt_themes'); 				
			}
		}
		
	}
	
	return $duration;
	
}

// Get s2member course details
function dttheme_get_course_details_linked_with_s2member($course_post_id, $page_type) {

	$out = '';
	
	$starting_price = dttheme_wp_kses(get_post_meta($course_post_id, 'starting-price', true));
	$s2_level = 'access_s2member_ccap_cid_'.$course_post_id;
	
	if(!current_user_can('administrator') && (dttheme_check_course_class_purhcase_status_s2member($course_post_id) || dttheme_check_is_s2member_level_user(true) || current_user_can($s2_level))) {
		
		$out .= '<div class="dt-sc-purchased-details">';
					$out .= '<span class="dt-sc-purchased"> <span class="fa fa fa-cart-arrow-down"> </span> '.__('Purchased','dt_themes').'</span>';
					$course_status = dt_get_users_course_status($course_post_id, '');
					if($course_status) {
						$out .= '<div class="dt-sc-course-completed"> <span class="fa fa-check-circle"> </span> '.__('Completed', 'dt_themes').'</div>';
					}
		$out .= '</div>';
		
	} else if($starting_price != '') {
		
		$price = $starting_price; 
		if(dttheme_option('dt_course','currency-position') == 'after-price') { 
			$price = $price.dttheme_wp_kses(dttheme_option('dt_course','currency')); 
		} else { 
			$price = dttheme_wp_kses(dttheme_option('dt_course','currency')).$price; 
		}
		
		if($page_type == 'single') {
			$purchase_link = dt_course_purchase_link($course_post_id);
			$out .= '<a href="'.esc_url($purchase_link).'" target="_self"  class="dt-sc-button small filled"><i class="fa fa-shopping-cart"></i> '.$price.' - '.__('Purchase Now','dt_themes').'</a>';
		} else {
			$out .= '<span class="dt-sc-course-price"><span class="amount">'.$price.'</span></span>';
		}
			
	} else {
		
		if($page_type == 'single') {
			
			$login_page_link = dttheme_get_page_permalink_by_its_template('tpl-login.php');
			if(!is_user_logged_in() && $login_page_link != '') {
				$out .= '<span class="dt-sc-purchased"><a href="'.$login_page_link.'" target="_self">'.__('Free','dt_themes').'</a></span>';
			} else {
				$out .= '<span class="dt-sc-purchased">'.__('Free','dt_themes').'</span>';
			}
		
		} else {
			$out .= '<span class="dt-sc-course-price"><span class="amount">'.__('Free','dt_themes').'</span></span>';
		}
		
	}
	
	return $out;
	
}


// Check to show course
function dttheme_check_to_show_course_content( $course_id ){

	$hide_contents = (dttheme_option('dt_course','hide-contents') != '') ? 'true' : 'false';
	
	if(dttheme_check_if_course_is_paid($course_id) || dttheme_check_if_class_is_paid($course_id)) {
		if($hide_contents == 'true') {
			return false;
		}
	}

	return true;
	
}

// Check if course is not free
function dttheme_check_if_course_is_paid( $course_id ){

	$payment_method = dttheme_option('general','payment-method');
	
	if($payment_method == 'woocommerce') {
		
		$dt_course_subscribed_products_ids = dttheme_get_course_subscription_product_ids($course_id);
		$dt_course_product_id = dttheme_get_course_product_id($course_id);

		if($dt_course_product_id != '' || !empty($dt_course_subscribed_products_ids)) {
			return true;	
		}
		
	} else {
		
		$starting_price = get_post_meta($course_id, 'starting-price', true);
		
		if($starting_price != '') {
			return true;	
		}

	}
	
	return false;
	
}

// Check if class is not free
function dttheme_check_if_class_is_paid( $course_id ){

	$payment_method = dttheme_option('general','payment-method');
	
	$class_ids = dttheme_get_course_classes_lists($course_id);
	
	if(!empty($class_ids)) {
		
		foreach($class_ids as $class_id) {
			
			if($payment_method == 'woocommerce') {
		
				$dt_class_product_id = dttheme_get_class_product_id($class_id);
				
				if($dt_class_product_id != '') {
					return true;	
				}
			
			} else {
				
				$starting_price = get_post_meta($class_id, 'dt-class-price', true);
				
				if($starting_price != '') {
					return true;	
				}
				
			}
		
		}
	
	}
	
	return false;
	
}

// Check if course is free
function dttheme_check_if_course_is_free( $course_id ){

	$payment_method = dttheme_option('general','payment-method');
	
	if($payment_method == 'woocommerce') {
		
		$dt_course_subscribed_products_ids = dttheme_get_course_subscription_product_ids($course_id);
		$dt_course_product_id = dttheme_get_course_product_id($course_id);

		if($dt_course_product_id == '' && empty($dt_course_subscribed_products_ids)) {
			return true;	
		}
		
	} else {
		
		$starting_price = get_post_meta($course_id, 'starting-price', true);
		
		if($starting_price == '') {
			return true;	
		}

	}
	
	return false;
	
}

// Check if user authorized to view course
function dttheme_check_if_user_authorized_to_view_course($course_id) {
	
	$payment_method = dttheme_option('general','payment-method');
	
	if($payment_method == 'woocommerce') {

		if(dttheme_check_if_user_subscribed_this_course($course_id) || dttheme_check_if_user_purchased_this_course($course_id) || dttheme_check_course_class_purhcase_status_product($course_id)) {
			return true;
		}
	
	} else {
		
		$s2_level = 'access_s2member_ccap_cid_'.$course_id;
		if(current_user_can($s2_level) || dttheme_check_is_s2member_level_user(true) || dttheme_check_course_class_purhcase_status_s2member($course_id)) {
			return true;
		}
		
	}
	
	return false;
	
}


// Single course ratings
function dttheme_get_single_course_page_ratings($course_id) {

	global $wpdb;
	
	$ratings_average = get_post_meta($course_id, 'ratings_average', true);
	$total_ratings = get_post_meta($course_id, 'ratings_users', true);
	$postratings_ratingstext = get_option('postratings_ratingstext');
	
	$one_stars_percent = $two_stars_percent = $three_stars_percent = $four_stars_percent = $five_stars_percent = 0;
	
	if($total_ratings > 0) {
	
		$one_stars_arr = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->ratings} WHERE 1=1 AND rating_postid = %d AND rating_rating = %d", $course_id, 1 ) );
		$one_stars = count($one_stars_arr);
		$one_stars_percent = floor(($one_stars/$total_ratings)*100);
	
		$two_stars_arr = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->ratings} WHERE 1=1 AND rating_postid = %d AND rating_rating = %d", $course_id, 2 ) );
		$two_stars = count($two_stars_arr);
		$two_stars_percent = floor(($two_stars/$total_ratings)*100);
	
		$three_stars_arr = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->ratings} WHERE 1=1 AND rating_postid = %d AND rating_rating = %d", $course_id, 3 ) );
		$three_stars = count($three_stars_arr);
		$three_stars_percent = floor(($three_stars/$total_ratings)*100);
	
		$four_stars_arr = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->ratings} WHERE 1=1 AND rating_postid = %d AND rating_rating = %d", $course_id, 4 ) );
		$four_stars = count($four_stars_arr);
		$four_stars_percent = floor(($four_stars/$total_ratings)*100);
	
		$five_stars_arr = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->ratings} WHERE 1=1 AND rating_postid = %d AND rating_rating = %d", $course_id, 5 ) );
		$five_stars = count($five_stars_arr);
		$five_stars_percent = floor(($five_stars/$total_ratings)*100);
	
	}
		
	echo '<div class="dt-sc-average-rating">
			<h2>'.$ratings_average.'</h2>
			'.do_shortcode('[ratings id="'.$course_id.'"]').'
			<span>'.$total_ratings.' '.esc_html__('ratings', 'dt_themes').'</span>
		</div>';
		
	echo '<ul class="dt-sc-ratings-breakup">
			<li>
				<span class="dt-sc-ratings-label">'.$postratings_ratingstext[0].'</span>
				<div class="dt-sc-ratings-percentage">
					<span style="width:'.$one_stars_percent.'%"></span>
				</div>
				<span>'.$one_stars.'</span>
			</li>
			<li>
				<span class="dt-sc-ratings-label">'.$postratings_ratingstext[1].'</span>
				<div class="dt-sc-ratings-percentage">
					<span style="width:'.$two_stars_percent.'%"></span>
				</div>
				<span>'.$two_stars.'</span>
			</li>
			<li>
				<span class="dt-sc-ratings-label">'.$postratings_ratingstext[2].'</span>
				<div class="dt-sc-ratings-percentage">
					<span style="width:'.$three_stars_percent.'%"></span>
				</div>
				<span>'.$three_stars.'</span>
			</li>
			<li>
				<span class="dt-sc-ratings-label">'.$postratings_ratingstext[3].'</span>
				<div class="dt-sc-ratings-percentage">
					<span style="width:'.$four_stars_percent.'%"></span>
				</div>
				<span>'.$four_stars.'</span>
			</li>
			<li>
				<span class="dt-sc-ratings-label">'.$postratings_ratingstext[4].'</span>
				<div class="dt-sc-ratings-percentage">
					<span style="width:'.$five_stars_percent.'%"></span>
				</div>
				<span>'.$five_stars.'</span>
			</li>
		</ul>';
		
}

// Singe course page widget left sidebar
function dttheme_get_single_course_page_widget_leftside($course_id) {
	
	// Course Group
	if(dttheme_is_plugin_active('buddypress/bp-loader.php')) {
		
		$course_group = get_post_meta( $course_id, 'dt_bp_course_group', true );
		
		if($course_group != '' && $course_group > 0) {
		
			echo '<aside class="widget dt_widget_course_group" id="dt-widget-course-group">
					<h3 class="widgettitle">'.esc_html__('Course Group', 'dt_themes').'<span></span></h3>
					<div class="course-group-widget">';
		
						if ( bp_has_groups( array('include' => $course_group) ) ) : 
							?>
							
							<ul id="groups-list" class="item-list">
							
								<?php while ( bp_groups() ) : bp_the_group(); ?>
								
									<li <?php bp_group_class(); ?>>
									
										<div class="item-avatar">
											<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=50&height=50' ); ?></a>
										</div>
										
										<div class="item">
											<div class="item-title"><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></div>
											<div class="item-meta"><span class="activity"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span></div>
											
											<div class="item-desc"><?php bp_group_description_excerpt(); ?></div>
											
												<?php do_action( 'bp_directory_groups_item' ); ?>
											
											</div>
											
											<div class="action">
											
												<?php do_action( 'bp_directory_groups_actions' ); ?>
											
											<div class="meta">
											
												<?php bp_group_type(); ?> / <?php bp_group_member_count(); ?>
											
											</div>
										</div>
										
										<div class="clear"></div>
										
									</li>
								
								<?php endwhile; ?>
							
							</ul>
							
							<?php
						endif;
					
				  echo '</div>
				</aside>';
	
		}
		
	}
	
	
	if(dttheme_is_plugin_active('the-events-calendar/the-events-calendar.php')) {
		
		$dt_course_event_id = get_post_meta( $course_id, 'dt-course-event-id', true );
		
		if(isset($dt_course_event_id) && !empty($dt_course_event_id)) {
			
			echo '<aside class="widget dt_widget_course_event" id="dt-widget-course-event">
					<h3 class="widgettitle">'.esc_html__('Course Event', 'dt_themes').'<span></span></h3>
					<div class="course-event-widget">';
			
					$args = array( 'posts_per_page'=>-1, 'post_type'=> 'tribe_events', 'post__in' => $dt_course_event_id );
					query_posts($args);
					if( have_posts() ):
						while( have_posts() ):
							the_post();
								
								$venue_details = tribe_get_venue_details();
								
								$has_venue = ( $venue_details ) ? ' vcard' : '';
								$has_venue_address = ( ! empty( $venue_details['address'] ) ) ? ' location' : '';
								?>
								
								<div class="tribe-events-day-time-slot">
														
									<?php if ( tribe_get_cost() ) : ?>
										<div class="tribe-events-event-cost">
											<span><?php echo tribe_get_cost( null, true ); ?></span>
										</div>
									<?php endif; ?>
									
									<h2 class="tribe-events-list-event-title summary">
										<a class="url" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title_attribute() ?>" rel="bookmark">
											<?php the_title() ?>
										</a>
									</h2>
									
									<div class="tribe-events-event-meta <?php echo esc_attr( $has_venue . $has_venue_address ); ?>">
									
										<div class="tribe-updated published time-details">
											<?php echo tribe_events_event_schedule_details(); ?>
										</div>
									
										<?php if ( $venue_details ) : ?>
											<div class="tribe-events-venue-details">
												<?php echo implode( ', ', $venue_details ); ?>
											</div>
										<?php endif; ?>
									
									</div>
									
									<?php echo tribe_event_featured_image( null, 'thumb' ); ?>
									
									<div class="tribe-events-list-event-description tribe-events-content description entry-summary">
										<?php echo tribe_events_get_the_excerpt(); ?>
										<a href="<?php echo esc_url( tribe_get_event_link() ); ?>" class="tribe-events-read-more" rel="bookmark"><?php esc_html_e( 'Find out more', 'the-events-calendar' ) ?> &raquo;</a>
									</div>
								
								</div>
								
								<?php
						endwhile;
					endif;
					wp_reset_query();
			
			  echo '</div>
			</aside>';
		
		}
	
	}
		
}

// Singe course page widget right sidebar
function dttheme_get_single_course_page_widget_rightside($course_id) {
	
	$course_settings = get_post_meta($course_id, '_course_settings', true);
	$course_settings = is_array( $course_settings ) ? $course_settings  : array();
	
	
	// Ratings
	if(function_exists('the_ratings') && !dttheme_option('general', 'disable-ratings-courses')) { 
	   dttheme_get_single_course_page_ratings($course_id);
	}
	
	// Social Sharer
	if(array_key_exists("show-social-share",$course_settings)) {
		
		echo '<div class="courses-share">';
			dttheme_social_bookmarks('sb-courses');
		echo '</div>';
		
	}
	
	// Student Enrolled Widget
	$course_students = dt_get_course_capabilities_id($course_id);
	$students_cnt = count($course_students);
	
	if($students_cnt > 0) {
		
		echo '<aside class="widget dt_widget_students_enrolled" id="dt-widget-students-enrolled">
				<h3 class="widgettitle">'.esc_html__('Students Enrolled', 'dt_themes').' ( '.$students_cnt.' )<span></span></h3>
				<div class="students-enrolled-widget">
					<ul class="dt-students-enrolled-list">';
					foreach($course_students as $student_id) {
						$student_info = get_userdata($student_id);
						echo '<li>
								'.get_avatar($student_id, 32).'<h5>'.$student_info->display_name.'</h5>
							</li>';
					}
			  echo '</ul>
				</div>
			</aside>';
		
	}

	
	// Media Attachments	
	if(dttheme_check_if_user_authorized_to_view_course($course_id) || dttheme_check_to_show_course_content($course_id)) {
		
		$media_attachments = get_post_meta($course_id, 'media-attachments', true);
		if(isset($media_attachments) && !empty($media_attachments)) {
			
			echo '<aside class="widget dt_widget_media_attachments" id="dt-widget-media-attachments">
					<h3 class="widgettitle">'.esc_html__('Media Attachments', 'dt_themes').'<span></span></h3>
					<div class="media-attachments-widget">';
					
						echo '<ul class="dt-sc-media-attachments">';
						foreach($media_attachments as $attachment_url) {
							if($attachment_url != '') {
								$attachment_id = dt_get_attachment_id_from_url($attachment_url);
								$attachment_title = get_the_title($attachment_id);
								if($attachment_title == '') $attachment_title = basename($attachment_url);
								echo '<li><a href="'.$attachment_url.'" target="_blank">'.$attachment_title.'</a></li>';
							}
						}
						echo '</ul>';
							
			  echo '</div>
				</aside>';
				
		}
			
	}
	
}

function dttheme_get_user_course_progress($course_id) {
	
	$user_id = get_current_user_id();
	
	$lesson_args = array('post_type' => 'dt_lessons', 'posts_per_page' => -1, 'meta_key' => 'dt_lesson_course', 'meta_value' => $course_id );
	$lessons_array = get_pages( $lesson_args );
	$total_lessons = count($lessons_array);
	
	$i = 0;
	if(isset($lessons_array) && !empty($lessons_array)) {		
	
		foreach($lessons_array as $lesson) {
			
			$lesson_id = $lesson->ID;
			$quiz_id = get_post_meta ($lesson_id, "lesson-quiz", true);
			if(!isset($quiz_id) || $quiz_id == '') {
				$quiz_id = -1;
			}
			
			$dt_gradings = dt_get_user_gradings_array($course_id, $lesson_id, $quiz_id, $user_id);
			$dt_grade_post = get_posts( $dt_gradings );
			
			if(isset($dt_grade_post[0])) {
				$dt_grade_post_id = $dt_grade_post[0]->ID;
				$user_status = get_post_meta($dt_grade_post_id, 'graded', true);
				if(isset($user_status) && $user_status != '') {
					$i++;
				}
			}
			
		}
	
	}
	
	$assignment_args = array('post_type' => 'dt_assignments', 'posts_per_page' => -1, 'meta_query'=>array());	
	$assignment_args['meta_query'][] = array( 'key' => 'assignment-course-evaluation', 'value' => '', 'compare' => '!=' );	
	$assignment_args['meta_query'][] = array( 'key' => 'dt-assignment-course', 'value' => $course_id, 'compare' => '=', 'type' => 'numeric' );
							
	$assignment_array = get_posts( $assignment_args );
	$total_assignments = count($assignment_array);
	
	$j = 0;
	foreach($assignment_array as $assignment) {
		$assignment_id = $assignment->ID;
		
		$dtgradings = array( 'post_type' => 'dt_gradings', 'meta_query'=>array() );
		$dtgradings['meta_query'][] = array( 'key' => 'dt-user-id', 'value' => $user_id, 'compare' => '=', 'type' => 'numeric' );
		$dtgradings['meta_query'][] = array( 'key' => 'dt-assignment-id', 'value' => $assignment_id, 'compare' => '=', 'type' => 'numeric' );
		$dtgradings['meta_query'][] = array( 'key' => 'grade-type', 'value' => 'assignment', 'compare' => '=' );
		$dtgradings['meta_query'][] = array( 'key' => 'graded', 'value' => '', 'compare' => '!=' );
		$dtgradings_post = get_posts( $dtgradings );
		
		if(isset($dtgradings_post) && !empty($dtgradings_post)) {
			$j++;
		}
		
	}
	
	$total_tasks = $total_lessons + $total_assignments;
	$total_tasks_completed = $i + $j;
	
	$tasks_completed_percentage = round(($total_tasks_completed/$total_tasks)*100, 2);
	
	if($tasks_completed_percentage == 100) {
		$bgcolor = 'rgb(155, 189, 60);';
		$bar_cls = 'dt-sc-standard';
	} else {
		if($tasks_completed_percentage < 50) {
			$bgcolor = 'rgb(232, 95, 79);';	
		} else {
			$bgcolor = 'rgb(245, 166, 39);';
		}
		$bar_cls = 'dt-sc-progress-striped active';
	}
	
	if($tasks_completed_percentage > 0) {
		
		$out = '<div class="dt-sc-progress '.$bar_cls.'">
					<div data-value="'.$tasks_completed_percentage.'" style="background-color: '.$bgcolor.'; width: '.$tasks_completed_percentage.'%;" class="dt-sc-bar">
						<div class="dt-sc-bar-text">'.esc_html__('Прогрес', 'dt_themes').'<span style="background-color:'.$bgcolor.'">'.$tasks_completed_percentage.'%</span></div>
					</div>
				</div>';
			
	} else {
		
		$out = '';
		
	}
	
	return $out;
	
}

function dttheme_get_user_course_result($course_id) {
	
	$course_percentage = dt_get_course_percentage($course_id, '', false);	
	
	if($course_percentage == 100) {
		$bgcolor = 'rgb(155, 189, 60);';
		$bar_cls = 'dt-sc-standard';
	} else {
		if($course_percentage < 50) {
			$bgcolor = 'rgb(232, 95, 79);';	
		} else {
			$bgcolor = 'rgb(245, 166, 39);';
		}
		$bar_cls = 'dt-sc-progress-striped active';
	}
	
	if($course_percentage > 0) {
		
		$out = '<div class="dt-sc-progress '.$bar_cls.'">
					<div data-value="'.$course_percentage.'" style="background-color: '.$bgcolor.'; width: '.$course_percentage.'%;" class="dt-sc-bar">
						<div class="dt-sc-bar-text">'.esc_html__('Course Result', 'dt_themes').'<span style="color:'.$bgcolor.'">'.$course_percentage.'%</span></div>
					</div>
				</div>';
			
	} else {
		
		$out = '';
		
	}
	
	return $out;
	
}

// Get user class status
function dt_get_users_class_status($class_id) {
	
	$compeleted_courses = 0;
	$class_courses = get_post_meta($class_id, 'dt-class-courses', true);
	$class_courses_cnt = count($class_courses);
	
	foreach($class_courses as $class_course) {
		$course_status = dt_get_users_course_status($class_course, '');
		if($course_status) {
			$compeleted_courses++;
		}
	}
	
	if($class_courses_cnt == $compeleted_courses) {
		return true;	
	}
	
	return false;

}

// Get s2member class details
function dttheme_get_class_details_linked_with_s2member($class_id, $page_type) {

	$out = '';
	
	$class_price = get_post_meta($class_id, 'dt-class-price', true);
	$s2_level = 'access_s2member_ccap_classid_'.$class_id;
	$seats_available = dttheme_get_onsite_class_seats_available($class_id);
	$dt_class_disable_purchases_regsitration = get_post_meta($class_id, 'dt-class-disable-purchases-regsitration', true);
	
	if (dttheme_check_is_s2member_level_user(true) || current_user_can($s2_level)){
		
		if($page_type == 'single') {
			
			$out .= '<div class="dt-sc-purchased-details">';
			
				$out .= '<span class="dt-sc-purchased"> <span class="fa fa fa-cart-arrow-down"> </span> '.__('Purchased','dt_themes').'</span>';
				
				$class_status = dt_get_users_class_status($class_id);
				if($class_status) {
					$out .= '<div class="dt-sc-course-completed"> <span class="fa fa-check-circle"> </span> '.__('Completed', 'dt_themes').'</div>';
				}
			
			$out .= '</div>';
			
		} else {
			
			$out .= '<span class="dt-sc-class-price dt-sc-class-amount"><span class="amount">'.__('Purchased','dt_themes').'</span></span>';
			
			$class_status = dt_get_users_class_status($class_id);
			if($class_status) {
				$out .= '<span class="dt-sc-class-price dt-sc-class-amount"><span class="amount">'.__('Completed', 'dt_themes').'</span></span>';
			}
		
		}
		
	} else {
		
		if($class_price != '') {
			
			if($seats_available > 0 || ($seats_available <= 0 && $dt_class_disable_purchases_regsitration != 'true')) {
				
				$price = $class_price; 
				if(dttheme_option('dt_course','currency-position') == 'after-price') { 
					$price = $price.dttheme_wp_kses(dttheme_option('dt_course','currency')); 
				} else { 
					$price = dttheme_wp_kses(dttheme_option('dt_course','currency')).$price; 
				}
				
				if($page_type == 'single') {
					if($class_price != '') {
						$purchase_link = dt_class_purchase_link($class_id);
						$out .= '<a href="'.esc_url($purchase_link).'" target="_self"  class="dt-sc-button small filled"><i class="fa fa-shopping-cart"></i> '.$price.' - '.__('Purchase Now','dt_themes').'</a>';
					} else {
						$out .= '<span class="dt-sc-purchased">'.__('Free','dt_themes').'</span>';
					}
				} else {
					if($class_price != '') {
						$out .= '<span class="dt-sc-class-price dt-sc-class-amount"><span class="amount">'.$price.'</span></span>';
					} else {
						$out .= '<span class="dt-sc-class-price dt-sc-class-free"><span class="amount">'.__('Free','dt_themes').'</span></span>';
					}
				}
			
			} else {
				$out .= '<span class="dt-sc-purchased">'.esc_html__('Registration Closed', 'dt_themes').'</span>';	
			}
				
		} else {
			
			if($page_type == 'single') {
				$out .= '<span class="dt-sc-purchased">'.__('Free','dt_themes').'</span>';
			} else {
				$out .= '<span class="dt-sc-class-price dt-sc-class-free"><span class="amount">'.__('Free','dt_themes').'</span></span>';
			}
			
		}
		
	}
	
	return $out;
	
}

function dt_class_purchase_link($class_id) {
	
	$purchase_link = '';

	$page_link = dttheme_get_page_permalink_by_its_template('tpl-membership.php');
	if($page_link != '') {
		
		$purchase_link = $page_link.'?classid='.$class_id;
		
	} else {

		$description = (dttheme_option('dt_course','s2member-1-description') != '') ? dttheme_option('dt_course','s2member-1-description') : __('You are about to purchase the Item : ', 'dt_themes').get_the_title($class_id);
		$period = (dttheme_option('dt_course','s2member-1-period') != '') ? dttheme_option('dt_course','s2member-1-period') : 1;
		$term = (dttheme_option('dt_course','s2member-1-term') != '') ? dttheme_option('dt_course','s2member-1-term') : 'L';									
		
		$class_price = dttheme_wp_kses(get_post_meta($class_id, 'dt-class-price', true));
		
		if(dttheme_option('dt_course','currency-s2member') != '') $currency = dttheme_option('dt_course','currency-s2member');
		else $currency = 'USD';
			
		if(dttheme_is_plugin_active('s2member/s2member.php')) {	
			$paypal_sc = do_shortcode("[s2Member-PayPal-Button level='1' ccaps='classid_{$class_id}' desc='{$description}' ps='paypal' lc='' cc='{$currency}' dg='0' ns='1' custom='".$_SERVER["HTTP_HOST"]."' ta='0' tp='0' tt='D' ra='{$class_price}' rp='{$period}' rt='{$term}' rr='BN' rrt='' rra='1' image='' output='url'/]");
		} else {
			$paypal_sc = '#';
		}
		
		$purchase_link = $paypal_sc;
	
	}
	
	return $purchase_link;
	
}

function dttheme_get_onsite_class_seats_available($class_id) {
	
	$payment_method = dttheme_option('general','payment-method');
	
	$dt_class_enable_purchases = get_post_meta($class_id, 'dt-class-enable-purchases', true);
	$dt_class_enable_registration = get_post_meta($class_id, 'dt-class-enable-registration', true);
	
	$dt_class_capacity = get_post_meta($class_id, 'dt-class-capacity', true);
	
	$units_sold = 0;
	
	if($dt_class_enable_purchases == 'true') {
		
		if($payment_method == 'woocommerce') {
		
			$dt_class_product_id = dttheme_get_class_product_id($class_id);
			$units_sold = get_post_meta( $dt_class_product_id, 'total_sales', true );
		
		} else {
			
			$dt_class_students_list = dt_get_students_list_purchased_this_class($class_id);
			$units_sold = !empty($dt_class_students_list) ? count($dt_class_students_list) : 0;
			
		}
	
	} else if($dt_class_enable_registration == 'true') {
		
		$units_sold = get_post_meta( $class_id, 'dt-class-registered-users', true );
		$units_sold = !empty($units_sold) ? count($units_sold) : 0;
		
	}
	
	$capacity_diff = $dt_class_capacity - $units_sold;
	
	return $capacity_diff;
	
}

add_action( 'wp_ajax_dttheme_show_class_registration_form', 'dttheme_show_class_registration_form' );
add_action( 'wp_ajax_nopriv_dttheme_show_class_registration_form', 'dttheme_show_class_registration_form' );
function dttheme_show_class_registration_form(){
	
	$user_id = get_current_user_id();
	
	$first_name = $last_name = $email = '';
	if($user_id != '') {
		
		$user_info = get_userdata($user_id);
		
		$first_name = $user_info->first_name;
		$last_name = $user_info->last_name;
		$email = $user_info->user_email;
	
	}
	
	$dt_classid = $_POST['dt_classid'];
	
	$seats_available = dttheme_get_onsite_class_seats_available($dt_classid);
	$dt_class_disable_purchases_regsitration = get_post_meta($dt_classid, 'dt-class-disable-purchases-regsitration', true);
	
	$out = '<div class="dt-sc-class-registration-form-container">';
	
		if(dt_check_student_already_registered($dt_classid)) {
			
			$out .= '<div class="dt-sc-info-box">'.esc_html('You have already registered for this class !', 'dt_themes').'</div>';
			
		} else {
		
			if($seats_available > 0 || ($seats_available <= 0 && $dt_class_disable_purchases_regsitration != 'true') || !dt_check_student_already_registered($dt_classid)) {
				
				$out .= '<h3 class="border-title"> '.esc_html__('Class Registration', 'dt_themes').'<span> </span></h3>';
		
				$out .= '<form action="" method="post" class="dt-sc-class-registration-form" id="dt-sc-class-registration-form" name="dt-sc-class-registration-form">';
				
					$out .= '<div class="column dt-sc-one-half first">
								<input type="text" name="first_name" id="first_name" placeholder="'.esc_html('First Name', 'dt_themes').'" value="'.$first_name.'" required />
							</div>
							<div class="column dt-sc-one-half">
								<input type="text" name="last_name" id="last_name" placeholder="'.esc_html('Last Name', 'dt_themes').'" value="'.$last_name.'" />
							</div>
							<div class="column dt-sc-one-half first">
								<input type="email" name="email" id="email" placeholder="'.esc_html('Email Id', 'dt_themes').'" value="'.$email.'" required />
							</div>
							<div class="column dt-sc-one-half">
								<input type="text" name="dob" id="dob" placeholder="'.esc_html('DOB (ex. 01/01/2001)', 'dt_themes').'" value="" required />
							</div>
							<div class="column dt-sc-one-column">
								<textarea name="message" id="message" placeholder="'.esc_html('Anything would you like to share with us ?', 'dt_themes').'"></textarea>
							</div>
							<input type="hidden" name="classid" id="classid" value="'.$dt_classid.'" />
							<input type="hidden" name="user_id" id="user_id" value="'.$user_id.'" />
							
							<button type="submit" class="dt-submit-regform" name="dt-submit-regform" value="'.__('Submit','dt_themes').'">'.__('Submit','dt_themes').'</button>';
									
				$out .= '</form>';
			
			} else {
				
				$out = '<div class="dt-sc-info-box">'.esc_html('All seats are booked !', 'dt_themes').'</div>';
				
			}
		
		}
		
	$out .= '</div>';
	
	echo $out;
	
	die();
}

add_action( 'wp_ajax_dttheme_process_class_registration_form', 'dttheme_process_class_registration_form' );
add_action( 'wp_ajax_nopriv_dttheme_process_class_registration_form', 'dttheme_process_class_registration_form' );
function dttheme_process_class_registration_form(){
	
	$classid = $_POST['classid'];
	
	$registered_users = array();
	
	$already_registered_users = get_post_meta($classid, 'dt-class-registered-users', true);
	
	if(!empty($already_registered_users)) {
		$registered_users = get_post_meta($classid, 'dt-class-registered-users', true);
	}
	
	$reg_details = array();
	$reg_details['first_name'] = $_POST['first_name'];
	$reg_details['last_name'] = $_POST['last_name'];
	$reg_details['email'] = $_POST['email'];
	$reg_details['dob'] = $_POST['dob'];
	$reg_details['message'] = $_POST['message'];
	$reg_details['user_id'] = $_POST['user_id'];
	
	$registered_users[] = $reg_details;

	update_post_meta ( $classid, "dt-class-registered-users", array_filter($registered_users) );
	
	die();
}

function dt_check_student_already_registered($class_id) {
	
	if(is_user_logged_in()) {
		
		$user_id = get_current_user_id();
		
		$registered_users = get_post_meta($class_id, 'dt-class-registered-users', true);
		
		if(is_array($registered_users) && !empty($registered_users)) {
		
			foreach($registered_users as $registered_user) {
				if($user_id == $registered_user['user_id']) {
					return true;
				}
			}
		
		}
		
	}
	
	return false;
	
}

function dt_get_students_list_purchased_this_class($class_id) {

	$students_list = array();
	
	$class_id = 'classid_'.$class_id;
	
	$students = get_users( array('role' => 's2member_level1') );
	foreach($students as $student) {
		$students_cap = function_exists(get_user_field) ? get_user_field ("s2member_access_ccaps", $student->data->ID) : array();
		if(in_array($class_id, $students_cap)) {
			$students_list[] = $student->data->ID;
		}
	}
	
	return $students_list;
	
}

function dttheme_check_lesson_grade($course_id, $lesson_id) {
	
	$user_id = get_current_user_id();
	
	$quiz_id = get_post_meta ($lesson_id, "lesson-quiz", true);
	if(!isset($quiz_id) || $quiz_id == '') { $quiz_id = -1; }
	
	$dt_gradings = dt_get_user_gradings_array($course_id, $lesson_id, $quiz_id, $user_id);
	$dt_grade_post = get_posts( $dt_gradings );
	
	if(isset($dt_grade_post[0])) {
		
		$dt_grade_post_id = $dt_grade_post[0]->ID;
		$user_status = get_post_meta ( $dt_grade_post_id, "graded", true);
		if(isset($user_status) && $user_status != '') {
			$out = '<span class="fa fa-check-circle"></span>';
		}
		
	}
	
	return $out;
	
}

function dttheme_check_assignment_grade($course_id, $assignment_id) {
	
	$user_id = get_current_user_id();
	
	$dtgradings = array( 'post_type' => 'dt_gradings', 'meta_query'=>array() );
	$dtgradings['meta_query'][] = array( 'key' => 'dt-user-id', 'value' => $user_id, 'compare' => '=', 'type' => 'numeric' );
	$dtgradings['meta_query'][] = array( 'key' => 'dt-course-id', 'value' => $course_id, 'compare' => '=', 'type' => 'numeric' );
	$dtgradings['meta_query'][] = array( 'key' => 'dt-assignment-id', 'value' => $assignment_id, 'compare' => '=', 'type' => 'numeric' );
	$dtgradings['meta_query'][] = array( 'key' => 'grade-type', 'value' => 'assignment', 'compare' => '=' );
	$dtgradings['meta_query'][] = array( 'key' => 'graded', 'value' => '', 'compare' => '!=' );
	$dtgradings_post = get_posts( $dtgradings );
	
	if(isset($dtgradings_post) && !empty($dtgradings_post)) {
		$out = '<span class="fa fa-check-circle"></span>';
	}
	
	return $out;
	
}

function dttheme_get_course_classes_lists($course_id) {
	
	$dtclasses = array( 'post_type' => 'dt_classes', 'fields' => 'ids' );
	$dtclasses_post = get_posts( $dtclasses );
	
	$class_ids = array();
	foreach($dtclasses_post as $dtclass) {
		
		$class_content_options_value = get_post_meta($dtclass, 'dt-class-content-options', true );
		
		if($class_content_options_value == 'course') {
			
			$class_courses = get_post_meta($dtclass, "dt-class-courses", true);
			if(in_array($course_id, $class_courses)) {
				$class_ids[] = $dtclass;
			}
		
		}
		
	}
	
	return $class_ids;
	
}

function dttheme_check_if_course_exists_in_class($course_id) {
	
	$class_ids = dttheme_get_course_classes_lists($course_id);
	
	foreach($class_ids as $class_id) {
		
		$class_courses = get_post_meta($class_id, "dt-class-courses", true);
		if(in_array($course_id, $class_courses)) {
			return true;
		}
		
	}
	
	return false;
	
}

function dttheme_check_course_class_purhcase_status_s2member($course_id) {
		
	$class_ids = dttheme_get_course_classes_lists($course_id);
	
	foreach($class_ids as $class_id) {
		
		$s2_level = 'access_s2member_ccap_classid_'.$class_id;
		if(current_user_can($s2_level) || dttheme_check_is_s2member_level_user(true)) {
			return true;
		}
	
	}
	
	return false;
	
}

function dttheme_get_course_classes_links($course_id) {
	
	$out = '';
	
	$class_ids = dttheme_get_course_classes_lists($course_id);
	
	foreach($class_ids as $class_id) {
		$out .= '<a href="'.get_permalink($class_id).'">'.get_the_title($class_id).'</a>, ';
	}
	
	return substr($out, 0, strlen($out) - 2);
	
}

// Single class ratings
function dttheme_get_single_class_page_ratings($class_id) {

	global $wpdb;
	
	$ratings_average = get_post_meta($class_id, 'ratings_average', true);
	$total_ratings = get_post_meta($class_id, 'ratings_users', true);
	$total_ratings = ($total_ratings == '') ? 0 : $total_ratings;
	$postratings_ratingstext = get_option('postratings_ratingstext');
	
	if($total_ratings == 0) {
		
		$one_stars = $two_stars = $three_stars = $four_stars = $five_stars = 0;
		$one_stars_percent = $two_stars_percent = $three_stars_percent = $four_stars_percent = $five_stars_percent = 0;
		
	} else {
		
		$one_stars_arr = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->ratings} WHERE 1=1 AND rating_postid = %d AND rating_rating = %d", $class_id, 1 ) );
		$one_stars = count($one_stars_arr);
		$one_stars_percent = floor(($one_stars/$total_ratings)*100);
	
		$two_stars_arr = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->ratings} WHERE 1=1 AND rating_postid = %d AND rating_rating = %d", $class_id, 2 ) );
		$two_stars = count($two_stars_arr);
		$two_stars_percent = floor(($two_stars/$total_ratings)*100);
	
		$three_stars_arr = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->ratings} WHERE 1=1 AND rating_postid = %d AND rating_rating = %d", $class_id, 3 ) );
		$three_stars = count($three_stars_arr);
		$three_stars_percent = floor(($three_stars/$total_ratings)*100);
	
		$four_stars_arr = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->ratings} WHERE 1=1 AND rating_postid = %d AND rating_rating = %d", $class_id, 4 ) );
		$four_stars = count($four_stars_arr);
		$four_stars_percent = floor(($four_stars/$total_ratings)*100);
	
		$five_stars_arr = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->ratings} WHERE 1=1 AND rating_postid = %d AND rating_rating = %d", $class_id, 5 ) );
		$five_stars = count($five_stars_arr);
		$five_stars_percent = floor(($five_stars/$total_ratings)*100);
	
	}
		
	echo '<div class="dt-sc-average-rating">
			<h2>'.$ratings_average.'</h2>
			'.do_shortcode('[ratings id="'.$class_id.'"]').'
			<span>'.$total_ratings.' '.esc_html__('ratings', 'dt_themes').'</span>
		</div>';
		
	echo '<ul class="dt-sc-ratings-breakup">
			<li>
				<span class="dt-sc-ratings-label">'.$postratings_ratingstext[0].'</span>
				<div class="dt-sc-ratings-percentage">
					<span style="width:'.$one_stars_percent.'%"></span>
				</div>
				<span>'.$one_stars.'</span>
			</li>
			<li>
				<span class="dt-sc-ratings-label">'.$postratings_ratingstext[1].'</span>
				<div class="dt-sc-ratings-percentage">
					<span style="width:'.$two_stars_percent.'%"></span>
				</div>
				<span>'.$two_stars.'</span>
			</li>
			<li>
				<span class="dt-sc-ratings-label">'.$postratings_ratingstext[2].'</span>
				<div class="dt-sc-ratings-percentage">
					<span style="width:'.$three_stars_percent.'%"></span>
				</div>
				<span>'.$three_stars.'</span>
			</li>
			<li>
				<span class="dt-sc-ratings-label">'.$postratings_ratingstext[3].'</span>
				<div class="dt-sc-ratings-percentage">
					<span style="width:'.$four_stars_percent.'%"></span>
				</div>
				<span>'.$four_stars.'</span>
			</li>
			<li>
				<span class="dt-sc-ratings-label">'.$postratings_ratingstext[4].'</span>
				<div class="dt-sc-ratings-percentage">
					<span style="width:'.$five_stars_percent.'%"></span>
				</div>
				<span>'.$five_stars.'</span>
			</li>
		</ul>';
		
}

function dttheme_generate_dashboard_progress_bar($value, $total_value) {
	
	$percentage_value = ($value/$total_value)*100;
	$bgcolor = 'rgb(155, 189, 60);';
	
	$out = '<div class="dt-sc-progress dt-sc-standard">
				<div data-value="'.$percentage_value.'" style="background-color: '.$bgcolor.'; width: '.$percentage_value.'%;" class="dt-sc-bar"></div>
			</div>';
	
	return $out;
	
}
?>