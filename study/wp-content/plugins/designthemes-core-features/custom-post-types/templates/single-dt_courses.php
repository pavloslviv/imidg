<?php 
get_header();

//GETTING META VALUES...
$course_settings = get_post_meta($post->ID, '_course_settings', true);
$course_settings = is_array( $course_settings ) ? $course_settings  : array();

if($GLOBALS['force_enable'] == true)
	$page_layout = $GLOBALS['page_layout'];
else
	$page_layout = !empty($course_settings['layout']) ? $course_settings['layout'] : 'content-full-width';

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

$ts = get_post_meta($post->ID, '_course_settings', true);
$s2_level = "access_s2member_ccap_cid_{$post->ID}";

$pholder = dttheme_option('general', 'disable-placeholder-images');

$payment_method = dttheme_option('general','payment-method');

$class_id = isset($_REQUEST['class_id']) ? $_REQUEST['class_id'] : -1;

if ( $show_sidebar ):
	if ( $show_left_sidebar ): 
		?>
		<section id="secondary-left" class="secondary-sidebar <?php echo $sidebar_class;?>">
        	<?php 
			if(dttheme_option('dt_course','enable-all-course-widgets') == 'true' && $page_layout == 'page-with-sidebar with-left-sidebar') {
				dttheme_get_single_course_page_widget_rightside(get_the_ID());
			}
			if(dttheme_option('dt_course','enable-course-widget-left') == 'true') {
				dttheme_get_single_course_page_widget_leftside(get_the_ID());
			}
			?>
			<?php get_sidebar( 'left' );?>
        </section>
		<?php
	endif;
endif;

?>

