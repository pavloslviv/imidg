<?php

function dt_settings_options() {

	$current = isset( $_GET['tab'] ) ? $_GET['tab'] : 'dt_assign_students';
	
	dt_get_settings_submenus($current);
	dt_get_settings_tab($current);
	
}		

function dt_get_settings_submenus($current){

    $tabs = array( 
				'dt_assign_students' => __('Assign students to course', 'dt_themes'), 
				'dt_assign_courses' => __('Assign courses to student', 'dt_themes'), 
    		);
			
    echo '<h2 class="nav-tab-wrapper">';
		foreach( $tabs as $key => $tab ){
			$class = ( $key == $current ) ? 'nav-tab-active' : '';
			echo '<a class="nav-tab '.$class.'" href="?page=dt-settings-options&tab='.$key.'">'.$tab.'</a>';
	
		}
    echo '</h2>';

}

function dt_get_settings_tab($current){
	
	if(isset($_POST['dt_save'])) {
		dt_save_assignment_settings($current);
	}

	switch($current){
		case 'dt_assign_students': 
			dt_assign_students_settings();
		break;
		case 'dt_assign_courses':
			dt_assign_courses_settings();
		break;
		default:
			dt_assign_students_settings();
		break;
	}
	
}

function dt_save_assignment_settings($current){
	
	$payment_method = dttheme_option('general','payment-method');
	
	if($current == 'dt_assign_students') {
		
		if ( !empty($_POST) && check_admin_referer('dt_student_assignment_settings', '_wpnonce') ){
			
			$students_list = $_POST['hid_students_list'];
			$assigned_students = ($_POST['assigned_students']) ? $_POST['assigned_students'] : array();
			$assigned_students_keys = array_keys($assigned_students);
			$course_id_org = $_POST['course_id'];
			$course_id = 'cid_'.$_POST['course_id'];
			
			$course_group_id = get_post_meta( $_POST['course_id'], 'dt_bp_course_group', true );
						
			if(isset($students_list)) {
			
				foreach($students_list as $student_key => $student_id) {
					
					$user = new WP_User($student_id);
					
					if(in_array($student_key, $assigned_students_keys)) {
						
						if($payment_method == 'woocommerce') {
							
							if(!dttheme_check_user_already_purchased_courses_without_subscription_courses($student_id, $course_id_org)) {
								
								dttheme_woo_assign_course_to_students($student_id, $course_id_org);
								
							}
							
						} else {
							
							$student_cap = get_user_field ("s2member_access_ccaps", $student_id);
							if(!in_array($course_id, $student_cap)) {
								
								$user->add_cap('access_s2member_ccap_'.$course_id);
							
							}

						}
						
						if(dttheme_is_plugin_active('buddypress/bp-loader.php')) {
							if($course_group_id > 0) {
								$member_added_already = groups_is_user_member($student_id, $course_group_id );
								if(!($member_added_already > 0)) {
									groups_join_group( $course_group_id, $student_id );
								}
							}
						}
						
					} else {
						
						if($payment_method == 'woocommerce') {
							
							dttheme_woo_delete_user_order($student_id, $course_id_org);
							
						} else {
							
							$user->remove_cap('access_s2member_ccap_'.$course_id);
						
						}
						
						if(dttheme_is_plugin_active('buddypress/bp-loader.php')) {
							if($course_group_id > 0) {
								$member_added_already = groups_is_user_member($student_id, $course_group_id );
								if($member_added_already > 0) {
									groups_remove_member( $student_id, $course_group_id );
								}
							}
						}
						
					}
					
				}
			
			}
			
		}
	
	}
	
	if($current == 'dt_assign_courses') {
		
		if ( !empty($_POST) && check_admin_referer('dt_course_assignment_settings', '_wpnonce') ){
			
			$courses_list = $_POST['hid_courses_list'];
			$assigned_courses = isset($_POST['assigned_courses']) ? $_POST['assigned_courses'] : array();
			$assigned_courses_keys = array_keys($assigned_courses);
			$student_id = $_POST['student_id'];
			
			if(isset($courses_list)) {
				
				$user = new WP_User($student_id);
			
				foreach($courses_list as $course_key => $course_id) {
					
					$course_group_id = get_post_meta( $course_id, 'dt_bp_course_group', true );
					$course_id_new = 'cid_'.$course_id;
					
					if(in_array($course_key, $assigned_courses_keys)) {
						
						if($payment_method == 'woocommerce') {
							
							if(!dttheme_check_user_already_purchased_courses_without_subscription_courses($student_id, $course_id)) {
								
								dttheme_woo_assign_course_to_students($student_id, $course_id);
								
							}
							
						} else {
						
							$student_cap = get_user_field ("s2member_access_ccaps", $student_id);
							if(!in_array($course_id_new, $student_cap)) {
								
								$user->add_cap('access_s2member_ccap_'.$course_id_new);
							
							}
						
						}
						
						if(dttheme_is_plugin_active('buddypress/bp-loader.php')) {
							if($course_group_id > 0) {
								$member_added_already = groups_is_user_member($student_id, $course_group_id );
								if(!($member_added_already > 0)) {
									groups_join_group( $course_group_id, $student_id );
								}
							}
						}
						
					} else {
						
						if($payment_method == 'woocommerce') {
							
							dttheme_woo_delete_user_order($student_id, $course_id);
							
						} else {
							
							$user->remove_cap('access_s2member_ccap_'.$course_id_new);
						
						}
						
						if(dttheme_is_plugin_active('buddypress/bp-loader.php')) {
							if($course_group_id > 0) {
								$member_added_already = groups_is_user_member($student_id, $course_group_id );
								if($member_added_already > 0) {
									groups_remove_member( $student_id, $course_group_id );
								}
							}
						}
						
					}
					
				}
			
			}
			
		}
	
	}
	
}

