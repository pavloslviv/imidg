<?php get_header(); ?>

	<!-- ** Primary Section ** -->
	<section id="primary" class="content-full-width">
    
        <article id="post-<?php the_ID(); ?>" <?php post_class('dt-sc-certificate-single'); ?>>
            
            <?php
			if(is_user_logged_in()) {
				
				if(IAMD_USER_ROLE == 'administrator' || IAMD_USER_ROLE == 'teacher') {
					
					if( have_posts() ): while( have_posts() ): the_post();
						
						$certificate_id = get_the_ID(); 
						$background_image = get_post_meta ( $certificate_id, 'background-image', TRUE );
						$custom_class = dttheme_wp_kses(get_post_meta ( $certificate_id, 'custom-class', TRUE ));
						$custom_css = dttheme_wp_kses(get_post_meta ( $certificate_id, 'custom-css', TRUE ));
						
						$enable_print = get_post_meta ( $certificate_id, 'enable-print', TRUE );
						
						$out = '';
						
						if(isset($enable_print) && $enable_print != '') {
							$out .= '<a href="#" class="dt_print_certificate"><span class="fa fa-print"></span>'. __('Print', 'dt_themes').'</a>';
						}
							
						$out .= '<div class="dt-sc-certificate-container '.$custom_class.'" style="background:url('.$background_image.')">';
						$out .= do_shortcode(get_the_content());
						$out .= '</div>';
						
						if (!empty($custom_css)) :
							$output = "\r".'<style type="text/css">'."\r".$custom_css."\r".'</style>'."\r";
							$out .= $output;
						endif;
						
						echo $out;
						
					endwhile; endif;
					
				} else {
					
					echo '<div class="dt-sc-info-box">'.__('You don\'t have permission to view the certificate directly !. Please view it from dashboard.', 'dt_themes').'</div>';
					
				}
				
			} else {
				echo '<div class="dt-sc-info-box">'.__('Please login to get access to the certificate !', 'dt_themes').'</div>';
			}
			?>
                    
        </article>
            
	</section><!-- ** Primary Section End ** -->
    
<?php get_footer(); ?>