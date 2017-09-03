<?php
/**
 * To load student and tacher profile tab
 */
$user_role = IAMD_USER_ROLE;
if(dttheme_check_is_s2member_level_user(false) || $user_role == 'customer') {
	require_once(IAMD_TD.'/framework/buddypress/student-profile.php');
} else if($user_role == 'teacher') {
	require_once(IAMD_TD.'/framework/buddypress/teacher-profile.php');
} else {
	require_once(IAMD_TD.'/framework/buddypress/default-profile.php');	
}

/**
 * To load all functions
 */
require_once(IAMD_TD.'/framework/buddypress/functions.php');


/**
 * To load metaboxes for courses
 */
if( bp_is_active( 'groups' ) ) {
	require_once(IAMD_TD.'/framework/buddypress/metaboxes.php');
}

?>