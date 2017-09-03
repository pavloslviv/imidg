<?php
function dttheme_get_user_purchased_classes($user_id) {
	
	$user_products = dttheme_get_user_purchased_products($user_id);
	$user_classes = array();
		
	$args = array('posts_per_page' => -1, 'post_type' => 'dt_classes');

	$user_classes_list = get_posts( $args );

	foreach($user_classes_list as $user_classes_list_key => $user_classes_list_value) {
		
		$dt_product_id = get_post_meta( $user_classes_list_value->ID, 'dt-class-product-id', true );
		
		if($dt_product_id != '') {
			if(in_array($dt_product_id, $user_products)) {
				$user_classes[] = $user_classes_list_value->ID;
			}
		}
		
		$dt_subscription_product_id = get_post_meta( $user_classes_list_value->ID, 'dt-class-subscription-product-id', true );
		
		if(!empty($dt_subscription_product_id)) {
			$subscribed_products = array_intersect($dt_subscription_product_id, $user_products);
			if(!empty($subscribed_products)) {
				$user_classes[] = $user_courses_list_value->ID;
			}
		}
		
	}
			
	return array_unique($user_classes);
	
}

function dt_get_all_paid_classes() {
	
	$ccaps = array();
	$dt_ccaps_qry = array('post_type'=>'dt_classes', 'sort_order' => 'ASC', 'sort_column' => 'menu_order', 'posts_per_page' => -1, 'meta_query'=>array());
	$dt_ccaps_qry['meta_query'][] = array('key' => 'dt-class-price', 'value' => 0, 'compare' => '>', 'type' => 'numeric' );

	$dt_ccaps_post = get_posts( $dt_ccaps_qry );
	
	foreach($dt_ccaps_post as $cp_post) {
		$ccaps[] = $cp_post->ID;
	}
	
	return $ccaps;

}

function dt_get_user_classes_list_overview($post_per_page, $curr_page) {
	
	$user_id = get_current_user_id();
	$user_info = get_userdata($user_id);
	
	$ccaps = array();
	
	$payment_method = dttheme_option('general','payment-method');
		
	if($payment_method == 'woocommerce') {
	
		$ccaps = dttheme_get_user_purchased_classes($user_id);
	
	} else {
		
		if(IAMD_USER_ROLE == 's2member_level1') {
			foreach ($user_info->allcaps as $cap => $cap_enabled) {
				if (preg_match ("/^access_s2member_ccap_classid_/", $cap)) {
					$ccaps[] = preg_replace ("/^access_s2member_ccap_classid_/", "", $cap);
				}
			}
		} else if(dttheme_check_is_s2member_level_user(true)) {
			$ccaps = dt_get_all_paid_classes();
		}

	}
	
	$ccaps = array_filter(array_unique($ccaps));
	
	$out = '';
	
	$out .= '<label>'.__('Class', 'dt_themes').'</label>';
    $out .= '<select id="dt-dsahboard-class" name="dt-dsahboard-class" data-placeholder="'.__('Select Class...', 'dt_themes').'" class="dt-chosen-select">';
	$out .= '<option value="">'.__('None', 'dt_themes').'</option>';
	
        if(!empty($ccaps)) {
            foreach($ccaps as $ccap){
				$out .= '<option value="'.esc_attr($ccap).'">'.get_the_title($ccap).'</option>';
            }
        }
		
    $out .= '</select>';
	
	$out .= '<div class="dt-sc-hr-invisible"></div>
			<div class="dt-sc-clear"></div>
			<div id="ajx_dashbord_class_courses_container"></div>';	
	
	echo $out;
	
}

