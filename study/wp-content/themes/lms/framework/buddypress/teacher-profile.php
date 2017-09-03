<?php

/**
 * To add additional teacher profile tabs
 */
require_once(IAMD_TD.'/framework/buddypress/profile-tabs/profile-tab-coursessubmitted.php');
require_once(IAMD_TD.'/framework/buddypress/profile-tabs/profile-tab-assignmentssubmitted.php');



/**
 * Change teachers default landing tab.
 */
define('BP_DEFAULT_COMPONENT', 'profile' );


/**
 * BuddyPress profile tabs order.
 */
function dttheme_profile_tab_order() {
    global $bp;
	$bp->bp_nav['profile']['position'] = 10;
	$bp->bp_nav['coursessubmitted']['position'] = 20;
	$bp->bp_nav['assignmentssubmitted']['position'] = 30;
    $bp->bp_nav['activity']['position'] = 40;
    $bp->bp_nav['friends']['position'] = 50;
    $bp->bp_nav['groups']['position'] = 60;
    $bp->bp_nav['messages']['position'] = 70;
    $bp->bp_nav['notifications']['position'] = 80;
    $bp->bp_nav['settings']['position'] = 90;
}
add_action( 'bp_setup_nav', 'dttheme_profile_tab_order', 999 );

?>