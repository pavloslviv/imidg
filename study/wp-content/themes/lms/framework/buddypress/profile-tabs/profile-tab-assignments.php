<?php

/**
 * Assignments Profile Tab
 */

function profile_tab_assignments() {
	
	global $bp;
	
	bp_core_new_nav_item(array( 
		'name' 					=> 'Assignments', 
		'slug' 					=> 'assignments', 
		'screen_function' 		=> 'assignments_screen', 
		'position' 				=> 50,
		'parent_url'      		=> bp_displayed_user_domain()  . '/assignments/',
		'parent_slug'     		=> $bp->profile->slug,
		'default_subnav_slug'	=> 'assignments'
	));
	  
}
add_action( 'bp_setup_nav', 'profile_tab_assignments' );
 
 
function assignments_screen() {
    add_action( 'bp_template_title', 'assignments_title' );
    add_action( 'bp_template_content', 'assignments_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );
}

function assignments_title() {
    echo __('Assignments', 'dt_themes');
}

function assignments_content() { 
	?>
    <div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
    <div class="dashboard-content" id="dt-sc-dashboard-user-assignments">
        <?php 
        dt_get_user_assignments(10, 1);
        ?>
    </div>
	<?php
                               
}
?>