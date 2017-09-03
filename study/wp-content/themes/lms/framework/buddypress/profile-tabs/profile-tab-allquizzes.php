<?php

/**
 * All Quizzes Profile Tab
 */

function profile_tab_allquizzes() {
	
	global $bp;
	
	bp_core_new_nav_item(array( 
		'name' 					=> 'All My Quizzes', 
		'slug' 					=> 'allquizzes', 
		'screen_function' 		=> 'allquizzes_screen', 
		'position' 				=> 50,
		'parent_url'      		=> bp_displayed_user_domain()  . '/allquizzes/',
		'parent_slug'     		=> $bp->profile->slug,
		'default_subnav_slug'	=> 'allquizzes'
	));
	  
}
add_action( 'bp_setup_nav', 'profile_tab_allquizzes' );
 
 
function allquizzes_screen() {
    add_action( 'bp_template_title', 'allquizzes_title' );
    add_action( 'bp_template_content', 'allquizzes_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );
}

function allquizzes_title() {
    echo __('All My Quizzes', 'dt_themes');
}

function allquizzes_content() { 
	
	?>
    <div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
    <div class="dashboard-content" id="dt-sc-dashboard-user-allquizzes">
        <?php 
        dt_get_user_allquizzes_list(10, 1);
        ?>
    </div>
	<?php
                               
}
?>