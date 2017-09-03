<?php

/**
 * All Courses Profile Tab
 */

function profile_tab_allcourses() {
	
	global $bp;
	
	bp_core_new_nav_item(array( 
		'name' 					=> 'All My Courses', 
		'slug' 					=> 'allcourses', 
		'screen_function' 		=> 'allcourses_screen', 
		'position' 				=> 40,
		'parent_url'      		=> bp_displayed_user_domain()  . '/allcourses/',
		'parent_slug'     		=> $bp->profile->slug,
		'default_subnav_slug'	=> 'allcourses'
	));
	  
}
add_action( 'bp_setup_nav', 'profile_tab_allcourses' );
 
 
function allcourses_screen() {
    add_action( 'bp_template_title', 'allcourses_title' );
    add_action( 'bp_template_content', 'allcourses_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );
}

function allcourses_title() {
    echo __('All My Courses', 'dt_themes');
}

function allcourses_content() { 
	
	?>
    <div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
    <div class="dashboard-content" id="dt-sc-dashboard-user-allcourseslist">
        <?php 
        dt_get_user_allcourses_list_overview(10, 1);
        ?>
    </div>
	<?php
                               
}
?>