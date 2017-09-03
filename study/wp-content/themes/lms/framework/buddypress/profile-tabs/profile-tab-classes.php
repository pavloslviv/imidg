<?php

/**
 * Classes Profile Tab
 */

function profile_tab_classes() {
	
	global $bp;
	
	bp_core_new_nav_item(array( 
		'name' 					=> 'My Classes', 
		'slug' 					=> 'classes', 
		'screen_function' 		=> 'classes_screen', 
		'position' 				=> 20,
		'parent_url'      		=> bp_displayed_user_domain()  . '/classes/',
		'parent_slug'     		=> $bp->profile->slug,
		'default_subnav_slug'	=> 'classes'
	));
	  
}
add_action( 'bp_setup_nav', 'profile_tab_classes' );
 
 
function classes_screen() {
    add_action( 'bp_template_title', 'classes_title' );
    add_action( 'bp_template_content', 'classes_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );
}

function classes_title() {
    echo __('My Classes', 'dt_themes');
}

function classes_content() { 
	
	?>
    <div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
    <div class="dashboard-content" id="dt-sc-dashboard-user-classes">
        <?php 
        dt_get_user_classes_list_overview(10, 1);
        ?>
    </div>
	<?php
                               
}
?>