// Assign Students

function dt_assign_students_settings(){
	
	$payment_method = dttheme_option('general','payment-method');
	
	$out = '';
	
	$out .= '<div class="dt-settings-option-container">';
	
	if($payment_method == 'woocommerce') {
		$out .= '<p class="note">'.esc_html__('Note this option is applicable only for "Customer" user role', 'dt_themes').'</p>';
		$out .= '<p class="note">'.esc_html__('You can\'t assign subscription woocommerce product ( course ) to student here', 'dt_themes').'</p>';
	} else {
		$out .= '<p class="note">'.esc_html__('Note this option is applicable only for "s2Member level 1" user role', 'dt_themes').'</p>';
	}
	$out .= '<p class="note">'.esc_html__('Only paid courses are listed here. Free courses can be taken by students once they login.', 'dt_themes').'</p>';
	$out .= '<p class="note">'.esc_html__('Members will be added or removed from course group ( if exists ) automatically if buddypress plugin is active.', 'dt_themes').'</p>';

	$out .= '<label>'.__('Course', 'dt_themes').'</label>';
    $out .= '<select id="dt-settings-course" name="dt-settings-course" style="width:50%;" data-placeholder="'.__('Select Course...', 'dt_themes').'" class="dt-chosen-select">';
	$out .= '<option value="">'.__('None', 'dt_themes').'</option>';
	
		$selected_course = isset($_POST['course_id']) ? $_POST['course_id'] : '';
		$course_args = array('posts_per_page' => -1, 'post_type' => 'dt_courses', 'orderby' => 'title', 'order' => 'DESC');
		if($payment_method == 'woocommerce') {
			$course_args['meta_query'][] = array(
							'key'     => 'dt-course-product-id',
							'value'   => 0,
							'type'    => 'numeric',
							'compare' => '>'
							);
		} else {
			$course_args['meta_query'][] = array(
							'key'     => 'starting-price',
							'value'   => 0,
							'type'    => 'numeric',
							'compare' => '>'
							);
		}
		
		$courses = get_posts( $course_args );
        if ( count( $courses ) > 0 ) {
            foreach ($courses as $course){
				$course_id = $course->ID;
				$course_title = $course->post_title;
				$out .= '<option value="' . esc_attr( $course_id ) . '"' . selected($course_id, $selected_course, false) . '>' . esc_html( $course_title ) . '</option>';
            }
        }
		
    $out .= '</select>';	
	
	$out .= '<input type="hidden" name="course-alert" id="course-alert" value="'.__('Please select course!', 'dt_themes').'" />';
	
	$out .= '</div>';
	
	$out .= '<div id="assignstudent-settings-container">';
		$out .= dt_assign_students($init_load = true);
	$out .= '</div>';
	
	echo $out;
	
}

