<?php 

add_action('add_meta_boxes', 'dttheme_bp_course_metabox');
add_action('save_post', 'dttheme_bp_course_save_postdata');

function dttheme_bp_course_metabox(){
	add_meta_box('dttheme_bp_course_group', __('Course Group', 'dt_themes'), 'dttheme_bp_course_metabox_function', 'dt_courses', 'side', 'core' );
} 
	
function dttheme_bp_course_metabox_function($post){ 

	wp_nonce_field( 'dttheme_bp_course_metabox_options', 'dttheme_bp_course_metabox_options_nonce' );
	
	$course_group = get_post_meta( $post->ID, 'dt_bp_course_group', true );
	$groups_arr = BP_Groups_Group::get(array(
					'type' => 'alphabetical',
					'per_page' => 999
				));
				
	?>

	<p><?php _e( 'Add this course to a BuddyPress group.', 'dt_themes' ); ?></p>
	<select name="dt_bp_course_group" id="dt_bp_course_group" class="dt-chosen-select">
		<option value="-1"><?php _e( 'Select', 'dt_themes' ); ?></option>
		<?php
		foreach ( $groups_arr[ 'groups' ] as $group ) {
			$group_status = groups_get_groupmeta( $group->id, 'dt_bp_group_course', true );
			if ( !empty($group_status) && $course_group != $group->id ) {
				continue;
			}
			?>
            <option value="<?php echo $group->id; ?>" <?php echo (( $course_group == $group->id )) ? 'selected' : ''; ?>><?php _e( $group->name, 'dt_themes' ); ?></option>
			<?php
		}
		?>
	</select>
	
	<?php
	
}

	
function dttheme_bp_course_save_postdata($post_id){
	
	
	if ( ! isset( $_POST['dttheme_bp_course_metabox_options_nonce'] ) ) {
		return;
	}
	
	if ( ! wp_verify_nonce( $_POST['dttheme_bp_course_metabox_options_nonce'], 'dttheme_bp_course_metabox_options' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	if ( (key_exists('post_type', $_POST)) && ('dt_courses' == $_POST['post_type']) ) {
	
		$old_group_id = get_post_meta( $post_id, 'dt_bp_course_group', true );
		
		if (!empty($old_group_id)) {
			groups_delete_groupmeta( $old_group_id, 'dt_bp_group_course' );
			dttheme_remove_members_from_group($post_id, $old_group_id );
		}
		
		if($_POST[ 'dt_bp_course_group' ] != '-1') {
			groups_add_groupmeta( $_POST[ 'dt_bp_course_group' ], 'dt_bp_group_course', $post_id );
			update_post_meta( $post_id, 'dt_bp_course_group', $_POST[ 'dt_bp_course_group' ] );
			dttheme_add_members_to_group($post_id, $_POST[ 'dt_bp_course_group' ] );
			dttheme_add_teacher_and_promote_as_admin( $post_id, $_POST[ 'dt_bp_course_group' ]);
		}
			
			
	}
	
}

?>