<?php get_header();

	$page_layout 	= dttheme_option('dt_course','archives-layout');
	
	if($GLOBALS['force_enable'] == true) {
		$page_layout = dttheme_option('general', 'global-page-layout');
	} else {
		$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";
	}
		
	$courses_layout_type = (dttheme_option('dt_course','archives-layout-type') != '') ? dttheme_option('dt_course','archives-layout-type') : '';
  	
	$show_sidebar = $show_left_sidebar = $show_right_sidebar =  false;
	$sidebar_class = "";
	
	$term_id = $wp_query->get_queried_object_id();

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

	$payment_method = dttheme_option('general','payment-method');
	
	if ( $show_sidebar ):
		if ( $show_left_sidebar ): ?>
			<!-- Secondary Left -->
			<section id="secondary-left" class="secondary-sidebar <?php echo $sidebar_class;?>"><?php get_sidebar( 'left' );?></section><?php
		endif;
	endif;?>

    <!-- ** Primary Section ** -->
    <section id="primary" class="<?php echo $page_layout;?>">
    
        <?php
		if(dttheme_option('general', 'disable-theme-default-courses') != 'true') {
			
			if($courses_layout_type == 'type2') {
				?>
                
                <div class="column dt-sc-one-fourth first">
                
                    <div class="courses-sorting">
                    
                        <div class="courses-popular-type">
                            <label> <?php echo __('Filter by :', 'dt_themes'); ?> </label>
                            <ul>
                                <li><input type="radio" name="courses-type" class="courses-type" value="all" data-postid="<?php echo $term_id; ?>" checked="checked" /><?php echo __('All', 'dt_themes'); ?></li>
                                <li><input type="radio" name="courses-type" class="courses-type" value="featured" data-postid="<?php echo $term_id; ?>" /><?php echo __('Featured Courses', 'dt_themes'); ?></li>
                                <?php if(function_exists('the_ratings')) { ?>
                                    <li><input type="radio" name="courses-type" class="courses-type" value="popular" data-postid="<?php echo $term_id; ?>" /><?php echo __('Popular Courses', 'dt_themes'); ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                                                
                        <div class="courses-classwise">
                            <label> <?php echo __('Choose Class :', 'dt_themes'); ?> </label>
                            <ul>
                                <li><input type="checkbox" name="filter-classes" class="filter-classes filter-classes-all" value="all" data-postid="<?php echo $term_id; ?>" checked="checked" /><?php echo __('All', 'dt_themes'); ?></li>
                                <?php 
                                $class_args = array('posts_per_page' => -1, 'post_type' => 'dt_classes', 'orderby' => 'title', 'order' => 'DESC');
								$classes = get_posts( $class_args );
                                if(count($classes) > 0) { 
                                    foreach($classes as $class) {
										$class_id = $class->ID;
										$class_title = $class->post_title;
										$class_content_options = get_post_meta($class_id, 'dt-class-content-options', true);
										if($class_content_options == 'course') {
											?>
											<li><input type="checkbox" name="filter-classes" class="filter-classes" value="<?php echo $class_id; ?>" data-postid="<?php echo $term_id; ?>" /><?php echo $class_title; ?></li>
											<?php 
										}
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                        
                        <div class="courses-price-type">
                            <label> <?php echo __('By Cost :', 'dt_themes'); ?> </label>
                            <ul>
                                <li><input type="radio" name="course-price-sidebar" class="course-price-sidebar course-price-sidebar-all" value="all" data-postid="<?php echo $term_id; ?>" checked="checked" /><?php echo __('All', 'dt_themes'); ?></li>
                                <li><input type="radio" name="course-price-sidebar" class="course-price-sidebar" value="paid" data-postid="<?php echo $term_id; ?>" /><?php echo __('Paid', 'dt_themes'); ?></li>
                                <li><input type="radio" name="course-price-sidebar" class="course-price-sidebar" value="free" data-postid="<?php echo $term_id; ?>" /><?php echo __('Free', 'dt_themes'); ?></li>
                            </ul>
                        </div>
                         
                    </div>     
                
                </div>
                
                <div class="column dt-sc-three-fourth">
                
                    <div class="courses-view-type">
                        <a class="course-layout course-grid-type active" data-postid="<?php echo $term_id; ?>" data-view_type="grid"> <span> </span><?php _e('Grid','dt_themes');?></a>
                        <a class="course-layout course-list-type" data-postid="<?php echo $term_id; ?>" data-view_type="list"> <span> </span><?php _e('List','dt_themes');?></a>
                    </div>
                    
                    <div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
                    <div id="ajax_tpl_course_content"></div>
                    
                    <?php
                    wp_link_pages( array('before' => '<div class="page-link">','after' =>'</div>', 'link_before' => '<span>', 'link_after' => '</span>', 'next_or_number' => 'number', 'pagelink' => '%', 'echo' => 1 ) );
                    edit_post_link( __( ' Edit ','dt_themes' ) );					
                    ?>
                
                </div>

                <?php
			} else {
				?>
			
                <div class="courses-sorting">
                
                    <div class="courses-popular-type">
                        <label> <?php echo __('Filter by :', 'dt_themes'); ?> </label>
                        <select name="courses-type" id="courses-type" data-postid="<?php echo $term_id; ?>">
                            <option value="all"><?php echo __('All', 'dt_themes'); ?></option>
                            <option value="featured"><?php echo __('Featured Courses', 'dt_themes'); ?></option>
                            <?php if(function_exists('the_ratings')) { ?>
                                <option value="popular"><?php echo __('Popular Courses', 'dt_themes'); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="courses-classwise">
                        <label> <?php echo __('Choose Class :', 'dt_themes'); ?> </label>
                        <select name="filter-classes" id="filter-classes" data-postid="<?php echo $term_id; ?>">
                            <option value="all"><?php echo __('All', 'dt_themes'); ?></option>
                            <?php 
                            $class_args = array('posts_per_page' => -1, 'post_type' => 'dt_classes', 'orderby' => 'title', 'order' => 'DESC');
                            $classes = get_posts( $class_args );
                            if(count($classes) > 0) { 
                                foreach($classes as $class) {
                                    $class_id = $class->ID;
                                    $class_title = $class->post_title;
                                    $class_content_options = get_post_meta($class_id, 'dt-class-content-options', true);
                                    if($class_content_options == 'course') {
										?>
										<option value="<?php echo $class_id; ?>"><?php echo $class_title; ?></option>
										<?php 
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="courses-price-type">
                        <a class="course-price course-all-price active" data-postid="<?php echo $term_id; ?>" data-price_type="all"> <span> </span><?php _e('All','dt_themes');?></a>
                        <a class="course-price course-paid-price" data-postid="<?php echo $term_id; ?>" data-price_type="paid"> <span> </span><?php _e('Paid','dt_themes');?></a>
                        <a class="course-price course-free-price" data-postid="<?php echo $term_id; ?>" data-price_type="free"> <span> </span><?php _e('Free','dt_themes');?></a>
                    </div>
                     
                </div>     
        
                <div class="courses-view-type">
                    <a class="course-layout course-grid-type active" data-postid="<?php echo $term_id; ?>" data-view_type="grid"> <span> </span><?php _e('Grid','dt_themes');?></a>
                    <a class="course-layout course-list-type" data-postid="<?php echo $term_id; ?>" data-view_type="list"> <span> </span><?php _e('List','dt_themes');?></a>
                </div>
                
                <div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
                <div id="ajax_tpl_course_content"></div>
                
                <?php
                wp_link_pages( array('before' => '<div class="page-link">','after' =>'</div>', 'link_before' => '<span>', 'link_after' => '</span>', 'next_or_number' => 'number', 'pagelink' => '%', 'echo' => 1 ) );
                edit_post_link( __( ' Edit ','dt_themes' ) );		
							
			}
			?>
        
        <?php
		} else {
			echo '<div class="dt-sc-error-box">'.__('You have disabled theme default courses in Buddha Panel settings. Please enable it.', 'dt_themes').'</div>';
		}
		?>
    
    </section><!-- ** Primary Section End ** -->
		
		
		<?php

	if ( $show_sidebar ):
		if ( $show_right_sidebar ): ?>
			<!-- Secondary Right -->
			<section id="secondary-right" class="secondary-sidebar <?php echo $sidebar_class;?>"><?php get_sidebar( 'right' );?></section><?php
		endif;
	endif;?>
<?php get_footer(); ?>