add_action( 'wp_ajax_dttheme_show_dashbord_class_courses', 'dttheme_show_dashbord_class_courses' );
add_action( 'wp_ajax_nopriv_dttheme_show_dashbord_class_courses', 'dttheme_show_dashbord_class_courses' );
function dttheme_show_dashbord_class_courses(){

	$class_id = $_REQUEST['class_id'];
	
	$post_per_page = 10;
	$curr_page = isset($_REQUEST['curr_page']) ? $_REQUEST['curr_page'] : 1;
	$offset = (($curr_page-1)*$post_per_page);
	
	$user_id = get_current_user_id();
	
	$out = '';
	$compeleted_courses = 0;
	$class_percentage = 0;
	
	$class_courses = get_post_meta($class_id, 'dt-class-courses', true);
	$class_courses_cnt = count($class_courses);
	
	foreach($class_courses as $class_course) {
		$course_status = dt_get_users_course_status($class_course, '');
		if($course_status) {
			$compeleted_courses++;
		}
		$course_percentage = dt_get_course_percentage($class_course, '', true);
		if($course_percentage > 0) {
			$class_percentage = $class_percentage + $course_percentage;	
		}
	}
	
	if($class_percentage > 0) {
		$class_percentage = round(($class_percentage/$class_courses_cnt), 2);
	}
	
	$pending_courses = ($class_courses_cnt - $compeleted_courses);
	
	$out .= '<div class="dt-sc-dashboard-class-overall-progress-container">';
			$out .= '<div class="dt-sc-dashboard-class-average-result">';
				$out .= '<span>'.$class_percentage.'%</span>';
				$out .= '<h4>'.esc_html__('Average Result', 'dt_themes').'</h4>';
			$out .= '</div>';
			$out .= '<div class="column dt-sc-one-third first">';
				$out .= '<div class="dt-sc-dashboard-class-overall-progress">';
					$out .= '<span>'.$class_courses_cnt.'</span>';
					$out .= dttheme_generate_dashboard_progress_bar($class_courses_cnt, $class_courses_cnt);
					$out .= '<h4>'.esc_html__('Total Courses', 'dt_themes').'</h4>';
				$out .= '</div>';
			$out .= '</div>';
			$out .= '<div class="column dt-sc-one-third">';
				$out .= '<div class="dt-sc-dashboard-class-overall-progress">';
					$out .= '<span>'.$compeleted_courses.'</span>';
					$out .= dttheme_generate_dashboard_progress_bar($compeleted_courses, $class_courses_cnt);
					$out .= '<h4>'.esc_html__('Completed Courses', 'dt_themes').'</h4>';
				$out .= '</div>';
			$out .= '</div>';
			$out .= '<div class="column dt-sc-one-third">';
				$out .= '<div class="dt-sc-dashboard-class-overall-progress">';
					$out .= '<span>'.$pending_courses.'</span>';
					$out .= dttheme_generate_dashboard_progress_bar($pending_courses, $class_courses_cnt);
					$out .= '<h4>'.esc_html__('Pending Courses', 'dt_themes').'</h4>';
				$out .= '</div>';
			$out .= '</div>';
	$out .= '</div>';
	
	$class_courses = array_splice($class_courses, $offset, $post_per_page);
	
	
	$out .= dttheme_get_dashboard_courses($class_courses);
	
	$out .= dtthemes_ajax_pagination($post_per_page, $curr_page, $class_courses_cnt, 0);
	
	echo $out;
	
	die();

}

