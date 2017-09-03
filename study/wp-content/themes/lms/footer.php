    <?php if( !is_page_template( 'tpl-fullwidth.php' ) && !is_page_template('tpl-landingpage.php') && !is_page_template('tpl-demopage.php') ): ?>
            </div><!-- **Container - End** -->
    <?php endif;?>
        </div><!-- **Main - End** -->


        <?php $dttheme_options = get_option(IAMD_THEME_SETTINGS); $dttheme_general = $dttheme_options['general'];?>
        <!-- **Footer** -->
        <footer id="footer">
        
			<?php
            if(!is_page_template('tpl-demopage.php')) {
                ?>
                <!--<div class="footer-logo">  
                        <?php
                        $flogo = dttheme_option('general','footer-logo-url');
                        $flogo = !empty($flogo) ? $flogo : IAMD_BASE_URL."images/footer-logo.png";
                        
                        $retina_url = dttheme_option('general','retina-footer-logo-url');
                        $retina_url = !empty($retina_url) ? $retina_url : IAMD_BASE_URL."images/footer-logo@2x.png";
                        
                        $width = dttheme_option('general','retina-footer-logo-width');
                        $width = !empty($width) ? $width."px;" : "98px";
                        
                        $height = dttheme_option('general','retina-footer-logo-height');
                        $height = !empty($height) ? $height."px;" : "99px";
                        ?>
                        <img class="normal_logo" src="<?php echo esc_url($flogo);?>" alt="<?php _e('Footer Logo','dt_themes');?>" title="<?php _e('Footer Logo','dt_themes');?>">
                        <img class="retina_logo" src="<?php echo esc_url($retina_url);?>" alt="<?php echo dttheme_blog_title();?>" title="<?php echo dttheme_blog_title(); ?>" style="width:<?php echo esc_attr($width);?>; height:<?php echo esc_attr($height);?>;"/>            
                </div> 
                <?php
                if(!empty($dttheme_general['show-footer'])): 
					$footer_bg = dttheme_option('general','footer-bg');
					$footer_bg_repeat = dttheme_option('general','footer-bg-repeat');
					$footer_bg_position = dttheme_option('general','footer-bg-position');
					
					if($footer_bg != '') {
						$footer_bg_position = isset($footer_bg_position) ? 'background-position:'.$footer_bg_position.';' : '';
						$footer_bg_repeat = isset($footer_bg_repeat) ? 'background-repeat:'.$footer_bg_repeat.';' : 'background-repeat:no-repeat;';
						$footerbg = 'style="background: url('.$footer_bg.'); '.$footer_bg_repeat.' '.$footer_bg_position.'"';
					} else {
						$footerbg = '';
					}
					?>
                    <div class="footer-widgets-wrapper" <?php echo $footerbg; ?>>
                        <div class="container"><?php dttheme_show_footer_widgetarea($dttheme_general['footer-columns']);?></div>
                    </div>
                    <?php
                endif;?>
				<?php
			}
			?>-->

        	<div class="copyright">
        		<div class="container"><?php
        			if( !empty($dttheme_general['show-copyrighttext']) ):
        				echo '<div class="copyright-info">';
        				echo dttheme_wp_kses(stripslashes($dttheme_general['copyright-text']));
        				echo '</div>'; 
        			endif;?>
        			<!--<?php echo do_shortcode('[dt_sc_social /]'); ?> -->
        		</div>
        	</div>
        </footer><!-- **Footer - End** -->
    </div><!-- **Inner Wrapper - End** -->
</div><!-- **Wrapper - End** -->
<?php
	if (is_singular() AND comments_open())
		wp_enqueue_script( 'comment-reply');

	if(dttheme_option('integration', 'enable-body-code') != '') 
		echo '<script type="text/javascript">'.dttheme_wp_kses(stripslashes(dttheme_option('integration', 'body-code'))).'</script>';
	wp_footer(); ?>
</body>
</html>