add_action( 'wp_ajax_dt_assign_students', 'dt_assign_students' );
add_action( 'wp_ajax_nopriv_dt_assign_students', 'dt_assign_students' );
function dt_assign_students($init_load) {
	
	$payment_method = dttheme_option('general','payment-method');
	
	$course_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id'] : '';
	$post_per_page = isset($_REQUEST['post_per_page']) ? $_REQUEST['post_per_page'] : 10;
	$curr_page = isset($_REQUEST['curr_page']) ? $_REQUEST['curr_page'] : 1;

	$offset = (($curr_page-1)*$post_per_page);
	
	if($payment_method == 'woocommerce') {
		$students = array_merge(get_users(array('role' => 'customer')));
	} else {
		$students = array_merge(get_users(array('role' => 's2member_level1')));
	}
	$students = array_splice($students, $offset, $post_per_page);

	$out = '';
	
	if($course_id != '') {
		
		$out .= '<div id="dt-sc-ajax-load-image" style="display:none;"><img src="'.IAMD_BASE_URL."images/loading.png".'" alt="" /></div>';
		
		$out .= '<form name="frmAssignStudents" method="post">';
		
		$out .= '<table border="0" cellpadding="0" cellspacing="10" style="width:100%;">
				  <tr>
					<th scope="col">'.__('#', 'dt_themes').'</th>
					<th scope="col">'.__('Student', 'dt_themes').'</th>
					<th scope="col">'.__('Registered Date', 'dt_themes').'</th>
					<th scope="col">'.__('Subscribed', 'dt_themes').'</th>
				  </tr>';
		
		$i = 0;
		if(isset($students) && !empty($students)) {
			
			foreach($students as $student) {
				
				$student_id = $student->data->ID;
				
				if($payment_method == 'woocommerce') {
					$student_cap = dttheme_get_user_purchased_courses_without_subscription_courses($student_id);
				} else {
					$student_cap = get_user_field ("s2member_access_ccaps", $student_id);
					$student_cap = dt_remove_cid($student_cap);
				}
				
				$assigned_already = false;
				if(in_array($course_id, $student_cap)) {
					$assigned_already = true;
				}
				
				$out .= '<tr>
						<td>'.($i+1).'</td>
						<td>'.$student->data->display_name.'</td>
						<td>'.$student->data->user_registered.'</td>
						<td>';
						
							$out .= '<input type="hidden" name="hid_students_list['.$i.']" value="'.$student_id.'" />';
							
							$switchclass = ($assigned_already) ? 'checkbox-switch-on' : 'checkbox-switch-off';
							$checked = ($assigned_already) ? ' checked="checked"' : '';
							$out .= '<div data-for="item-'.$i.'" class="dt-settings-checkbox-switch '.$switchclass.'"></div>';
							$out .= '<input id="item-'.$i.'" class="hidden" type="checkbox" name="assigned_students['.$i.']" value="true" '.$checked.' />';
						
						'</td>
					</tr>';
					
				$i++;	
				
			}
			
		}
		
		if($i == 0) {
			$out .= '<tr><td colspan="5">'.__('No Records Found!', 'dt_themes').'</td></tr>';
		}
		
		$out .= '<input type="hidden" name="course_id" value="'.$course_id.'" />';
		$out .= '<input type="hidden" name="post_per_page" value="'.$post_per_page.'" />';
		$out .= '<input type="hidden" name="curr_page" value="'.$curr_page.'" />';
		$out .= '</table>';
		
		$out .= wp_nonce_field('dt_student_assignment_settings','_wpnonce'); 
		$out .= '<input type="submit" name="dt_save" value="'.__('Assign Students' ,'dt_themes').'" class="dt-assign-students-button" />';
		
		$out .= '</form>';
		
		if($payment_method == 'woocommerce') {
			$students = array_merge(get_users(array('role' => 'customer')));
		} else {
			$students = array_merge(get_users(array('role' => 's2member_level1')));
		}
		
		$out .= dtthemes_ajax_pagination($post_per_page, $curr_page, count($students), 0);
	
	} else {
		$out .= esc_html__('Please select course!', 'dt_themes');
	}
	
	if($init_load) {
		return $out;
	} else {
		echo $out;
		die();
	}

}