function dttheme_get_dashboard_courses($class_courses) {
	
	$user_id = get_current_user_id();
	
	$out = '';
	
	foreach($class_courses as $class_course) {
		
		$course_id = $class_course;
		$course_permalink = get_permalink($course_id);
		$course_title = get_the_title($course_id);
		
		if(dttheme_is_plugin_active('buddypress/bp-loader.php')) {
			
			$course_group_id = get_post_meta( $course_id, 'dt_bp_course_group', true );
			if($course_group_id > 0) {
				$member_added_already = groups_is_user_member($user_id, $course_group_id );
				if(!($member_added_already > 0)) {
					$join_group_label = '<a class="dt-sc-button small filled dt-sc-join-group" href="#" data-studentid="'.$user_id.'" data-groupid="'.$course_group_id.'">'.__('Join Group', 'dt_themes').'</a>';
				} else {
					$join_group_label = '<div class="dt-sc-course-completed"><span class="fa fa-check-circle"></span>'.__('Already Group Member', 'dt_themes').'</div>';	
				}
			} else {
				$join_group_label = '';	
			}
		
		} else {
			$join_group_label = '';
		}
		
		$out .= '<div class="dt-sc-full-width">';
		
			$out .= '<article id="post-'.$course_id.'" class="'.implode(" ", get_post_class('dt-sc-custom-course-type course-list-view', $course_id)).'">';
					
					$out .= '<div class="dt-sc-course-dashbord-holder">';
					
						$out .= '<div class="dt-sc-course-thumb">';
							$out .= '<a href="'.$course_permalink.'" >';
									if(has_post_thumbnail($course_id)):
										$attachment_id = get_post_thumbnail_id($course_id);
										$img_attributes = wp_get_attachment_image_src($attachment_id, $post_thumbnail);
										$out .= '<img src="'.$img_attributes[0].'" width="'.$img_attributes[1].'" height="'.$img_attributes[2].'" />';
									endif;
							$out .= '</a>';
							$out .= '<div class="dt-sc-course-overlay">
										<a title="'.$course_title.'" href="'.$course_permalink.'" class="dt-sc-button small white">'.__('View Course', 'dt_themes').'</a>
									</div>';
						$out .= '</div>';
					
						$out .= '<div class="dt-sc-dashboard-course-details-container">';
						
								$out .= '<h5><a href="'.$course_permalink.'" title="'.$course_title.'">'.$course_title.'</a></h5>';
								
								$out .= '<div class="dt-sc-course-meta">
											<p>'.get_the_term_list($course_id, 'course_category', ' ', ', ', ' ').'</p>
										</div>';
						
								$out .= '<div class="dt-sc-dashboard-course-details">';
										
										$out .= dttheme_get_user_course_progress($course_id);
	
										$course_completed = '';
										$course_status = dt_get_users_course_status($course_id, '');
										if($course_status) {
											$out .= '<div class="dt-sc-course-completed"> <span class="fa fa-check-circle"> </span> '.__('Completed', 'dt_themes').'</div>';
											$course_completed = 'dt-sc-completed-score';
										}
										
										$out .= $join_group_label;
								
								$out .= '</div>';
						
						$out .= '</div>';
						
						$course_percentage = dt_get_course_percentage($course_id, '', true);	
						$out .= dttheme_create_donut_chart($course_percentage);
					
					$out .= '</div>';
					
					$out .= '<div class="dt-sc-course-gradings-container">';
					
						$out .= '<div class="dt-sc-toggle-frame">
									<h5 class="dt-sc-toggle">
										<a href="#">'.esc_html__('Results', 'dt_themes').'</a>
									</h5>
									<div class="dt-sc-toggle-content" style="display:none;">
										<div class="block">
											'.dttheme_show_dashbord_course_result($course_id).'
										</div>
									</div>
								</div>';
			
					$out .= '</div>';
			
			$out .= '</article>';
	
		$out .= '</div>';
	
	}
	
	return $out;
	
}

