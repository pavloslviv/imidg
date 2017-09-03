            <!-- **Header** -->
            <header id="header" class="header2">
            
                <div class="container">
                    <!-- **Logo - Start** -->
                    <div id="logo">
						<?php
                        if( dttheme_option('general', 'logo') ):
                            $url = dttheme_option('general', 'logo-url');
                            $url = !empty( $url ) ? $url : IAMD_BASE_URL."images/logo.png";
                            
                            $retina_url = dttheme_option('general','retina-logo-url');
                            $retina_url = !empty($retina_url) ? $retina_url : IAMD_BASE_URL."images/logo@2x.png";
                            
                            $width = dttheme_option('general','retina-logo-width');
                            $width = !empty($width) ? $width."px;" : "98px";
                            
                            $height = dttheme_option('general','retina-logo-height');
                            $height = !empty($height) ? $height."px;" : "99px";?>
                            <a href="<?php echo home_url();?>" title="<?php echo dttheme_blog_title();?>">
                                <img class="normal_logo" src="<?php echo esc_url($url);?>" alt="<?php echo dttheme_blog_title(); ?>" title="<?php echo dttheme_blog_title(); ?>" />
                                <img class="retina_logo" src="<?php echo esc_url($retina_url);?>" alt="<?php echo dttheme_blog_title();?>" title="<?php echo dttheme_blog_title(); ?>" style="width:<?php echo esc_attr($width);?>; height:<?php echo esc_attr($height);?>;"/>
                            </a><?php
                        else:?>
                            <h2><a href="<?php echo home_url();?>" title="<?php echo dttheme_blog_title();?>"><?php echo do_shortcode(get_option('blogname')); ?></a></h2><?php
                        endif;?>
                    </div><!-- **Logo - End** -->
            
                    <div class="header-register">                    	
                        <?php dttheme_get_login_logout_url(); ?>
                    </div>                    
            
                    <!-- **Navigation** -->
                    <div id="primary-menu">
                    <nav id="main-menu">
                        <div class="dt-menu-toggle" id="dt-menu-toggle">
                            <?php _e('Menu','dt_themes');?>
                            <span class="dt-menu-toggle-icon"></span>
                        </div>
                    
						<?php 
						$primaryMenu = NULL;
						if( is_page_template('tpl-landingpage.php') ) {
							
							global $post;
							$lp_title = $post->post_title;
							$lp_name = str_replace(' ', '-', trim($post->post_title));
							
							if (function_exists('wp_nav_menu')) :
								$primaryMenu = wp_nav_menu(array(
											'theme_location'=>'landingpage_menu',
											'menu_id'=>'',
											'menu_class'=>'menu',
											'fallback_cb'=>'dttheme_default_navigation',
											'echo'=>false,
											'container'=>false,
											'items_wrap'      => '<ul id="%1$s" class="group %2$s"><li class="menu-item current-menu-item"><a href="#'.$lp_name.'"><i class="fa fa-home"></i>'.$lp_title.'</a></li>%3$s</ul>',
											'walker' => new DTFrontEndMenuWalker()
										));
							endif;
						
						} else {
							
							if (function_exists('wp_nav_menu')) :
								$primaryMenu = wp_nav_menu(array(
											'theme_location'=>'header_menu',
											'menu_id'=>'',
											'menu_class'=>'menu',
											'fallback_cb'=>'dttheme_default_navigation',
											'echo'=>false,
											'container'=>false,
											'walker' => new DTFrontEndMenuWalker()
										));
							endif;
							
						}
						if(!empty($primaryMenu)) echo $primaryMenu;
						?>
                    </nav><!-- **Navigation - End** -->
                    </div>
                                        
                </div>    
            </header><!-- **Header - End** -->