<?php 
get_header();

$dt_class_type = get_post_meta($post->ID, 'dt-class-type', true);
$dt_class_subtitle = get_post_meta($post->ID, 'dt-class-subtitle', true);

$dt_class_shyllabus_preview = $dt_class_disable_purchases_regsitration = $dt_class_enable_purchases = $dt_class_enable_registration = '';

if($dt_class_type == 'onsite') {
	$dt_class_start_date = get_post_meta($post->ID, 'dt-class-start-date', true);
	$dt_class_capacity = get_post_meta($post->ID, 'dt-class-capacity', true);
	$dt_class_disable_purchases_regsitration = get_post_meta($post->ID, 'dt-class-disable-purchases-regsitration', true);
	$dt_class_enable_purchases = get_post_meta($post->ID, 'dt-class-enable-purchases', true);
	$dt_class_enable_registration = get_post_meta($post->ID, 'dt-class-enable-registration', true);
	$dt_class_shyllabus_preview = get_post_meta($post->ID, 'dt-class-shyllabus-preview', true);
}

if($dt_class_type == 'online') {
	$dt_class_type_icon = '<i class="fa fa-globe"></i>';	
} else {
	$dt_class_type_icon = '<i class="fa fa-building"></i>';	
}

$pholder = dttheme_option('general', 'disable-placeholder-images');
$payment_method = dttheme_option('general','payment-method');

?>