function dttheme_show_dashbord_course_result($course_id) {
	
	$user_id = get_current_user_id();
	
	$out = '<table border="0" cellpadding="0" cellspacing="10" style="width:100%;">
				<thead>
					<tr>
						<th scope="col">'.__('#', 'dt_themes').'</th>
						<th scope="col" class="dt-sc-align-left">'.__('Lesson', 'dt_themes').'</th>
						<th scope="col">'.__('Grade', 'dt_themes').'</th>
						<th scope="col">'.__('Status', 'dt_themes').'</th>
						<th scope="col">'.__('Option', 'dt_themes').'</th>
					</tr>
				</thead>
			<tbody>';
	
			$lesson_args = array('sort_order' => 'ASC', 'sort_column' => 'menu_order', 'post_type' => 'dt_lessons', 'posts_per_page' => -1, 'meta_key' => 'dt_lesson_course', 'meta_value' => $course_id );								
			$lessons_array = get_posts( $lesson_args );
			
			if(!empty($lessons_array)) {
				
				$l = 1;
				foreach($lessons_array as $lesson) {
					
					$lesson_id = $lesson->ID;
					$quiz_id = get_post_meta($lesson_id, 'lesson-quiz', true);
					if(!isset($quiz_id) || $quiz_id == '') {
						$quiz_id = -1;
					}
					
					$dt_gradings = dt_get_user_gradings_array($course_id, $lesson_id, $quiz_id, $user_id);
					$dt_grade_post = get_posts($dt_gradings);
					
					$user_option = '';
					
					if(isset($dt_grade_post[0])) {
						
						$dt_grade_post_id = $dt_grade_post[0]->ID;
						$graded = get_post_meta($dt_grade_post_id, 'graded', true);
						
						if(isset($graded) && $graded != '') {
							
							$user_status = '<div class="dt-sc-course-completed"> <span class="fa fa-check-circle"></span>'.__('Completed', 'dt_themes').'</div>';
							
							if($quiz_id != -1 && $quiz_id != '') {
								$user_option = '<a href="'.get_permalink($quiz_id).'?dttype=viewquiz" class="dt-sc-button small">'.__('View Quiz', 'dt_themes').'</a>';
							}
							
							$grade = get_post_meta($dt_grade_post_id, 'marks-obtained-percent', true);
							$grade = $grade.'%';
						
						} else {
							
							$grade = '';
							$user_status = '<div class="dt-sc-course-notgraded"> <span class="fa fa-trophy"></span>'.__('Not yet graded', 'dt_themes').'</div>';
							if(dt_can_user_retake_quiz($course_id, $lesson_id, $quiz_id, $user_id)) {
								$user_option = '<a href="'.get_permalink($quiz_id).'" class="dt-sc-button small">'.__('Retake Quiz', 'dt_themes').'</a>';
							} else {
								$user_option = '<a href="'.get_permalink($lesson_id).'" class="dt-sc-button small">'.__('View Lesson', 'dt_themes').'</a>';
							}
							
						}
						
					} else {
						
						$grade = '';
						$user_status = '<div class="dt-sc-course-pending"> <span class="fa fa-clock-o fa-rotate-90"></span>'.__('Pending', 'dt_themes').'</div>';
						if(isset($quiz_id) && $quiz_id > 0) {
							$user_option = '<a href="'.get_permalink($quiz_id).'" class="dt-sc-button small">'.__('Take Quiz', 'dt_themes').'</a>';
						} else {
							$user_option = '<a href="'.get_permalink($lesson_id).'" class="dt-sc-button small">'.__('View Lesson', 'dt_themes').'</a>';
						}
							
					}
					
					$out .= '<tr>
								<td>'.$l.'</td>
								<td class="dt-sc-lesson-name"><a href="'.get_permalink($lesson_id).'">'.$lesson->post_title.'</a></td>
								<td class="dt-sc-grade-percent">'.$grade.'</td>
								<td>'.$user_status.'</td>
								<td>'.$user_option.'</td>
							</tr>';
							
					$l++;
					
				}
				
			} else {
				
					$out .= '<tr>
								<td colspan="5">'.esc_html__('No Records Found!', 'dt_themes').'</td>
							</tr>';

			}
	
	$out .= '</tbody></table>';
	
	
	$out .= '<table border="0" cellpadding="0" cellspacing="10" style="width:100%;">
				<thead>
					
				</thead>
			<tbody>';

			$assignment_args = array('post_type' => 'dt_assignments', 'posts_per_page' => -1, 'meta_query'=>array());	
			$assignment_args['meta_query'][] = array( 'key' => 'dt-assignment-course', 'value' => $course_id, 'compare' => '=', 'type' => 'numeric' );
									
			$assignment_array = get_posts($assignment_args);
			
			if(!empty($assignment_array)) {
				
				$a = 1;
				foreach($assignment_array as $assignment) {
					
					$assignment_id = $assignment->ID;
					
					$dtgradings = array( 'post_type' => 'dt_gradings', 'meta_query'=>array() );
					$dtgradings['meta_query'][] = array( 'key' => 'dt-user-id', 'value' => $user_id, 'compare' => '=', 'type' => 'numeric' );
					$dtgradings['meta_query'][] = array( 'key' => 'dt-course-id', 'value' => $course_id, 'compare' => '=', 'type' => 'numeric' );
					$dtgradings['meta_query'][] = array( 'key' => 'dt-assignment-id', 'value' => $assignment_id, 'compare' => '=', 'type' => 'numeric' );
					$dtgradings['meta_query'][] = array( 'key' => 'grade-type', 'value' => 'assignment', 'compare' => '=' );
					$dtgradings_post = get_posts( $dtgradings );
					
					if(isset($dtgradings_post) && !empty($dtgradings_post)) {
						
						$dtgradings_id = $dtgradings_post[0]->ID;
						$marks_obtained_percent = get_post_meta($dtgradings_id, 'marks-obtained-percent', true); 
						$graded = get_post_meta($dtgradings_id, 'graded', true);
						
						if(isset($graded) && $graded != '') { 
							$user_status = '<div class="dt-sc-course-completed"> <span class="fa fa-check-circle"></span>'.__('Completed', 'dt_themes').'</div>';
							$grade = $marks_obtained_percent.'%'; 
						} else { 
							$user_status = '<div class="dt-sc-course-notgraded"> <span class="fa fa-check-circle"></span>'.__('Not yet graded', 'dt_themes').'</div>'; $grade = ''; 
						}
							
					} else {
						
						$grade = '';
						$user_status = '<div class="dt-sc-course-pending"> <span class="fa fa-clock-o fa-rotate-90"></span>'.__('Pending', 'dt_themes').'</div>';
						
					}
					
					$out .= '<tr>
								<td>'.$a.'</td>
								<td class="dt-sc-assignment-name"><a href="'.get_permalink($assignment_id).'">'.get_the_title($assignment_id).'</a></td>
								<td>'.$grade.'</td>
								<td>'.$user_status.'</td>
								<td><a href="'.get_permalink($assignment_id).'" class="dt-sc-button small">'.__('View Assignment', 'dt_themes').'</a></td>
							</tr>';
					
					$a++;
					
				}
				
			} else {
			
					$out .= '<tr>
								
							</tr>';
				
			}
	
	$out .= '</tbody></table>';
	
	return $out;
	
}

