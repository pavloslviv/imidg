<?php

/**
 * Courses Submitted Profile Tab
 */

function profile_tab_coursessubmitted() {
	
	global $bp;
	
	bp_core_new_nav_item(array( 
		'name' 					=> 'Courses Submitted', 
		'slug' 					=> 'coursessubmitted', 
		'screen_function' 		=> 'coursessubmitted_screen', 
		'position' 				=> 20,
		'parent_url'      		=> bp_displayed_user_domain()  . '/coursessubmitted/',
		'parent_slug'     		=> $bp->profile->slug,
		'default_subnav_slug'	=> 'coursessubmitted'
	));
	  
}
add_action( 'bp_setup_nav', 'profile_tab_coursessubmitted' );
 
 
function coursessubmitted_screen() {
    add_action( 'bp_template_title', 'coursessubmitted_title' );
    add_action( 'bp_template_content', 'coursessubmitted_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );
}

function coursessubmitted_title() {
    echo __('Courses Submitted', 'dt_themes');
}

function coursessubmitted_content() { 
	?>
    <div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
    <div class="dashboard-content" id="dt-sc-dashboard-teacher-courses">
        <?php 
        dt_get_teacher_courses(10, 1);
        ?>
    </div>
	<?php
                               
}
?>