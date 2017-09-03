<?php
/*Template Name: Login Template */
get_header();

	$tpl_default_settings = get_post_meta( $post->ID, '_tpl_default_settings', TRUE );
	$tpl_default_settings = is_array( $tpl_default_settings ) ? $tpl_default_settings  : array();

	if($GLOBALS['force_enable'] == true)
		$page_layout = $GLOBALS['page_layout'];
	else
		$page_layout  = array_key_exists( "layout", $tpl_default_settings ) ? $tpl_default_settings['layout'] : "content-full-width";
	
	$show_sidebar = $show_left_sidebar = $show_right_sidebar =  false;
	$sidebar_class = "";

	switch ( $page_layout ) {
		case 'with-left-sidebar':
			$page_layout = "page-with-sidebar with-left-sidebar";
			$show_sidebar = $show_left_sidebar = true;
			$sidebar_class = "secondary-has-left-sidebar";
		break;

		case 'with-right-sidebar':
			$page_layout = "page-with-sidebar with-right-sidebar";
			$show_sidebar = $show_right_sidebar	= true;
			$sidebar_class = "secondary-has-right-sidebar";
		break;

		case 'both-sidebar':
			$page_layout = "page-with-sidebar page-with-both-sidebar";
			$show_sidebar = $show_right_sidebar	= $show_left_sidebar = true;
			$sidebar_class = "secondary-has-both-sidebar";
		break;

		case 'content-full-width':
		default:
			$page_layout = "content-full-width";
		break;
	}

	if ( $show_sidebar ):
		if ( $show_left_sidebar ): ?>
			<!-- Secondary Left -->
			<section id="secondary-left" class="secondary-sidebar <?php echo $sidebar_class;?>"><?php get_sidebar( 'left' );?></section><?php
		endif;
	endif;
	
	?>

	<!-- ** Primary Section ** -->
	<section id="primary" class="<?php echo $page_layout;?>">
		<!-- Login Module -->
                
			<?php 
			
			if( is_user_logged_in() ) {
				
				$loginreg_page = dttheme_option('general','login-registration-page');
				if($loginreg_page == 'lms-buddypress-profile') {
					$link = bp_loggedin_user_domain();
				} else {
					$link = dttheme_get_page_permalink_by_its_template('tpl-welcome.php');
				}
				
				if ( !empty( $link )) {
					return wp_redirect( $link );
				}
				
			} else { 			
			?>

				<!-- Login Form -->
				<div class="column dt-sc-one-half first">

					<div class="dt-sc-border-title"> <h2><span><?php _e('Login Form','dt_themes');?></span> </h2></div>
                    
                    <p> <strong><?php _e('Already a Member? Log in here.','dt_themes');?></strong> </p>
                    <?php
					
					$loginreg_page = dttheme_option('general','login-registration-page');
					if($loginreg_page == 'lms-buddypress-profile') {
						$link = home_url();
					} else {
						$link = dttheme_get_page_permalink_by_its_template('tpl-welcome.php');
						$link = is_null($link) ? admin_url( 'profile.php' ) : $link;
					}
							  					
					$args = array(
						'redirect' => $link,
					);
					wp_login_form($args);
					?>
                    <p class="tpl-forget-pwd"><a href="<?php echo wp_lostpassword_url( get_bloginfo('url') ); ?>"><?php _e('Lost your password ?','dt_themes');?></a></p>

				</div><!-- Login Form End -->

				<!-- Registration Form 
				<div class="column dt-sc-one-half">
                    <div class="dt-sc-border-title"> <h2><span><?php _e('Register Form','dt_themes');?></span> </h2></div>
                    
					<p> <strong><?php _e('Do not have an account? Register here','dt_themes');?></strong> </p>

					<form name="loginform" id="loginform" action="<?php echo wp_registration_url(); ?>" method="post">
						<p>	
							<label><?php _e('Username','dt_themes');?><span class="required"> * </span> </label> 
							<input type="text" name="user_login"  class="input" value="" size="20" required="required" />
						</p>
						<p>
							<label><?php _e('Email Id','dt_themes');?><span class="required"> * </span> </label> 
							<input type="email" name="user_email"  class="input" value="" size="20" required="required" />
						</p>
						<p>
							<label><?php _e('Role','dt_themes');?><span class="required"> * </span> </label> 
                            <select name="role" id="role">
                                <option value="subscriber"><?php echo __('Subscriber', 'dt_themes'); ?></option>
                                <option value="teacher"><?php echo $GLOBALS['teachers-singular-label']; ?></option>
                                <?php 
								$payment_method = dttheme_option('general','payment-method');
								
								if($payment_method == 'woocommerce') {
									$status = dttheme_is_plugin_active('woocommerce/woocommerce.php');
									if($status) {
										?>
										<option value="customer"><?php echo __('Student', 'dt_themes'); ?></option>
										<?php
									}
								} else {
								
									$status = dttheme_is_plugin_active('s2member/s2member.php');
									if($status) {
										?>
										<option value="s2member_level1"><?php echo __('Student', 'dt_themes'); ?></option>
										<?php
									}
									
								}
								
								?>
                            </select>
						</p>
						<p class="submit alignleft"><input type="submit" class="button-primary" value="<?php _e('Register','dt_themes');?>" /></p>
					</form>
				</div> Registration Form End -->
				<div class="clear"></div>
		<?php }?>
		
        <?php
		if(dttheme_option('general','enable-social-logins') == 'true') {
			
			echo '<div class="dt-sc-social-logins-container">';
			
				echo '<div class="dt-sc-hr-invisible"></div>';
				echo '<div class="dt-sc-social-logins-divider">'.esc_html__('Or', 'dt_themes').'</div>';
				echo '<div class="dt-sc-hr-invisible"></div>';
				
				if(dttheme_option('general','enable-facebook-login') == 'true') {
					echo '<a href="'.dttheme_facebook_login_url().'" class="dt-sc-social-facebook-connect"><i class="fa fa-facebook"></i>'.esc_html__('Connect With Facebook', 'dt_themes').'</a>';
					echo '<div class="dt-sc-hr-invisible"></div>';
				}
				
				if(dttheme_option('general','enable-googleplus-login') == 'true') {
					echo '<a href="'.dttheme_google_login_url().'" class="dt-sc-social-googleplus-connect"><i class="fa fa-google-plus"></i>'.esc_html__('Connect With Google +', 'dt_themes').'</a>';
					echo '<div class="dt-sc-hr-invisible"></div>';
				}
			
			echo '</div>';
			
		}
		?>
		
		<!-- Login Module End-->

		<?php
		if( have_posts() ):
			while( have_posts() ):
				the_post();
				get_template_part( 'framework/loops/content', 'page' );
			endwhile;
		endif;?>
	</section><!-- ** Primary Section End ** --><?php

	if ( $show_sidebar ):
		if ( $show_right_sidebar ): ?>
			<!-- Secondary Right -->
			<section id="secondary-right" class="secondary-sidebar <?php echo $sidebar_class;?>"><?php get_sidebar( 'right' );?></section><?php
		endif;
	endif;?>
    
<?php get_footer(); ?>