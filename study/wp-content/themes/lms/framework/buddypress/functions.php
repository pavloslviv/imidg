<?php
function dttheme_add_members_to_group($course_id, $group_id) {
	
	$course_students = dt_get_course_capabilities_id($course_id);
	
	if ( empty( $course_students ) ) {
		return;
	}
	
	if ( is_array( $course_students ) ) {
		foreach ( $course_students as $course_students_id ) {
			groups_join_group( $group_id, $course_students_id );
		}
	} else {
		groups_join_group( $group_id, $course_students );
	}
	
}

function dttheme_remove_members_from_group($course_id, $group_id) {
	
	$course_students = dt_get_course_capabilities_id($course_id);
	
	if ( empty( $course_students ) ) {
		return;
	}
	
	if ( is_array( $course_students ) ) {
		foreach ( $course_students as $course_students_id ) {
			groups_remove_member( $course_students_id, $group_id );
		}
	} else {
		groups_remove_member( $course_students, $group_id );
	}
	
}

function dttheme_add_teacher_and_promote_as_admin( $course_id, $group_id ) {

	$teacher_id = get_post_field( 'post_author', $course_id );
	groups_join_group( $group_id, $teacher_id );
	$member = new BP_Groups_Member( $teacher_id, $group_id );
	$member->promote( 'admin' );

}
?>