<?php

/**
 * Gradings Profile Tab
 */

function profile_tab_gradings() {
	
	global $bp;
	
	bp_core_new_nav_item(array( 
		'name' 					=> 'Gradings', 
		'slug' 					=> 'gradings', 
		'screen_function' 		=> 'gradings_screen', 
		'position' 				=> 40,
		'parent_url'      		=> bp_displayed_user_domain()  . '/gradings/',
		'parent_slug'     		=> $bp->profile->slug,
		'default_subnav_slug'	=> 'gradings'
	));
	  
}
add_action( 'bp_setup_nav', 'profile_tab_gradings' );
 
 
function gradings_screen() {
    add_action( 'bp_template_title', 'gradings_title' );
    add_action( 'bp_template_content', 'gradings_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );
}

function gradings_title() {
    echo __('Gradings', 'dt_themes');
}

function gradings_content() { 
	?>
    <div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
    <div class="dashboard-content" id="dt-sc-dashboard-user-courses">
        <?php 
        dt_get_user_course_overview(5, 1);
        ?>
    </div>
	<?php
                               
}
?>