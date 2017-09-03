<!-- #post-<?php the_ID(); ?> -->
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php 
    the_content(); 
    edit_post_link( __( ' Edit ','dt_themes' ) );
	$dt_type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'home';
	
	$user_role = IAMD_USER_ROLE;
	
	if(dttheme_check_is_s2member_level_user(true)) {
		$s2member_access_label = get_user_field ("s2member_access_label");
		$s2member_auto_eot_time = get_user_option ("s2member_auto_eot_time");
		if($s2member_auto_eot_time != '') {
			$time_format = get_option( 'date_format' ); 
			$exp_date = date('F j, Y H:i A', $s2member_auto_eot_time);
			echo '<p class="dt-sc-info-box">'.__('You have subscribed for our ', 'dt_themes').$s2member_access_label.'. '.__('Your subcription will expire on ', 'dt_themes').$exp_date.'</p>';
			echo '<div class="dt-sc-hr-invisible-small"></div>';
		}
	}
	
    ?>
	<div class="column dt-sc-one-fifth first">
    
    	<div class="dt-sc-user-details">
			<?php 
			
            $user_id = get_current_user_id();
            $user_info = get_userdata($user_id);
				
            echo get_avatar($user_id, 180); 
            echo '<div class="dt-sc-username">'.$user_info->display_name.'</div>';
			
            ?>        
        </div>
        
        <ul class="dt-sc-dashboard-menus">
        	<?php if(dttheme_check_is_s2member_level_user(false) || $user_role == 'customer') { ?>
                <li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=home" <?php if($dt_type == 'home') echo 'class="active"'; ?>> <span class="fa fa-home"> </span> <?php echo __('Home', 'dt_themes'); ?></a></li>
                <!--<li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=classes" <?php if($dt_type == 'classes') echo 'class="active"'; ?>> <span class="fa fa-institution"> </span><?php echo __('My Classes', 'dt_themes'); ?></a></li> -->
                 <li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=courses" <?php if($dt_type == 'courses') echo 'class="active"'; ?>> <span class="fa fa-book"> </span><?php echo __('My Courses', 'dt_themes'); ?></a></li>
                  <!--<li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=allcourses" <?php if($dt_type == 'allcourses') echo 'class="active"'; ?>> <span class="fa fa-book"> </span><?php echo __('All My Courses', 'dt_themes'); ?></a></li> -->
                <li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=allquizzes" <?php if($dt_type == 'allquizzes') echo 'class="active"'; ?>> <span class="fa fa-book"> </span><?php echo __('All My Quizzes', 'dt_themes'); ?></a></li>
                <li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=gradings" <?php if($dt_type == 'gradings') echo 'class="active"'; ?>> <span class="fa fa-trophy"> </span><?php echo __('Gradings', 'dt_themes'); ?></a></li>
               <!-- <li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=assignments" <?php if($dt_type == 'assignments') echo 'class="active"'; ?>> <span class="fa fa-file-text-o"> </span><?php echo __('Assignments', 'dt_themes'); ?></a></li> -->
                <?php
				if(dttheme_is_plugin_active('s2member/s2member.php')) {
					?>
                    <li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=profile" <?php if($dt_type == 'profile') echo 'class="active"'; ?>> <span class="fa fa-pencil"> </span><?php echo __('Edit Profile', 'dt_themes'); ?></a></li>
                	<?php
				}
				if(dttheme_is_plugin_active('woocommerce/woocommerce.php')) {
					?>
					<li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('WooCommerce Dashboard','dt_themes'); ?>"><span class="fa fa-shopping-cart"> </span><?php _e('WooCommerce','dt_themes'); ?></a></li>
					<?php
				}
				?>
             <?php } elseif(strtolower($user_role) == 'teacher') { ?>
                <li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=home" <?php if($dt_type == 'home') echo 'class="active"'; ?>> <span class="fa fa-home"> </span> <?php echo __('Home', 'dt_themes'); ?></a></li>
                <li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=courses" <?php if($dt_type == 'courses') echo 'class="active"'; ?>> <span class="fa fa-book"> </span> <?php echo __('Courses Submitted', 'dt_themes'); ?></a></li>
                <li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=assignments" <?php if($dt_type == 'assignments') echo 'class="active"'; ?>> <span class="fa fa-trophy"> </span> <?php echo __('Assignments', 'dt_themes'); ?></a></li>
                <li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=profile" <?php if($dt_type == 'profile') echo 'class="active"'; ?>> <span class="fa fa-pencil"> </span> <?php echo __('Edit Profile', 'dt_themes'); ?></a></li>
             <?php } else { ?>
                <li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=home" <?php if($dt_type == 'home') echo 'class="active"'; ?>> <span class="fa fa-home"> </span> <?php echo __('Home', 'dt_themes'); ?></a></li>
                <li><a href="<?php echo get_permalink(get_the_ID()); ?>?type=profile" <?php if($dt_type == 'profile') echo 'class="active"'; ?>> <span class="fa fa-pencil"> </span> <?php echo __('Edit Profile', 'dt_themes'); ?></a></li>
            <?php } ?>
        </ul>
    </div>
	<div class="column dt-sc-four-fifth dt-sc-user-dashboard-details">
    	
    	<?php
		
		if(dttheme_check_is_s2member_level_user(false) || $user_role == 'customer') {
			
			$ccaps = array();
			
			$payment_method = dttheme_option('general','payment-method');
				
			if($payment_method == 'woocommerce') {
			
				$ccaps = dttheme_get_user_purchased_courses($user_id);
			
			} else {
			
				if($user_role == 's2member_level1') {
					foreach ($user_info->allcaps as $cap => $cap_enabled) {
						if (preg_match ("/^access_s2member_ccap_cid_/", $cap))
							$ccaps[] = preg_replace ("/^access_s2member_ccap_cid_/", "", $cap);
					}
				} else if(dttheme_check_is_s2member_level_user(true)) {
					$ccaps = dt_get_all_paid_courses();
				}
			
			}
			
			$course_ids_arr = dt_get_user_graded_course();
			$free_course = array_diff($course_ids_arr, $ccaps);
			$ccaps = array_merge($ccaps, $free_course);
			
			if($dt_type == 'home') {
			?>
            <div class="dashboard-content">
            	<div class="column dt-sc-one-column">
                    
                <div class="dt-sc-hr-invisible-small"></div>
                
                                	
                    <h4 class="border-title"> <?php echo __('Profile', 'dt_themes'); ?> <span> </span> </h4>
                    <ul class="teachers-details">
                        <li><strong><?php echo __('Username : ', 'dt_themes'); ?></strong><?php echo $user_info->user_login; ?></li>
                        <li><strong><?php echo __('First Name : ', 'dt_themes'); ?></strong><?php echo $user_info->first_name; ?></li>
                        <li><strong><?php echo __('Last Name : ', 'dt_themes'); ?></strong><?php echo $user_info->last_name; ?></li>
                        <li><strong><?php echo __('Display Name : ', 'dt_themes'); ?></strong><?php echo $user_info->display_name; ?></li>
                        <li><strong><?php echo __('Email Address : ', 'dt_themes'); ?></strong><?php echo '<a href="mailto:'.$user_info->user_email.'">'.$user_info->user_email.'</a>'; ?></li>
                    </ul>
                </div>
            <?php
			} else if($dt_type == 'classes') {
			?>
            	<h4 class="border-title"><?php echo __('My Classes', 'dt_themes'); ?><span></span></h4>
            	<div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
                <div class="dashboard-content" id="dt-sc-dashboard-user-classlist">
                    <?php 
					dt_get_user_classes_list_overview(10, 1);
                    ?>
                </div>
            <?php
			} else if($dt_type == 'courses') {
			?>
            	<h4 class="border-title"><?php echo __('My Courses', 'dt_themes'); ?><span></span></h4>
            	<div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
                <div class="dashboard-content" id="dt-sc-dashboard-user-mycourseslist">
                    <?php 
					dt_get_user_mycourses_list_overview(10, 1);
                    ?>
                </div>
            <?php
			} else if($dt_type == 'allcourses') {
			?>
            	<h4 class="border-title"><?php echo __('All My Courses', 'dt_themes'); ?><span></span></h4>
            	<div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
                <div class="dashboard-content" id="dt-sc-dashboard-user-allcourseslist">
                    <?php 
					dt_get_user_allcourses_list_overview(10, 1);
                    ?>
                </div>
            <?php
			} else if($dt_type == 'allquizzes') {
			?>
            	<h4 class="border-title"><?php echo __('All My Quizzes', 'dt_themes'); ?><span></span></h4>
            	<div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
                <div class="dashboard-content" id="dt-sc-dashboard-user-allquizzes"">
                    <?php 
					dt_get_user_allquizzes_list(10, 1);
                    ?>
                </div>
            <?php
			} else if($dt_type == 'gradings') {
			?>
            	<h4 class="border-title"><?php echo __('Gradings', 'dt_themes'); ?><span></span></h4>
            	<div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
                <div class="dashboard-content" id="dt-sc-dashboard-user-courses">
                    <?php 
					dt_get_user_course_overview(5, 1);
                    ?>
                </div>
            
            <?php
			} else if($dt_type == 'profile') {
			?>
            	<h4 class="border-title"><?php echo __('Edit Profile', 'dt_themes'); ?><span></span></h4>
                
				<?php echo do_shortcode('[s2Member-Profile /]'); ?>
               
            <?php
			}
			?>
            
		<?php
        } elseif(strtolower($user_role) == 'teacher') {
        ?>
        	<?php
			if($dt_type == 'home') {
				?>
                <div class="dashboard-content">                	
                    <h4 class="border-title"><?php echo __('Profile', 'dt_themes'); ?><span></span></h4>
                    <ul class="teachers-details">
                        <li><strong><?php echo __('Username : ', 'dt_themes'); ?></strong><?php echo $user_info->user_login; ?></li>
                        <li><strong><?php echo __('First Name : ', 'dt_themes'); ?></strong><?php echo $user_info->first_name; ?></li>
                        <li><strong><?php echo __('Last Name : ', 'dt_themes'); ?></strong><?php echo $user_info->last_name; ?></li>
                        <li><strong><?php echo __('Display Name : ', 'dt_themes'); ?></strong><?php echo $user_info->display_name; ?></li>
                        <li><strong><?php echo __('Email Address : ', 'dt_themes'); ?></strong><?php echo '<a href="mailto:'.$user_info->user_email.'">'.$user_info->user_email.'</a>'; ?></li>
                    </ul>
                </div>
            <?php
			} else if($dt_type == 'courses') {
			?>
            	<h4 class="border-title"><?php echo __('Courses Submitted', 'dt_themes'); ?><span></span></h4>
            	<div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
                <div class="dashboard-content" id="dt-sc-dashboard-teacher-courses">
                    <?php 
					dt_get_teacher_courses(10, 1);
                    ?>
                </div>
            
            <?php
			} else if($dt_type == 'profile') {
			?>
            	<h4 class="border-title"><?php echo __('Edit Profile', 'dt_themes'); ?><span></span></h4>
                    <?php echo do_shortcode('[s2Member-Profile /]'); ?>
            <?php
			}
			?>
      
		<?php
        } else {
        ?>
        	<?php
			if($dt_type == 'home') {
				?>
                <div class="dashboard-content">
                    <h4 class="border-title"><?php echo __('Profile', 'dt_themes'); ?><span></span></h4>
                    <ul class="teachers-details">
                        <li><strong><?php echo __('Username : ', 'dt_themes'); ?></strong><?php echo $user_info->user_login; ?></li>
                        <li><strong><?php echo __('First Name : ', 'dt_themes'); ?></strong><?php echo $user_info->first_name; ?></li>
                        <li><strong><?php echo __('Last Name : ', 'dt_themes'); ?></strong><?php echo $user_info->last_name; ?></li>
                        <li><strong><?php echo __('Display Name : ', 'dt_themes'); ?></strong><?php echo $user_info->display_name; ?></li>
                        <li><strong><?php echo __('Email Address : ', 'dt_themes'); ?></strong><?php echo '<a href="mailto:'.$user_info->user_email.'">'.$user_info->user_email.'</a>'; ?></li>
                    </ul>
                </div>            
            <?php
			} else if($dt_type == 'profile') {
			?>
            	<h4 class="border-title"><?php echo __('Edit Profile', 'dt_themes'); ?><span></span></h4>
                    <?php echo do_shortcode('[s2Member-Profile /]'); ?>
            <?php
			}
		}
		?>
        
    </div>
    
    	
</div><!-- #post-<?php the_ID(); ?> -->