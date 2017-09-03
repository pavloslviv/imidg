<?php

/**
 * My Courses Profile Tab
 */

function profile_tab_mycourses() {
	
	global $bp;
	
	bp_core_new_nav_item(array( 
		'name' 					=> 'My Courses', 
		'slug' 					=> 'mycourses', 
		'screen_function' 		=> 'mycourses_screen', 
		'position' 				=> 30,
		'parent_url'      		=> bp_displayed_user_domain()  . '/mycourses/',
		'parent_slug'     		=> $bp->profile->slug,
		'default_subnav_slug'	=> 'mycourses'
	));
	  
}
add_action( 'bp_setup_nav', 'profile_tab_mycourses' );
 
 
function mycourses_screen() {
    add_action( 'bp_template_title', 'mycourses_title' );
    add_action( 'bp_template_content', 'mycourses_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );
}

function mycourses_title() {
    echo __('My Courses', 'dt_themes');
}

function mycourses_content() { 
	
	?>
    <div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
    <div class="dashboard-content" id="dt-sc-dashboard-user-mycourseslist">
        <?php 
        dt_get_user_mycourses_list_overview(10, 1);
        ?>
    </div>
	<?php
                               
}
?>