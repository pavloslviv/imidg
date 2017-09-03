<?php

/**
 * Assignments Submitted Profile Tab
 */

function profile_tab_assignmentssubmitted() {
	
	global $bp;
	
	bp_core_new_nav_item(array( 
		'name' 					=> 'Assignments Submitted', 
		'slug' 					=> 'assignmentssubmitted', 
		'screen_function' 		=> 'assignmentssubmitted_screen', 
		'position' 				=> 30,
		'parent_url'      		=> bp_displayed_user_domain()  . '/assignmentssubmitted/',
		'parent_slug'     		=> $bp->profile->slug,
		'default_subnav_slug'	=> 'assignmentssubmitted'
	));
	  
}
add_action( 'bp_setup_nav', 'profile_tab_assignmentssubmitted' );
 
 
function assignmentssubmitted_screen() {
    add_action( 'bp_template_title', 'assignmentssubmitted_title' );
    add_action( 'bp_template_content', 'assignmentssubmitted_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );
}

function assignmentssubmitted_title() {
    echo __('Assignments Submitted', 'dt_themes');
}

function assignmentssubmitted_content() { 
	?>
    <div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
    <div class="dashboard-content" id="dt-sc-dashboard-teacher-assignments">
        <?php 
        dt_get_teacher_assignments(10, 1);
        ?>
    </div>
	<?php
                               
}
?>