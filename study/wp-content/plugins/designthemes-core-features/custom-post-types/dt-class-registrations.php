<?php

function dt_classregistrations_options() {

	$out = '<div class="dt-settings-option-container">';

	$out .= '<p class="note">'.esc_html__('Only onsite classes with registration option will be shown here.', 'dt_themes').'</p>';
	$out .= '<p class="note">'.esc_html__('For onsite classes with WooCommerce purchase option check it in WooCommerce Orders.', 'dt_themes').'</p>';
	$out .= '<p class="note">'.esc_html__('For onsite classes with s2Member purchase option check it in s2Member Configuration & Profile Fields of individual users profile.', 'dt_themes').'</p>';
	
	$out .= '<label>'.__('Class', 'dt_themes').'</label>';
    $out .= '<select id="dt-settings-class" name="dt-settings-class" style="width:50%;" data-placeholder="'.__('Select Class...', 'dt_themes').'" class="dt-chosen-select">';
	$out .= '<option value="">'.__('None', 'dt_themes').'</option>';
	
		$selected_class = isset($_POST['class_id']) ? $_POST['class_id'] : '';
		$class_args = array('posts_per_page' => -1, 'post_type' => 'dt_classes', 'orderby' => 'title', 'order' => 'DESC');
		$class_args['meta_query'][] = array(
						'key'     => 'dt-class-type',
						'value'   => 'onsite',
						'compare' => '='
						);
		$class_args['meta_query'][] = array(
						'key'     => 'dt-class-enable-purchases',
						'compare' => 'NOT EXISTS'
						);
		$class_args['meta_query'][] = array(
						'key'     => 'dt-class-enable-registration',
						'compare' => 'EXISTS '
						);
		
		$classes = get_posts( $class_args );
        if ( count( $classes ) > 0 ) {
            foreach ($classes as $class){
				$class_id = $class->ID;
				$class_title = $class->post_title;
				$out .= '<option value="' . esc_attr( $class_id ) . '"' . selected($class_id, $selected_class, false) . '>' . esc_html( $class_title ) . '</option>';
            }
        }
		
    $out .= '</select>';
	
	$out .= '<input type="hidden" name="class-alert" id="class-alert" value="'.__('Please select class!', 'dt_themes').'" />';	
	
	$out .= '<div id="dt-sc-class-registration-details-container">';
		$out .= dt_class_registration_details($init_load = true);
	$out .= '</div>';
	
	$out .= '</div>';
	
	echo $out;

}	

add_action( 'wp_ajax_dt_class_registration_details', 'dt_class_registration_details' );
add_action( 'wp_ajax_nopriv_dt_class_registration_details', 'dt_class_registration_details' );
function dt_class_registration_details($init_load) {
	
	$class_id = isset($_REQUEST['class_id']) ? $_REQUEST['class_id'] : '';
	$post_per_page = isset($_REQUEST['post_per_page']) ? $_REQUEST['post_per_page'] : 10;
	$curr_page = isset($_REQUEST['curr_page']) ? $_REQUEST['curr_page'] : 1;

	$offset = (($curr_page-1)*$post_per_page);
	
	if($class_id == '') {
		$dt_class_registered_users = array();
	} else {
		$dt_class_registered_users = get_post_meta($class_id, 'dt-class-registered-users', true);
		$dt_class_registered_users_cnt = (!empty($dt_class_registered_users)) ? count($dt_class_registered_users) : 0;
	}
	
	$dt_class_registered_users = array_splice($dt_class_registered_users, $offset, $post_per_page);
	
	$out = '';
	
	if($class_id != '') {
		
		$dt_class_start_date = get_post_meta($class_id, 'dt-class-start-date', true);
		$dt_class_capacity = get_post_meta($class_id, 'dt-class-capacity', true);
		
		$out .= '<div class="dt-sc-class-details-container">
					<ul>
						<li><label>'.esc_html__('Start Date', 'dt_themes').'</label> : '.$dt_class_start_date.'</li>
						<li><label>'.esc_html__('Capacity', 'dt_themes').'</label> : '.$dt_class_capacity.'</li>
						<li><label>'.esc_html__('Registered', 'dt_themes').'</label> : '.$dt_class_registered_users_cnt.'</li>
						<li><label>'.esc_html__('Available', 'dt_themes').'</label> : '.($dt_class_capacity-$dt_class_registered_users_cnt).'</li>
					</ul>
				</div>';
	
		$out .= '<div id="dt-sc-ajax-load-image" style="display:none;"><img src="'.IAMD_BASE_URL."images/loading.png".'" alt="" /></div>';
		
		$out .= '<table border="0" cellpadding="0" cellspacing="10" style="width:100%;">
				  <tr>
					<th scope="col">'.__('#', 'dt_themes').'</th>
					<th scope="col">'.__('Name', 'dt_themes').'</th>
					<th scope="col">'.__('Email', 'dt_themes').'</th>
					<th scope="col">'.__('DOB', 'dt_themes').'</th>
					<th scope="col">'.__('Message', 'dt_themes').'</th>
					<th scope="col">'.__('Registered User', 'dt_themes').'</th>
				  </tr>';
		
		$i = 0;
		if(isset($dt_class_registered_users) && !empty($dt_class_registered_users)) {
			
			foreach($dt_class_registered_users as $dt_class_registered_user) {
				
				$view_url = '';
				if($dt_class_registered_user['user_id'] > 0) {
					$view_url = '<a href="'.get_edit_user_link($dt_class_registered_user['user_id']).'">'.esc_html('View', 'dt_themes').'</a>';
				}
				
				$out .= '<tr>
						<td>'.($i+1).'</td>
						<td>'.$dt_class_registered_user['first_name'].' '.$dt_class_registered_user['last_name'].'</td>
						<td>'.$dt_class_registered_user['email'].'</td>
						<td>'.$dt_class_registered_user['dob'].'</td>
						<td>'.$dt_class_registered_user['message'].'</td>
						<td>'.$view_url.'</td>
					</tr>';
					
				$i++;	
				
			}
			
		}
		
		if($i == 0) {
			$out .= '<tr><td colspan="3">'.__('No Records Found!', 'dt_themes').'</td></tr>';
		}
		
		$out .= '</table>';
		
		$dt_class_registered_users = get_post_meta($class_id, 'dt-class-registered-users', true);
		
		$out .= dtthemes_ajax_pagination($post_per_page, $curr_page, count($dt_class_registered_users), 0);	
	
	} else {
		
		$out .= esc_html__('Please select class!', 'dt_themes');
		
	}
	
	if($init_load) {
		return $out;
	} else {
		echo $out;
		die();
	}
	
}

?>