function dt_get_user_allcourses_list_overview($post_per_page, $curr_page) {
	
	$user_id = get_current_user_id();
	$user_info = get_userdata($user_id);
		
	$payment_method = dttheme_option('general','payment-method');
	
	$classes = array();
	
	$all_courses = array();
	
	if($payment_method == 'woocommerce') {
	
		$classes = dttheme_get_user_purchased_classes($user_id);
		$all_courses = dttheme_get_user_purchased_courses($user_id);
	
	} else {
		
		if(IAMD_USER_ROLE == 's2member_level1') {
			foreach ($user_info->allcaps as $cap => $cap_enabled) {
				if (preg_match ("/^access_s2member_ccap_classid_/", $cap)) {
					$classes[] = preg_replace ("/^access_s2member_ccap_classid_/", "", $cap);
				}
				if (preg_match ("/^access_s2member_ccap_cid_/", $cap)) {
					$all_courses[] = preg_replace ("/^access_s2member_ccap_cid_/", "", $cap);
				}
			}
		} else if(dttheme_check_is_s2member_level_user(true)) {
			$classes = dt_get_all_paid_classes();
			$all_courses = dt_get_all_paid_courses();
		}

	}
		
	foreach($classes as $class_id) {
		$class_courses = get_post_meta($class_id, 'dt-class-courses', true);
		$all_courses = array_merge($all_courses, $class_courses);
	}
	
	$all_courses = array_filter(array_unique($all_courses));
	
	if(!empty($all_courses)) {
		
		$courses_cnt = count($all_courses);
		
		$offset = (($curr_page-1)*$post_per_page);	
		$all_courses = array_splice($all_courses, $offset, $post_per_page);
		
		echo dttheme_get_dashboard_courses($all_courses);
		echo dtthemes_ajax_pagination($post_per_page, $curr_page, $courses_cnt, 0);
		
	} else {
		
		echo esc_html__('No Records Found!', 'dt_themes');
		
	}
	
}