<section id="primary" class="content-full-width">
    <?php 
	if( have_posts() ): while( have_posts() ): the_post();
	
		$class_id = get_the_ID(); 
		$seats_available = dttheme_get_onsite_class_seats_available($class_id);
		$dt_class_featured = get_post_meta($class_id, 'dt-class-featured', true);
		?>
        
		<article id="post-<?php echo $class_id; ?>" <?php post_class('dt-sc-class-single'); ?>>
			
			<?php
			if($payment_method == 'woocommerce') {
						
				$woo_notices = WC()->session->get( 'wc_notices', array() );		
				do_action( 'woocommerce_before_single_product' );
				
				if(empty($woo_notices) && dttheme_is_class_in_cart($class_id)) {
					
					echo '<div class="dt-sc-info-box">'.sprintf( esc_html__('You have already added %s to your cart. Please complete the purchase to get access to this item.', 'dt_themes'), '<strong>'.get_the_title($class_id).'</strong>' ).' <a href="'.WC()->cart->get_checkout_url().'" target="_self" class="dt-sc-info-link"><i class="fa fa-shopping-bag"></i>'.__('Checkout','dt_themes').'</a> '.esc_html('or', 'dt_themes').' <a href="'.WC()->cart->get_cart_url().'" target="_self" class="dt-sc-info-link"><i class="fa fa-cart-plus"></i>'.__('View Cart','dt_themes').'</a>'.'</div>';
					
				}
				
			}
			?>
            
            <div class="dt-sc-class-details-container">
            
            	<div class="column dt-sc-three-fourth first">
                    <div class="dt-sc-class-image">
                        <?php
						if($dt_class_featured == 'true') {
							?>
							<div class="featured-tag"><div><i class="fa fa-thumb-tack"></i><span><?php echo esc_html__('Featured', 'dt_themes'); ?></span></div></div>
							<?php
						}
						
                        if(has_post_thumbnail()) {
                            $image_url = wp_get_attachment_image_src(get_post_thumbnail_id($class_id), 'full');
                            ?>
                            <img src="<?php echo $image_url[0]; ?>" alt="<?php echo get_the_title(); ?>" />
                            <?php 
                        } elseif($pholder != 'on') { 
                            ?>
                            <img src="http<?php echo dttheme_ssl(); ?>://placehold.it/1170x822&text=Image" alt="<?php echo get_the_title(); ?>" />
                            <?php 
                        } 
                        ?>
                        <span class="dt-sc-class-type <?php echo $dt_class_type; ?>"><?php echo $dt_class_type_icon.$dt_class_type; ?></span>
                    </div>
                    <div class="dt-sc-class-details">
                        
                        <h3><?php the_title(); ?></h3>
                        
                        <?php
                        if($dt_class_subtitle != '') {
                            echo '<h4>'.$dt_class_subtitle.'</h4>';
                        }
						
						$dt_class_teacher = get_post_meta($class_id, 'dt-class-teacher', true);
						if($dt_class_teacher != '') {
							
							echo '<div class="dt-class-author">';
							
									if(get_the_post_thumbnail($dt_class_teacher) != '') {
										echo get_the_post_thumbnail($dt_class_teacher, array(40,40));
									} else {
										echo '<img src="http'.dttheme_ssl().'://placehold.it/40x40" alt="author-image" />';
									}

									echo '<div class="dt-class-author-details">
												<label>'.$GLOBALS['teachers-singular-label'].'</label>
												<a href="'.get_permalink($dt_class_teacher).'">'.get_the_title($dt_class_teacher).'</a>
											</div>
								</div>';
						}
						
						if($dt_class_type == 'onsite') {
							?>
                            <div class="dt-sc-class-meta-container">
                                <p>
                                    <i class="fa fa-calendar"></i> 
                                    <?php echo date('F j, Y', strtotime($dt_class_start_date)); ?>
                                </p>
                                <p>
                                    <i class="fa fa-university"></i> 
                                    <?php echo esc_html__('Seats Available', 'dt_themes').' '.$seats_available; ?>
                                </p>
                            </div>
                            <?php
						}
                        ?>
                        
                        <div class="dt-sc-status-container">
                            <?php 
                            if($dt_class_type == 'onsite') {
								
								if($dt_class_enable_purchases == 'true') {
									if($payment_method == 'woocommerce') {
										echo dttheme_get_class_details_linked_with_products($class_id, 'single');
									} else {
										echo dttheme_get_class_details_linked_with_s2member($class_id, 'single');
									}
								} else if($dt_class_enable_registration == 'true') {
									if(dt_check_student_already_registered($class_id)) {
										echo '<span class="dt-sc-purchased">'.esc_html__('You have already registered for this class!', 'dt_themes').'</span>';	
									} else {
										if($seats_available > 0 || ($seats_available <= 0 && $dt_class_disable_purchases_regsitration != 'true')) {
											echo '<a class="dt-sc-button small filled dt-sc-class-registration-btn" target="_self" data-classid="'.$class_id.'"><i class="fa fa-user-plus"></i> '.esc_html('Register Now', 'dt_themes').'</a>';
										} else {
											echo '<span class="dt-sc-purchased">'.esc_html__('Registration Closed', 'dt_themes').'</span>';	
										}
									}
								}
									
                            } else {
								
								if($payment_method == 'woocommerce') {
									echo dttheme_get_class_details_linked_with_products($class_id, 'single');
								} else {
									echo dttheme_get_class_details_linked_with_s2member($class_id, 'single');
								}
								
                            }
                            ?>
                        </div>
                        
                    </div>    
                </div>
                <div class="column dt-sc-one-fourth">
					<?php
                    if(function_exists('the_ratings') && !dttheme_option('general', 'disable-ratings-classes')) { 
                        dttheme_get_single_class_page_ratings($class_id);
                    }
                    ?>
                </div>
				
			</div>  
            
            <?php
			$dt_class_maintabtitle = get_post_meta($class_id, 'dt-class-maintabtitle', true);
			if($dt_class_maintabtitle == '') {
				$dt_class_maintabtitle = esc_html__('About The Program', 'dt_themes');
			}
			
			$dt_class_accessories_tabtitle = get_post_meta($class_id, 'dt-class-accessories-tabtitle', true);
			if($dt_class_accessories_tabtitle == '') {
				$dt_class_accessories_tabtitle = esc_html__('Accessories', 'dt_themes');
			}
			
			$class_content_options = get_post_meta($class_id, 'dt-class-content-options', true);
			$class_content_title = get_post_meta($class_id, 'dt-class-content-title', true);
			
			if($class_content_options != '') {
				
				if($class_content_title != '') {
					
					$content_label = $class_content_title;
					
				} else {
					
					if($class_content_options == 'timetable') {
						$content_label = esc_html__('Schedule', 'dt_themes');
					} else {
						$content_label = esc_html__('Courses', 'dt_themes');
					}
				
				}
			
			}
			
			$class_event_id = get_post_meta($class_id, 'dt-class-event-id', true);
			
			$tab_colors = array('blue', 'red', 'avacado', 'lightred', 'blueturquoise', 'grassgreen', 'brown', 'orange', 'lightgreen', 'skyblue', 'blueris', 'pink', 'blue', 'red', 'avacado', 'lightred', 'blueturquoise', 'grassgreen', 'brown', 'orange', 'lightgreen', 'skyblue', 'blueris', 'pink');
			?>
            
            <div class="column dt-sc-one-fifth first">
                <div class="dt-sc-clear"></div>
                <div class="dt-sc-hr-invisible"></div>          
                <ul class="dt-sc-class-menu-list" id="dt-sc-class-menu-list">
                    <li><a href="#maintab" class="classCustomScroll <?php echo $tab_colors[0]; ?>"><?php echo $dt_class_maintabtitle; ?></a></li>
                    <li><a href="#accessoriestab" class="classCustomScroll <?php echo $tab_colors[1]; ?>"><?php echo $dt_class_accessories_tabtitle; ?></a></li>
                    <?php
					if($class_content_options != '') {
						?>
                        <li><a href="#classcontent" class="classCustomScroll <?php echo $tab_colors[2]; ?>"><?php echo $content_label; ?></a></li>
                        <?php
					}
					
					if($class_event_id != '') {
						echo '<li><a href="#eventtab" class="classCustomScroll '.$tab_colors[3].'">'.esc_html__('Events', 'dt_themes').'</a></li>';
						$icnt = 4;
					} else {
						$icnt = 3;
					}
					
                    $class_tabs_title = get_post_meta ( $class_id, "dt-class-tabs-title", true);                    
                    if(isset($class_tabs_title) && is_array($class_tabs_title)) {
                        foreach($class_tabs_title as $class_tab_title) {
							$class_tab_title_id = str_replace(' ', '', $class_tab_title);
							$class_tab_title_id = strtolower(trim($class_tab_title_id));
							echo '<li><a href="#'.$class_tab_title_id.'" class="classCustomScroll '.$tab_colors[$icnt].'">'.$class_tab_title.'</a></li>';
							$icnt++;
                        }
                    }
					
					$ncnt = $icnt;					
					
					if($dt_class_type == 'onsite') {
						echo '<li><a href="#location" class="classCustomScroll '.$tab_colors[$ncnt].'">'.esc_html__('Location', 'dt_themes').'</a></li>';
						$ncnt = $ncnt+1;	
					}
					
                    ?>
                    <li><a href="#reviews" class="classCustomScroll <?php echo $tab_colors[$ncnt]; ?>"><?php echo esc_html__('Reviews', 'dt_themes'); ?></a></li>
                </ul>
            </div>
            <div class="column dt-sc-four-fifth">
                <div class="dt-sc-clear"></div>
                <div class="dt-sc-hr-invisible"></div>
                
                <div id="maintab" class="dt-sc-class-menu-items <?php echo $tab_colors[0]; ?>">
                    <h2><?php echo $dt_class_maintabtitle; ?></h2>
                    <div class="column dt-sc-one-column first">
                        <?php the_content(); ?>
                    </div>
                    <div class="dt-sc-clear"></div>
                    <div class="dt-sc-hr-invisible"></div>
                </div>
                
                <div id="accessoriestab" class="dt-sc-class-menu-items <?php echo $tab_colors[1]; ?>">
                    <h2><?php echo $dt_class_accessories_tabtitle; ?></h2>
                    <div class="column dt-sc-one-column first">
						<?php 
                        $class_accessories_icon = get_post_meta ( $class_id, "dt-class-accessories-icon", true);
                        $class_accessories_label = get_post_meta ( $class_id, "dt-class-accessories-label", true);
                        $class_accessories_value = get_post_meta ( $class_id, "dt-class-accessories-value", true);
                        
                        $j = 0;
                        if(isset($class_accessories_value) && is_array($class_accessories_value)) {
							echo '<ul class="dt-sc-acessories-list">';
                            foreach($class_accessories_value as $class_accessory_value) {
								echo '<li><span class="'.$class_accessories_icon[$j].'"></span><label>'.$class_accessories_label[$j].'</label> : '.$class_accessory_value.'</li>';
								$j++;
                            }
							echo '</ul>';
                        }
                        ?>
                    </div>
                    <div class="dt-sc-clear"></div>
                    <div class="dt-sc-hr-invisible"></div>
                </div>
                
                <?php
				if($class_content_options != '') {
					?>
                    <div id="classcontent" class="dt-sc-class-menu-items <?php echo $tab_colors[2]; ?>">
                        <h2><?php echo $content_label; ?></h2>
                        <div class="column dt-sc-one-column first">
                            <?php
                            
                            if($class_content_options == 'timetable') {
                                
                                $class_timetable_sc = get_post_meta( $class_id, "dt-class-timetable-sc", true);
                                
                                echo do_shortcode($class_timetable_sc);
                            
                            } else if($class_content_options == 'course') {
                            
                                $class_courses = get_post_meta ( $class_id, "dt-class-courses", true);
                                
                                foreach($class_courses as $class_course) {
                                    
                                    $course_status_html = '';
                                    $payment_method = dttheme_option('general','payment-method');
                    
                                    if($payment_method == 'woocommerce') {
                                        
                                        if(dttheme_check_if_user_subscribed_this_class($class_id) || dttheme_check_if_user_purchased_this_class($class_id)) {
                                            $course_status = dt_get_users_course_status($class_course, '');
                                            if($course_status) {
                                                $course_status_html = '<span class="dt-sc-course-completed"> <span class="fa fa-check-circle"> </span> '.__('Completed', 'dt_themes').'</span>';
                                            } else {
                                                $course_status_html = '<span class="dt-sc-course-inprogress"> <span class="fa fa-cog"> </span> '.__('Inprogress', 'dt_themes').'</span>';
                                            }
                                        }
                                        
                                    } else {
                                        
                                        $s2_level = 'access_s2member_ccap_classid_'.$class_id;									
                                        if (dttheme_check_is_s2member_level_user(true) || current_user_can($s2_level)){
                                            $course_status = dt_get_users_course_status($class_course, '');
                                            if($course_status) {
                                                $course_status_html = '<span class="dt-sc-course-completed"> <span class="fa fa-check-circle"> </span> '.__('Completed', 'dt_themes').'</span>';
                                            } else {
                                                $course_status_html = '<span class="dt-sc-course-inprogress"> <span class="fa fa-cog"> </span> '.__('Inprogress', 'dt_themes').'</span>';
                                            }
                                        }
                                        
                                    }
            
                                    $lesson_args = array('sort_order' => 'ASC', 'sort_column' => 'menu_order', 'hierarchical' => 1, 'post_type' => 'dt_lessons', 'posts_per_page' => -1, 'meta_key' => 'dt_lesson_course', 'meta_value' => $class_course );
                                    $lessons_array = get_pages( $lesson_args );
                                    $lessons_cnt = count($lessons_array);
                                    
                                    $assignments_args = array('sort_order' => 'ASC', 'sort_column' => 'menu_order', 'hierarchical' => 1, 'post_type' => 'dt_assignments', 'posts_per_page' => -1, 'meta_key' => 'dt-assignment-course', 'meta_value' => $class_course );
                                    $assignments_array = get_pages( $assignments_args );
                                    $assignments_cnt = count($assignments_array);
                        
                                    $courses_overview = '<span class="dt-sc-courses-overview">';
                                    $courses_overview .= $lessons_cnt.' '.esc_html__('Lessons', 'dt_themes');
                                    if($assignments_cnt > 0) {
                                        $courses_overview .= '<span class="dt-sc-courses-overview-sep"></span>';
                                        $courses_overview .= $assignments_cnt.' '.esc_html__('Assignments', 'dt_themes');
                                    }
                                    $courses_overview .= '</span>';
                                    
                                    echo '<div class="dt-sc-class-toggle-frame">
                                            <h5 class="dt-sc-class-toggle">';
                                                if($dt_class_shyllabus_preview == 'true') {
                                                    $course_link = '#';
                                                } else {
                                                    $course_link = get_permalink($class_course);
                                                }
                                                echo '<span class="dt-sc-class-toggle-switch"></span>';
                                                echo '<a href="'.$course_link.'">'.get_the_title($class_course).'</a>'.$courses_overview.$course_status_html.'
                                            </h5>
                                            
                                            <div style="display: none;" class="dt-sc-class-toggle-content">
                                                <div class="block">
                                                
                                                    <div class="dt-sc-tabs-vertical-container">
                                                        <ul class="dt-sc-tabs-vertical-frame">
                                                            <li class="first current"><a href="#" class="current">'.esc_html__('Lessons', 'dt_themes').'<span></span></a></li>
                                                            <li><a href="#">'.esc_html__('Assignments', 'dt_themes').'<span></span></a></li>
                                                        </ul>
                                                        <div class="dt-sc-tabs-vertical-frame-content" style="display: block;">';
                                                        
                                                            // Lessons
                                                            
                                                            if(!empty($lessons_array)) {
                                                                echo '<ul class="dt-sc-class-lessons-list">';
                                                                    foreach($lessons_array as $lesson) {
                                                                        if($dt_class_shyllabus_preview == 'true') {
                                                                            $lesson_link = '#';
                                                                        } else {
                                                                            $lesson_link = get_permalink($lesson);
                                                                        }
                                                                        echo '<li><a href="'.$lesson_link.'">'.get_the_title($lesson).'</a>'.dttheme_check_lesson_grade($class_course, $lesson->ID).'</li>';
                                                                    }
                                                                echo '</ul>';
                                                            } else {
                                                                echo esc_html__('No Lessons Found!', 'dt_themes');
                                                            }
        
                                                  echo '</div>
                                                        <div class="dt-sc-tabs-vertical-frame-content" style="display: none;">';
                                                        
                                                            // Assignments
                                                            
                                                            if(!empty($assignments_array)) {	
                                                                echo '<ul class="dt-sc-class-assignments-list">';
                                                                    foreach($assignments_array as $assignment) {
                                                                        if($dt_class_shyllabus_preview == 'true') {
                                                                            $assignment_link = '#';
                                                                        } else {
                                                                            $assignment_link = get_permalink($assignment);
                                                                        }
                                                                        echo '<li><a href="'.$assignment_link.'">'.get_the_title($assignment).'</a>'.dttheme_check_assignment_grade($class_course, $assignment->ID).'</li>';
                                                                    }
                                                                echo '</ul>';
                                                            } else {
                                                                echo esc_html__('No Assignments Found!', 'dt_themes');
                                                            }
                                                            
                                                    echo '</div>
                                                    </div>
                                                    <div class="dt-sc-clear"></div>
                                                    
                                                </div>
                                            </div>
                                        </div>';
                                    
                                }
                            
                            }
                            
                            ?>
                        </div>
                        <div class="dt-sc-clear"></div>
                        <div class="dt-sc-hr-invisible"></div>
                    </div>
                    
                    <?php 
				}
				
				
				if($class_event_id != '') {
					?>
                        <div id="eventtab" class="dt-sc-class-menu-items <?php echo $tab_colors[3]; ?>">
                            <h2><?php echo esc_html__('Events', 'dt_themes'); ?></h2>
                            <div class="column dt-sc-one-column first">
								<?php
								$args = array( 'posts_per_page'=>-1, 'post_type'=> 'tribe_events', 'post__in' => $class_event_id );
								query_posts($args);
								if( have_posts() ):
									while( have_posts() ):
										the_post();
											
											$venue_details = tribe_get_venue_details();
											
											$has_venue = ( $venue_details ) ? ' vcard' : '';
											$has_venue_address = ( ! empty( $venue_details['address'] ) ) ? ' location' : '';
											?>
											
											<div class="tribe-events-day-time-slot">
																	
												<?php if ( tribe_get_cost() ) : ?>
													<div class="tribe-events-event-cost">
														<span><?php echo tribe_get_cost( null, true ); ?></span>
													</div>
												<?php endif; ?>
												
												<h2 class="tribe-events-list-event-title summary">
													<a class="url" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title_attribute() ?>" rel="bookmark">
														<?php the_title() ?>
													</a>
												</h2>
												
												<div class="tribe-events-event-meta <?php echo esc_attr( $has_venue . $has_venue_address ); ?>">
												
													<div class="tribe-updated published time-details">
														<?php echo tribe_events_event_schedule_details(); ?>
													</div>
												
													<?php if ( $venue_details ) : ?>
														<div class="tribe-events-venue-details">
															<?php echo implode( ', ', $venue_details ); ?>
														</div>
													<?php endif; ?>
												
												</div>
												
												<?php echo tribe_event_featured_image( null, 'thumb' ); ?>
												
												<div class="tribe-events-list-event-description tribe-events-content description entry-summary">
													<?php echo tribe_events_get_the_excerpt(); ?>
													<a href="<?php echo esc_url( tribe_get_event_link() ); ?>" class="tribe-events-read-more" rel="bookmark"><?php esc_html_e( 'Find out more', 'the-events-calendar' ) ?> &raquo;</a>
												</div>
											
											</div>
											
											<?php
									endwhile;
								endif;
								wp_reset_query();
								?>
                            </div>
                            <div class="dt-sc-clear"></div>
                            <div class="dt-sc-hr-invisible"></div>
                        </div>
                    <?php
					$icnt = 4;
				} else {
					$icnt = 3;
				}
				
				
                $class_tabs_title = get_post_meta ( $class_id, "dt-class-tabs-title", true);
                $class_tabs_content = get_post_meta ( $class_id, "dt-class-tabs-content", true);
                
                $j = 0;
                if(isset($class_tabs_content) && is_array($class_tabs_content)) {
                    foreach($class_tabs_content as $class_tab_content) {
						$class_tab_title_id = str_replace(' ', '', $class_tabs_title[$j]);
						$class_tab_title_id = strtolower(trim($class_tab_title_id));
                    	?>
                        <div id="<?php echo $class_tab_title_id; ?>" class="dt-sc-class-menu-items <?php echo $tab_colors[$icnt]; ?>">
                            <h2><?php echo $class_tabs_title[$j]; ?></h2>
                            <div class="column dt-sc-one-column first">
                                <?php echo do_shortcode($class_tab_content); ?>
                            </div>
                            <div class="dt-sc-clear"></div>
                            <div class="dt-sc-hr-invisible"></div>
                        </div>
						<?php
                        $j++;
						$icnt++;
                    }
                }
				
				$ncnt = $icnt;
				
				if($dt_class_type == 'onsite') {
					$dt_class_gps = get_post_meta($class_id, 'dt-class-gps', true);
					$dt_class_address = get_post_meta($class_id, 'dt-class-address', true);
					?>
                    <div id="location" class="dt-sc-class-menu-items <?php echo $tab_colors[$ncnt]; ?>">
                        <h2><?php echo esc_html('Location', 'dt_themes'); ?></h2>
                        <div class="column dt-sc-one-column first">
                        	<?php echo dttheme_wp_kses($dt_class_address);?>
                            <div class="dt-sc-hr-invisible"></div>
                            <div class="dt-sc-clear"></div>
                            <div class="dt-sc-onsite-map-container">
                                <div id="dt-sc-onsite-map" style="margin: 0;padding: 0;height: 100%;" data-title="<?php the_title();?>" data-lat="<?php echo dttheme_wp_kses($dt_class_gps['latitude']);?>" data-lng="<?php echo dttheme_wp_kses($dt_class_gps['longitude']);?>" data-address="<?php echo dttheme_wp_kses($dt_class_address);?>"></div>
                            </div>
                        </div>
                        <div class="dt-sc-clear"></div>
                        <div class="dt-sc-hr-invisible"></div>
                    </div>
                    <?php	
					$ncnt = $ncnt+1;	
				}
                ?>
                
                <div id="reviews" class="dt-sc-class-menu-items <?php echo $tab_colors[$ncnt]; ?>">
                    <h2><?php echo esc_html('Reviews', 'dt_themes'); ?></h2>
                    <div class="column dt-sc-one-column first">
                        <?php comments_template('', true); ?>
                    </div>
                    <div class="dt-sc-clear"></div>
                    <div class="dt-sc-hr-invisible"></div>
                </div>
                
            </div>
                                                       			
		</article>
		
		<?php 
	endwhile; endif; 
	?>
    
    <div class="clear"> </div>
    <div class="dt-sc-hr-invisible"> </div>
       
    <?php
    edit_post_link(__('Edit', 'dt_themes'), ' <div class="dt-sc-hr-invisible"> </div><span class="edit-link">', '</span> <div class="dt-sc-hr-invisible"> </div>' );
    ?>
            
</section>

<?php get_footer(); ?>