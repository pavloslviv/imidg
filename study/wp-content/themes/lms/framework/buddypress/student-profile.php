<?php

/**
 * To add additional student profile tabs
 */
require_once(IAMD_TD.'/framework/buddypress/profile-tabs/profile-tab-wall.php');
require_once(IAMD_TD.'/framework/buddypress/profile-tabs/profile-tab-classes.php');
require_once(IAMD_TD.'/framework/buddypress/profile-tabs/profile-tab-mycourses.php');
require_once(IAMD_TD.'/framework/buddypress/profile-tabs/profile-tab-allcourses.php');
require_once(IAMD_TD.'/framework/buddypress/profile-tabs/profile-tab-allquizzes.php');
require_once(IAMD_TD.'/framework/buddypress/profile-tabs/profile-tab-gradings.php');
require_once(IAMD_TD.'/framework/buddypress/profile-tabs/profile-tab-assignments.php');



/**
 * Change students default landing tab.
 */
define('BP_DEFAULT_COMPONENT', 'wall' );


/**
 * BuddyPress profile tabs order.
 */
function dttheme_profile_tab_order() {
    global $bp;
	$bp->bp_nav['wall']['position'] = 10;
	$bp->bp_nav['classes']['position'] = 20;
	$bp->bp_nav['mycourses']['position'] = 30;
	$bp->bp_nav['allcourses']['position'] = 40;
	$bp->bp_nav['allquizzes']['position'] = 50;
	$bp->bp_nav['gradings']['position'] = 60;
	$bp->bp_nav['assignments']['position'] = 70;
    $bp->bp_nav['profile']['position'] = 80;
    $bp->bp_nav['activity']['position'] = 90;
    $bp->bp_nav['friends']['position'] = 100;
    $bp->bp_nav['groups']['position'] = 110;
    $bp->bp_nav['messages']['position'] = 120;
    $bp->bp_nav['notifications']['position'] = 130;
    $bp->bp_nav['settings']['position'] = 140;
}
add_action( 'bp_setup_nav', 'dttheme_profile_tab_order', 999 );


function bpfr_custom_setup_nav() {
	if(bp_is_active('xprofile')) {
		?>
		<li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('WooCommerce','dt_themes'); ?>"><?php _e('WooCommerce','dt_themes'); ?></a></li>
		<?php
	}
}
add_action( 'bp_member_options_nav', 'bpfr_custom_setup_nav' );


?>