// Assign Courses

function dt_assign_courses_settings(){

	$out = '';
	
	$out .= '<div class="dt-settings-option-container">';
	
	if($payment_method == 'woocommerce') {
		$out .= '<p class="note">'.esc_html__('Note this option is applicable only for "Customer" user role', 'dt_themes').'</p>';
		$out .= '<p class="note">'.esc_html__('You can\'t assign subscription woocommerce product ( course ) to student here', 'dt_themes').'</p>';
	} else {
		$out .= '<p class="note">'.esc_html__('Note this option is applicable only for "s2Member level 1" user role', 'dt_themes').'</p>';
	}
	$out .= '<p class="note">'.esc_html__('Only paid courses are listed here. Free courses can be taken by students once they login.', 'dt_themes').'</p>';
	$out .= '<p class="note">'.esc_html__('Members will be added or removed from course group ( if exists ) automatically if buddypress plugin is active.', 'dt_themes').'</p>';

	
	$out .= '<label>'.__('Student', 'dt_themes').'</label>';
    $out .= '<select id="dt-settings-student" name="dt-settings-student" style="width:50%;" data-placeholder="'.__('Select Student...', 'dt_themes').'" class="dt-chosen-select">';
	$out .= '<option value="">'.__('None', 'dt_themes').'</option>';
	
		$selected_student = isset($_POST['student_id']) ? $_POST['student_id'] : '';
		
		$payment_method = dttheme_option('general','payment-method');
		
		if($payment_method == 'woocommerce') {
			$students = array_merge(get_users(array('role' => 'customer')));
		} else {
			$students = array_merge(get_users(array('role' => 's2member_level1')));
		}
		
		if(isset($students) && !empty($students)) {
			
			foreach($students as $student) {
				$student_id = $student->data->ID;
				$student_name = $student->data->display_name;
                $out .= '<option value="' . esc_attr($student_id) . '"' . selected($student_id, $selected_student, false) . '>' . esc_html($student_name) . '</option>';
			}
			
		}
		
    $out .= '</select>';
	$out .= '<input type="hidden" name="student-alert" id="student-alert" value="'.__('Please select student!', 'dt_themes').'" />';
	
	$out .= '</div>';
	
	$out .= '<div id="assigncourses-settings-container">';
		$out .= dt_assign_courses($init_load = true);
	$out .= '</div>';
	
	echo $out;
		
}