function dt_get_user_allquizzes_list($post_per_page, $curr_page) {
	
	$user_id = get_current_user_id();
	$offset = (($curr_page-1)*$post_per_page);
	
	$out .= '<div id="dt-sc-ajax-load-image" style="display:none;"><img src="'.IAMD_BASE_URL."images/loading.gif".'" alt="" /></div>';
	
	$dt_gradings = array(
					'offset'=>$offset, 
					'paged' => $curr_page,
					'posts_per_page' => $post_per_page, 
					'post_type'=>'dt_gradings',
					'meta_query'=>array()
				);
	
	$dt_gradings['meta_query'][] = array(
										'key'     => 'dt-quiz-id',
										'compare' => 'EXISTS',
									);

	$dt_gradings['meta_query'][] = array(
										'key'     => 'dt-user-id',
										'value'   => $user_id,
										'compare' => '=',
										'type'    => 'numeric'
									);
									
	$dt_gradings['meta_query'][] = array(
										'key'     => 'grade-type',
										'value'   => 'quiz',
										'compare' => '=',
									);
	
	$dt_grade_post = get_posts( $dt_gradings );
	
	if(is_array($dt_grade_post) && !empty($dt_grade_post)) {
		
		foreach($dt_grade_post as $dt_grade) {
			
			$dt_grade_post_id = $dt_grade->ID;
			
			echo '<div class="dt-sc-dashboard-quiz-statistcis">';
			
				$quiz_id = get_post_meta($dt_grade_post_id, "dt-quiz-id", true);
				
				echo '<h5><a href="'.get_permalink($quiz_id).'?dttype=viewquiz">'.get_the_title($quiz_id).'</a></h5>';
				echo dttheme_get_quiz_statistics($quiz_id, $dt_grade_post_id, '');
			
			echo '</div>';
			
		}
	
	} else {
		
		echo esc_html__('No Records Found!', 'dt_themes');
		
	}
	
	echo '<div class="dt-sc-hr-invisible"></div>';
	
	$dt_gradings = array(
					'post_type'=>'dt_gradings',
					'meta_query'=>array()
				);
	
	$dt_gradings['meta_query'][] = array(
										'key'     => 'dt-quiz-id',
										'compare' => 'EXISTS',
									);

	$dt_gradings['meta_query'][] = array(
										'key'     => 'dt-user-id',
										'value'   => $user_id,
										'compare' => '=',
										'type'    => 'numeric'
									);
									
	$dt_gradings['meta_query'][] = array(
										'key'     => 'grade-type',
										'value'   => 'quiz',
										'compare' => '=',
									);
	
	$dt_grade_post = get_posts( $dt_gradings );
	$total_posts = count($dt_grade_post);
	
	echo dtthemes_ajax_pagination($post_per_page, $curr_page, $total_posts, 0);
	
}