<section id="primary" class="<?php echo $page_layout;?>">
    <?php 
	
	if( have_posts() ): while( have_posts() ): the_post();
		$course_post_id = get_the_ID(); 
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('dt-sc-course-single'); ?>>
			
			<?php
			if($payment_method == 'woocommerce') {
						
				$woo_notices = WC()->session->get( 'wc_notices', array() );		
				do_action( 'woocommerce_before_single_product' );
				
				if(empty($woo_notices) && dttheme_is_course_in_cart($course_post_id)) {
					
					echo '<div class="dt-sc-info-box">'.esc_html__('You have already added this course to your cart. Please complete the purchase to get access to this course.', 'dt_themes').' <a href="'.WC()->cart->get_checkout_url().'" target="_self" class="dt-sc-info-link"><i class="fa fa-shopping-bag"></i>'.__('Checkout','dt_themes').'</a> '.esc_html('or', 'dt_themes').' <a href="'.WC()->cart->get_cart_url().'" target="_self" class="dt-sc-info-link"><i class="fa fa-cart-plus"></i>'.__('View Cart','dt_themes').'</a>'.'</div>';
					
				}
				
			}
			?>
			
			<div class="dt-sc-course-details">
				<div class="dt-sc-course-image">
					<?php
					if(has_post_thumbnail()) {
						$image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full');
						?>
						<img src="<?php echo $image_url[0]; ?>" alt="<?php echo get_the_title(); ?>" />
						<?php 
					} elseif($pholder != 'on') { 
						?>
						<img src="http<?php echo dttheme_ssl(); ?>://placehold.it/1170x822&text=Image" alt="<?php echo get_the_title(); ?>" />
						<?php 
					} 
					
					$featured_course = get_post_meta($course_post_id, 'featured-course', true);
					if(isset($featured_course) && $featured_course == 'true') {
						?>
						<div class="featured-post"> <span class="fa fa-trophy"> </span> <span class="text"> <?php _e('Featured','dt_themes');?> </span></div>
						<?php 
					} 
					
					$enable_certificate = get_post_meta($course_post_id, 'enable-certificate', true);
					$enable_badge = get_post_meta($course_post_id, 'enable-badge', true);
					
					if($enable_certificate == 'true' || $enable_badge == 'true') {
						echo '<div class="dt-sc-certificate-badge">';
							if($enable_certificate == 'true') {
								echo '<span class="certificate"></span>';	
							}
							if($enable_badge == 'true') {
								echo '<span class="badge"></span>';	
							}
						echo '</div>';
					}
					?>
				</div>
				<div class="dt-sc-course-details-inner">

					<h3><?php the_title(); ?></h3>
					
					<?php
					if(dttheme_check_if_course_exists_in_class($course_post_id)) {
						echo '<div class="dt-sc-course-class"><i class="fa fa-institution"></i>'.dttheme_get_course_classes_links($course_post_id).'</div>';
					}
					?>
					
					<div class="entry-metadata">
					
						<div class="dt-sc-meta-container">
							<p><i class="fa fa-location-arrow"> </i> <?php the_terms($course_post_id,'course_category'); ?></p>
							<p><i class="fa fa-book"> </i> 
							<?php echo dttheme_get_lessons_count($course_post_id).__(' Уроки', 'dt_themes'); ?>
							</p>
							<p><i class="fa fa-clock-o"> </i> <?php echo dttheme_get_lessons_durations($course_post_id, ''); ?></p>
						</div>
                        
                        <?php echo dttheme_get_user_course_progress($course_post_id); ?>
					
						
						
					</div>
					
					<?php
					if (shortcode_exists('yith_wcwl_add_to_wishlist')) {
						if(!dttheme_check_if_user_authorized_to_view_course($course_post_id)) {
							$product_ids = dttheme_get_course_all_products($course_post_id);
							$pnum = (count($product_ids)-1);
							if(isset($product_ids[$pnum]) && !empty($product_ids[$pnum])) {
								echo '<div class="dt-sc-wishlist-holder">';
									echo do_shortcode('[yith_wcwl_add_to_wishlist product_id="'.$product_ids[$pnum].'" /]');
								echo '</div>';
							}
						}
					}
					?>
						
				</div>
			</div>
							
			<?php			 
			if(dttheme_check_if_user_authorized_to_view_course($course_post_id) || dttheme_check_to_show_course_content($course_post_id)) { 
				?>                
							
				<div class="dt-sc-clear"></div>
				<div class="dt-sc-hr-invisible-small"></div>
			   
				<section class="entry">
				
					<?php the_content(); ?>  
					
					<div class="dt-sc-hr-invisible-small"></div> 
					
					<?php 
					if(isset($course_settings['referrrence_url'])) {
						echo '<strong>'.__('Referrrence URL: ','dt_themes').'</strong><a href="'.esc_url($course_settings['referrrence_url']).'">'.esc_url($course_settings['referrrence_url']).'</a>';
					}
					?>
					
				</section>
				
				<div class="dt-sc-clear"></div>
				<div class="dt-sc-hr-invisible"></div>
				
				<?php 
				$course_video = get_post_meta($course_post_id, 'course-video', true);
				if(isset($course_video) && $course_video != '') { 
					?>
					<h4 class="border-title"><?php _e('Course Intro Video', 'dt_themes'); ?><span></span></h4>
					<div class="course-video">
						<?php 
						if(wp_oembed_get( $course_video ) != '') { 
							echo wp_oembed_get( $course_video ); 
						} else { 
							echo wp_video_shortcode( array('src' => $course_video) ); 
						}
						?>
					</div>
					<div class="dt-sc-clear"></div>
					<div class="dt-sc-hr-invisible-medium"></div>
					<?php 
				} 
				?>
	
				<?php
			
			} else {
	
				echo '<div class="clear"></div><div class="dt-sc-hr-invisible"></div>';
				echo '<div class="dt-sc-warning-box">';
				echo esc_html__('You have to purchase this course in order to get access to its content.', 'dt_themes');
				echo '</div>';
				
			}
			
			
			// Show Lessons
			$lessons_array = $staffs_id = array();
			$lesson_args = array('sort_order' => 'ASC', 'sort_column' => 'menu_order', 'hierarchical' => 1, 'post_type' => 'dt_lessons', 'posts_per_page' => -1, 'meta_key' => 'dt_lesson_course', 'meta_value' => $course_post_id );
			$lessons_array = get_pages( $lesson_args );
			
			if(isset($lessons_array) && !empty($lessons_array)) {		
				
				echo '<div class="dt-lesson-wrapper">
					<div class="dt-lesson-inner-wrapper">
					<h4 class="dt-lesson-title">'.__('Lessons', 'dt_themes').'<span></span></h4>';
			
				$lessons_hierarchy_array = array();
				foreach ( (array) $lessons_array as $p ) {
					$lesson_teacher = get_post_meta ( $p->ID, "lesson-teacher",true);
					if($lesson_teacher != '')
						$staffs_id[] = $lesson_teacher;
					
					$parent_id = intval( $p->post_parent );
					$lessons_hierarchy_array[ $parent_id ][] = $p;
				}
				
				if(isset($lessons_hierarchy_array[0])) {
					$out = '';
					$i = 1;
					$out .= '<ol class="dt-sc-lessons-list">';
					foreach($lessons_hierarchy_array[0] as $lesson) {
						$lesson_meta_data = get_post_meta($lesson->ID, '_lesson_settings');
						$lesson_teacher = $lesson_duration = '';
						$private_lesson = !empty($lesson_meta_data[0]['private-lesson']) ? $lesson_meta_data[0]['private-lesson'] : '';
						
						$lesson_teacher = get_post_meta ( $lesson->ID, "lesson-teacher",true);
						if($lesson_teacher != '') {
							$teacher_data = get_post($lesson_teacher);
							if($private_lesson != '') {
								$lesson_teacher = '<p> <i class="fa fa-user"> </i>'.$teacher_data->post_title.'</p>';
							} else {
								$lesson_teacher = '<p> <i class="fa fa-user"> </i><a href="'.get_permalink($teacher_data->ID).'">'.$teacher_data->post_title.'</a></p>';
							}
						}
						
						if(isset($lesson_meta_data[0]['lesson-duration']) && $lesson_meta_data[0]['lesson-duration'] != '') {
							$lesson_duration_data = $lesson_meta_data[0]['lesson-duration'];
							
							if($lesson_duration_data > 0) {
								$hours = floor($lesson_duration_data/60); 
								$mins = $lesson_duration_data % 60; 
								if($hours == 0) {
									$lesson_duration_data = $mins . __(' mins ', 'dt_themes'); 				
								} elseif($hours == 1) {
									$lesson_duration_data = $hours .  __(' hour ', 'dt_themes') . $mins . __(' mins ', 'dt_themes'); 				
								} else {
									$lesson_duration_data = $hours . __(' hours ', 'dt_themes') . $mins . __(' mins ', 'dt_themes'); 				
								}
							}
	
							$lesson_duration .= '<p> <i class="fa fa-clock-o"> </i>'.dttheme_wp_kses($lesson_duration_data).'</p>';
						}
						
						if(isset($lesson_meta_data[0]['private-lesson']) && $lesson_meta_data[0]['private-lesson'] != '') {
							if ( dttheme_check_if_user_authorized_to_view_course($course_post_id) ){
								$private_lesson = '';
							} else {
								$private_lesson = 'dt-hidden-lesson';
							}
						} else {
							$private_lesson = '';
						}
						
						$terms = get_the_terms($lesson->ID,'lesson_complexity');
						$lesson_terms = '';
						if(isset($terms) && !empty($terms)) {
							$lesson_terms = array();
							foreach ( $terms as $term ) {
								if($private_lesson != '') {
									$lesson_terms[] = $term->name;
								} else {
									$lesson_terms[] = '<a href="'.get_term_link( $term->slug, 'lesson_complexity' ).'">'.$term->name.'</a>';
								}
							}
							$lesson_terms = join( ", ", $lesson_terms );
						}
						
						$grade_chk = $grade_cls = '';
						if(is_user_logged_in() && $private_lesson != 'dt-hidden-lesson') {
							$user_id = get_current_user_id();
							$course_id = $course_post_id;
							$lesson_id = $lesson->ID;
							$quiz_id = get_post_meta ($lesson_id, "lesson-quiz", true);
							if(!isset($quiz_id) || $quiz_id == '') $quiz_id = -1;
	
							$dt_gradings = dt_get_user_gradings_array($course_id, $lesson_id, $quiz_id, $user_id);
							$dt_grade_post = get_posts( $dt_gradings );
							
							$dt_grade_post_id = isset($dt_grade_post[0]->ID) ? $dt_grade_post[0]->ID : 0;
							
							$graded = get_post_meta ( $dt_grade_post_id, "graded",true);
							if(isset($graded) && $graded != '') {
								$grade_chk = '<div class="dt-sc-lesson-completed"> <span class="fa fa-check-circle"> </span> '.__('Completed', 'dt_themes').'</div>';
								$grade_cls = ' dt-lesson-complete';
							}
						}
						
						$out .= '<li class="'.$private_lesson.$grade_cls.'">';
									if($private_lesson != '') {
										$out .= '<div class="hidden-lesson-overlay"> </div>';
									}
							$out .= '<article class="dt_lessons">
										<div class="lesson-title">';
											if($private_lesson != '') {
												$out .= '<h2>'.$lesson->post_title.'</h2>';
											} else {
												$out .= '<h2> <a href="'.get_permalink($lesson->ID).'" title="'.$lesson->post_title.'">'.$lesson->post_title.'</a> </h2>';
											}
											$out .= $grade_chk;
									$out .= '<div class="lesson-metadata">';
											if($lesson_terms != '') { 
												 $out .= '<p> <i class="fa fa-tags"> </i> '.$lesson_terms.' </p>';
											}
											$out .= $lesson_duration.$lesson_teacher.'
										   </div>
										</div>
										
										<div class="dt-sc-clear"></div>
										<div class="dt-sc-hr-invisible-small"></div>
										
										<section class="lesson-details">
											'.$lesson->post_excerpt.'
										</section>
									</article>';
							$out .= dttheme_get_lesson_details( $lessons_hierarchy_array,  $lesson->ID, $s2_level );
						$out .= '</li>';
						
						$i++;
					}
					$out .= '</ol>';
					echo $out;
				}
				echo '</div></div>';
			
			}
	
			// Show Assignments
			$assignments_args = array('sort_order' => 'ASC', 'sort_column' => 'menu_order', 'hierarchical' => 1, 'post_type' => 'dt_assignments', 'posts_per_page' => -1, 'meta_key' => 'dt-assignment-course', 'meta_value' => $course_post_id );
			$assignments_array = get_pages( $assignments_args );
			
			if(isset($assignments_array) && !empty($assignments_array)) {		
			
				echo '<div class="clear"></div>
					  <div class="dt-sc-hr-invisible"></div>';
						
				echo '<div class="dt-lesson-wrapper">
						<div class="dt-lesson-inner-wrapper">
							<h4 class="dt-lesson-title">'.__('Assignments', 'dt_themes').'<span></span></h4>';
							
							echo '<ol class="dt-sc-lessons-list">';
							foreach($assignments_array as $assignment) {
								
								$grade_chk = $grade_cls = '';
								
								$assignment_id = $assignment->ID;
								$subtitle = get_post_meta ($assignment->ID, "assignment-subtitle", true);
								$assignment_private = get_post_meta ($assignment->ID, "assignment-private",true);
								
								if(isset($assignment_private) && $assignment_private != '') {
									if ( dttheme_check_if_user_authorized_to_view_course($course_post_id) ){
										$private_assignment = '';
									} else {
										$private_assignment = 'dt-hidden-lesson';
									}
								} else {
									$private_assignment = '';
								}
								
								$user_id = get_current_user_id();
								$dtgradings = array( 'post_type' => 'dt_gradings', 'meta_query'=>array() );
								$dtgradings['meta_query'][] = array( 'key' => 'dt-user-id', 'value' => $user_id, 'compare' => '=', 'type' => 'numeric' );
								$dtgradings['meta_query'][] = array( 'key' => 'dt-course-id', 'value' => $course_post_id, 'compare' => '=', 'type' => 'numeric' );
								$dtgradings['meta_query'][] = array( 'key' => 'dt-assignment-id', 'value' => $assignment_id, 'compare' => '=', 'type' => 'numeric' );
								$dtgradings['meta_query'][] = array( 'key' => 'grade-type', 'value' => 'assignment', 'compare' => '=' );
								$dtgradings_post = get_posts( $dtgradings );
								
								if(isset($dtgradings_post) && !empty($dtgradings_post) && $private_assignment != 'dt-hidden-lesson') {
									
									$dtgradings_id = $dtgradings_post[0]->ID;
									$marks_obtained_percent = get_post_meta ( $dtgradings_id, "marks-obtained-percent", true); 
									$graded = get_post_meta ($dtgradings_id, "graded", true);
									
									if(isset($graded) && $graded != '') { 
										$grade_chk = '<div class="dt-sc-assignment-completed"> <span class="fa fa-check-circle"> </span> '.__('Completed', 'dt_themes').'</div>';
										$grade_cls = ' dt-assignment-complete';
									}
									
								}
																
								echo '<li class="'.$grade_cls.' '.$private_assignment.'">';
										if($private_assignment != '') {
											echo '<div class="hidden-lesson-overlay"> </div>';
										}
								echo '<article class="dt_lessons">
											<div class="lesson-title">
												<h2><a href="'.get_permalink($assignment_id).'">'.$assignment->post_title.'</a></h2>
												<h5>'.$subtitle.'</h5>
												'.$grade_chk.'
											</div>
										</article>
									</li>';
							}
							
							echo '</ol>';
							
				echo '	</div>
					</div>';		
			
			}

			?>
						
		</article>
		
		<?php 
	endwhile; endif; 
	?>
	
	<div class="clear"> </div>
	<div class="dt-sc-hr-invisible"> </div>
	<?php 
	if(!dttheme_option('general', 'disable-courses-comment')) { 
		comments_template('', true); 
	}
	?>
	
	<?php 
	if(!array_key_exists("disable-staffs",$course_settings) && !empty($staffs_id[0])): 
		?>
		
		<div class="clear"> </div>
		<div class="dt-sc-hr-invisible"> </div>
		<h3><?php echo $GLOBALS['teachers-plural-label']; ?></h3> 
		
		<?php        
		
		$staffs_id = array_unique (array_filter($staffs_id));
		$out = ''; $cnt = 1;
		
		foreach($staffs_id as $staff_id) {
			
			if(($cnt%4) == 1) $firstcls = ' first'; else $firstcls = '';
			$staff_settings = get_post_meta ( $staff_id, '_teacher_settings', TRUE );
			
			$s = "";
			$sociables_icons_path  = plugin_dir_url(__FILE__);
			$x =  explode ( "designthemes-core-features" , $sociables_icons_path );
			$path = $x[0].'designthemes-core-features/shortcodes/images/sociables/';
	
			if(isset($staff_settings['teacher-social'])) {
				foreach ( $staff_settings['teacher-social'] as $sociable => $social_link ) {
					if($social_link != '') {
						$img = $sociable;
						$class = explode(".",$img);
						$class = $class[0];
						$s .= "<li class='{$class}'><a href='{$social_link}' target='_blank'> <img src='{$path}hover/{$img}' alt='{$class}'/>  <img src='{$path}{$img}' alt='{$class}'/> </a></li>";
					}
				}
			}
			$s = ! empty ( $s ) ? "<div class='dt-sc-social-icons'><ul>$s</ul></div>" : "";
			
			//FOR AJAX...
			$nonce = wp_create_nonce("dt_team_member_nonce");
			$link = admin_url('admin-ajax.php?ajax=true&amp;action=dttheme_team_member&amp;post_id='.$staff_id.'&amp;nonce='.$nonce);
						
			$out .= '<li class="column dt-sc-one-fourth">';	
			$out .= "   <div class='dt-sc-team'>";
			$out .= "		<div class='image'>";
								if(get_the_post_thumbnail($staff_id, 'full') != ''):
									$out .= get_the_post_thumbnail($staff_id, 'full');
								else:
									$out .= '<img src="http'.dttheme_ssl().'://placehold.it/400x420" alt="member-image" />';
								endif;
			$out .= " 		</div>";
			$out .= '		<div class="team-details">';
			$out .= '			<h5><a href="'.$link.'" data-gal="prettyPhoto[pp_gal]">'.get_the_title($staff_id).'</a></h5>';
								if(isset($staff_settings['role']) && $staff_settings['role'] != '')
									$out .= "<h6>".$staff_settings['role']."</h6>";
								if(isset($staff_settings['show-social-share']) && $staff_settings['show-social-share'] != '') $out .= $s;
			$out .= '		</div>';
			$out .= '   </div>';
			$out .= '</li>';	
			
			$cnt++;
		
		}
		echo '<div class="dt-sc-staff-carousel-wrapper"><ul class="dt-sc-staff-carousel">'.$out.'</ul><div class="carousel-arrows"><a class="staff-prev" href=""></a><a class="staff-next" href=""></a></div></div>';
		
		?>
		
		<?php 
	endif;
	
	if(array_key_exists("show-related-course",$course_settings)):
		?>
	
		<div class="clear"> </div>
		<div class="dt-sc-hr-invisible"> </div>
		
		<div class="dt-sc-related-courses">
		<h3><?php _e('Related Courses','dt_themes');?></h3> 
		<?php
		
		$category_ids = array();
		$allcats  = wp_get_object_terms( $course_post_id, 'course_category');
		
		foreach($allcats as $category) $category_ids[] = $category->term_id;
		
		$args = array('orderby' => 'rand', 'showposts' => '3', 'post__not_in' => array($course_post_id), 'tax_query' => array( array( 'taxonomy'=>'course_category', 'field'=>'id', 'operator'=>'IN', 'terms'=>$category_ids )));
				
		query_posts($args);
		if( have_posts() ): while( have_posts() ): the_post();
			$no = $wp_query->current_post;
			if($no == 0) { $first_cls = 'first'; } else { $first_cls = ''; }
			?>
			
			<div class="column dt-sc-one-third <?php echo $first_cls; ?>">
				<article id="post-<?php echo get_the_ID(); ?>" class="<?php echo implode(" ", get_post_class("dt-sc-custom-course-type", get_the_ID())); ?>">
				
					<div class="dt-sc-course-thumb">
						<a href="<?php echo the_permalink(); ?>" >
							<?php
							if(has_post_thumbnail()) {
								$image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full');
								?>
								<img src="<?php echo $image_url[0]; ?>" alt="<?php echo get_the_title(); ?>" />
								<?php 
							} else {
								?>
								<img src="http<?php echo dttheme_ssl(); ?>://placehold.it/1170x822&text=Image" alt="<?php echo get_the_title(); ?>" />
								<?php 
							}
							?>
						 </a>
						<div class="dt-sc-course-overlay">
							<a title="<?php echo get_the_title(); ?>" href="<?php echo the_permalink(); ?>" class="dt-sc-button small white"> <?php echo __('View Course', 'dt_themes'); ?> </a>
						</div>
					</div>
					
					<div class="dt-sc-course-details">	
					
						<?php
						if($payment_method == 'woocommerce') {
							echo dttheme_get_course_details_linked_with_products(get_the_ID(), '');
						} else {
							echo dttheme_get_course_details_linked_with_s2member(get_the_ID(), '');
						}
						?>
					
						<h5><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h5>
						
						<div class="dt-sc-course-meta">
							<p> <?php the_terms(get_the_ID(), 'course_category', ' ', ', ', ' '); ?> </p>
							<p> <?php echo dttheme_get_lessons_count(get_the_ID()).'&nbsp;'.__('Lessons', 'dt_themes'); ?> </p>
						</div>
						
						<div class="dt-sc-course-data">
							<div class="dt-sc-course-duration">
								<i class="fa fa-clock-o"> </i>
								<span> <?php echo dttheme_get_lessons_durations(get_the_ID(), 'style2'); ?> </span>
							</div>
							<?php
							if(function_exists('the_ratings') && !dttheme_option('general', 'disable-ratings-courses')) { 
								echo do_shortcode('[ratings id="'.get_the_ID().'"]');
							}
							?>
						</div>
					
					</div>
				
				</article>
			</div>
		
			<?php
		endwhile; endif;
		wp_reset_query();
		?>      
		</div> 
	
		<?php 
	endif; 
	
	?>
    
    <?php
    edit_post_link(__('Edit', 'dt_themes'), '<span class="edit-link">', '</span>' );
    ?>
            
</section>
<?php
if ( $show_sidebar ):
    if ( $show_right_sidebar ): ?>
        <!-- Secondary Right -->
        <section id="secondary-right" class="secondary-sidebar <?php echo $sidebar_class;?>">
			<?php 
			if(dttheme_option('dt_course','enable-course-widget-right') == 'true') {
				dttheme_get_single_course_page_widget_rightside(get_the_ID());
			}
			if(dttheme_option('dt_course','enable-all-course-widgets') == 'true' && $page_layout == 'page-with-sidebar with-right-sidebar') {
				dttheme_get_single_course_page_widget_leftside(get_the_ID());
			}
			?>
			<?php get_sidebar( 'right' );?>
        </section>
		<?php
    endif;
endif;
?>
<?php get_footer(); ?>