add_action( 'wp_ajax_dt_assign_courses', 'dt_assign_courses' );
add_action( 'wp_ajax_nopriv_dt_assign_courses', 'dt_assign_courses' );
function dt_assign_courses($init_load) {
	
	$student_id = isset($_REQUEST['student_id']) ? $_REQUEST['student_id'] : '';
	$post_per_page = isset($_REQUEST['post_per_page']) ? $_REQUEST['post_per_page'] : 10;
	$curr_page = isset($_REQUEST['curr_page']) ? $_REQUEST['curr_page'] : 1;

	$offset = (($curr_page-1)*$post_per_page);
	
	$payment_method = dttheme_option('general','payment-method');
	
	$out = '';
	
	if($student_id != '') {
		
		if($payment_method == 'woocommerce') {
			$student_cap = dttheme_get_user_purchased_courses_without_subscription_courses($student_id);
		} else {
			$student_cap = get_user_field ("s2member_access_ccaps", $student_id);
			$student_cap = dt_remove_cid($student_cap);
		}
		
		$out .= '<div id="dt-sc-ajax-load-image" style="display:none;"><img src="'.IAMD_BASE_URL."images/loading.png".'" alt="" /></div>';
		
		$out .= '<form name="frmAssignCourses" method="post">';
		
		$out .= '<table border="0" cellpadding="0" cellspacing="10" style="width:100%;">
				  <tr>
					<th scope="col">'.__('#', 'dt_themes').'</th>
					<th scope="col">'.__('Course', 'dt_themes').'</th>
					<th scope="col">'.__('Subscribed', 'dt_themes').'</th>
				  </tr>';
		
		$course_args = array('offset'=>$offset, 'paged' => $curr_page ,'posts_per_page' => $post_per_page, 'post_type' => 'dt_courses', 'orderby' => 'title', 'order' => 'DESC');
		if($payment_method == 'woocommerce') {
			$course_args['meta_query'][] = array(
							'key'     => 'dt-course-product-id',
							'value'   => 0,
							'type'    => 'numeric',
							'compare' => '>'
							);
		} else {
			$course_args['meta_query'][] = array(
							'key'     => 'starting-price',
							'value'   => 0,
							'type'    => 'numeric',
							'compare' => '>'
							);
		}
		
		$courses = get_posts( $course_args );
		
		$i = 0;
        if ( count( $courses ) > 0 ) {
            foreach ($courses as $course){
				
				$course_id = $course->ID;
				$course_title = $course->post_title;
								
				$assigned_already = false;
				if(in_array($course_id, $student_cap)) {
					$assigned_already = true;
				}
					
				$out .= '<tr>
						<td>'.($i+1).'</td>
						<td>'.$course_title.'</td>
						<td>';
							$out .= '<input type="hidden" name="hid_courses_list['.$i.']" value="'.$course_id.'" />';
							$switchclass = ($assigned_already) ? 'checkbox-switch-on' : 'checkbox-switch-off';
							$checked = ($assigned_already) ? ' checked="checked"' : '';
							$out .= '<div data-for="item-'.$i.'" class="dt-settings-checkbox-switch '.$switchclass.'"></div>';
							$out .= '<input id="item-'.$i.'" class="hidden" type="checkbox" name="assigned_courses['.$i.']" value="true" '.$checked.' />';
						'</td>
					</tr>';
					
				$i++;	
					
            }
        }
		
		if($i == 0) {
			$out .= '<tr><td colspan="5">'.__('No Records Found!', 'dt_themes').'</td></tr>';
		}
		
		$out .= '<input type="hidden" name="student_id" value="'.$student_id.'" />';
		$out .= '<input type="hidden" name="post_per_page" value="'.$post_per_page.'" />';
		$out .= '<input type="hidden" name="curr_page" value="'.$curr_page.'" />';
		$out .= '</table>';
		
		$out .= wp_nonce_field('dt_course_assignment_settings','_wpnonce'); 
		$out .= '<input type="submit" name="dt_save" value="'.__('Assign Courses' ,'dt_themes').'" class="dt-assign-courses-button" />';
		
		$out .= '</form>';
		
		$course_args = array('posts_per_page' => -1, 'post_type' => 'dt_courses', 'orderby' => 'title', 'order' => 'DESC');
		if($payment_method == 'woocommerce') {
			$course_args['meta_query'][] = array(
							'key'     => 'dt-course-product-id',
							'value'   => 0,
							'type'    => 'numeric',
							'compare' => '>'
							);
		} else {
			$course_args['meta_query'][] = array(
							'key'     => 'starting-price',
							'value'   => 0,
							'type'    => 'numeric',
							'compare' => '>'
							);
		}
		$courses_pagination = get_posts( $course_args );
		
		$out .= dtthemes_ajax_pagination($post_per_page, $curr_page, count($courses_pagination), 0);
	
	} else {
		$out .= esc_html__('Please select student!', 'dt_themes');
	}
	
	if($init_load) {
		return $out;
	} else {
		echo $out;
		die();
	}

}

?>