function dttheme_get_quiz_statistics($quiz_id, $dt_grade_post_id, $page_type) {
		
	$total_questions = $skipped_question = $correct_answers = $wrong_answers = 0;
	
	$quiz_question = get_post_meta($quiz_id, "quiz-question", true);
	$total_questions = count($quiz_question);
	
	foreach($quiz_question as $question_id) {
		
		$question_type = get_post_meta($question_id, 'question-type' ,true);
		$user_answer = get_post_meta($dt_grade_post_id, 'dt-question-'.$question_id, true);
		
		if((is_array($user_answer) && empty($user_answer)) || $user_answer == '') {
			$skipped_question++;
		}
		
		if(dt_validate_user_answer($question_id, $question_type, $user_answer)) {
			$correct_answers++;
		} else {
			$wrong_answers++;
		}
		
	}
	
	$wrong_answers = $wrong_answers - $skipped_question;
	
	$quiz_duration = get_post_meta($quiz_id, "quiz-duration", true);
	$quiz_duration = (isset($quiz_duration) && $quiz_duration > 0) ? $quiz_duration : 0;

	$timings = get_post_meta($dt_grade_post_id, "dt-timings", true);
	
	if($timings != '') {
		$time_taken = (($quiz_duration*60) - $timings);
		$time_taken = gmdate('H:i:s', $time_taken);
	} else {
		$time_taken = '-';
	}
	
	$dt_marks_obtained_percent = get_post_meta($dt_grade_post_id, "marks-obtained-percent", true); 
	$dt_marks_obtained_percent = (isset($dt_marks_obtained_percent) && $dt_marks_obtained_percent > 0) ? $dt_marks_obtained_percent : 0;
	
	$out = '';
	
	$out .= '<div class="column dt-sc-one-fourth first">';
		$out .= dttheme_create_donut_chart($dt_marks_obtained_percent);
	$out .= '</div>';
	$out .= '<div class="column dt-sc-one-fourth">';
		$out .= '<ul>';
			if(get_locale()=='uk'){$out .= '<li class="dt-sc-quiz-total-questions"><label>'.esc_html__('Всіх', 'dt_themes').'</label> <span class="dt-sc-quiz-sepeartor"></span> <span class="dt-sc-quiz-question-result">'.$total_questions.'</span></li>';}else{$out .= '<li class="dt-sc-quiz-total-questions"><label>'.esc_html__('Total', 'dt_themes').'</label> <span class="dt-sc-quiz-sepeartor"></span> <span class="dt-sc-quiz-question-result">'.$total_questions.'</span></li>';}
			if(get_locale()=='uk'){$out .= '<li class="dt-sc-quiz-skipped-questions"><label>'.esc_html__('Пропущених', 'dt_themes').'</label> <span class="dt-sc-quiz-sepeartor"></span> <span class="dt-sc-quiz-question-result">'.$skipped_question.'</span></li>';}else{$out .= '<li class="dt-sc-quiz-skipped-questions"><label>'.esc_html__('Skipped', 'dt_themes').'</label> <span class="dt-sc-quiz-sepeartor"></span> <span class="dt-sc-quiz-question-result">'.$skipped_question.'</span></li>';}
			if(get_locale()=='uk'){$out .= '<li class="dt-sc-quiz-correct-answers"><label>'.esc_html__('Правильних', 'dt_themes').'</label> <span class="dt-sc-quiz-sepeartor"></span> <span class="dt-sc-quiz-question-result">'.$correct_answers.'</span></li>';}else{$out .= '<li class="dt-sc-quiz-correct-answers"><label>'.esc_html__('Correct', 'dt_themes').'</label> <span class="dt-sc-quiz-sepeartor"></span> <span class="dt-sc-quiz-question-result">'.$correct_answers.'</span></li>';}
			if(get_locale()=='uk'){$out .= '<li class="dt-sc-quiz-wrong-answers"><label>'.esc_html__('Помилок', 'dt_themes').'</label> <span class="dt-sc-quiz-sepeartor"></span> <span class="dt-sc-quiz-question-result">'.$wrong_answers.'</span></li>';}else{$out .= '<li class="dt-sc-quiz-wrong-answers"><label>'.esc_html__('Wrong', 'dt_themes').'</label> <span class="dt-sc-quiz-sepeartor"></span> <span class="dt-sc-quiz-question-result">'.$wrong_answers.'</span></li>';}
		$out .= '</ul>';
	$out .= '</div>';
	$out .= '<div class="column dt-sc-one-fourth">';
		if(get_locale()=='uk'){$out .= '<div class="dt-sc-quiz-time-taken"><label>'.esc_html__('Часу Витратили', 'dt_themes').'</label><span>'.$time_taken.'</span></div>';}else{$out .= '<div class="dt-sc-quiz-time-taken"><label>'.esc_html__('Time Taken', 'dt_themes').'</label><span>'.$time_taken.'</span></div>';}
	$out .= '</div>';
	$out .= '<div class="column dt-sc-one-fourth">';
		if($page_type != 'single') {
			$out .= '<div class="dt-sc-dashboard-view-quiz">';
				$out .= '<a class="dt-sc-button small filled" target="_blank" href="'.get_permalink($quiz_id).'?dttype=viewquiz">'.esc_html__('View Quiz', 'dt_themes').'</a>';
			$out .= '</div>';
		}
	$out .= '</div>';
	
	return $out;
	
}

function dttheme_create_donut_chart($percent) {
	
	$fgcolor = '#e85f4f';
	if($percent >= 80) {
		$fgcolor = '#9bbd3c';
	} else if($percent >= 40 && $percent < 80) {
		$fgcolor = '#f5a627';
	}
	
	$out = '<div class="dt-sc-donutchart" data-size="130" data-percent="'.$percent.'" data-bgcolor="#808080" data-fgcolor="'.$fgcolor.'"></div>';
	
	return $out;

}

?>