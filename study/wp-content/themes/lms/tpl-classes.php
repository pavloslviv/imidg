<?php /*Template Name: Classes Template*/?>
<?php get_header();

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
	endif;?>

	<!-- ** Primary Section ** -->
	<section id="primary" class="<?php echo $page_layout;?>">
    	
        <?php		
		if(dttheme_option('general', 'disable-theme-default-courses') != 'true') {
			
			if( have_posts() ):
				while( have_posts() ):
					the_post();
					the_content();
				endwhile;
			endif;
			
			?>
			
            <div class="dt-sc-classes-filters">
            
                <div class="classes-type">
                    <a class="class-type class-type-all active" data-postid="<?php echo $post->ID; ?>" data-class_type="all"> <span> </span><?php _e('All','dt_themes');?></a>
                    <a class="class-type class-type-onsite" data-postid="<?php echo $post->ID; ?>" data-class_type="onsite"> <span> </span><?php _e('Onsite','dt_themes');?></a>
                    <a class="class-type class-type-online" data-postid="<?php echo $post->ID; ?>" data-class_type="online"> <span> </span><?php _e('Online','dt_themes');?></a>
                </div>
                
                <div class="classes-items-type">
                    <a class="class-item-type class-item-type-all active" data-postid="<?php echo $post->ID; ?>" data-class_item_type="all"> <span> </span><?php _e('All','dt_themes');?></a>
                    <a class="class-item-type class-item-type-popular" data-postid="<?php echo $post->ID; ?>" data-class_item_type="popular"> <span> </span><?php _e('Popular','dt_themes');?></a>
                    <a class="class-item-type class-item-type-featured" data-postid="<?php echo $post->ID; ?>" data-class_item_type="featured"> <span> </span><?php _e('Featured','dt_themes');?></a>
                </div>
            
            </div>
	
			<div class="classes-view-type">
				<a class="class-layout class-grid-type active" data-postid="<?php echo $post->ID; ?>" data-view_type="grid"> <span class="fa fa-th-large"> </span><?php _e('Grid View','dt_themes');?></a>
				<a class="class-layout class-list-type" data-postid="<?php echo $post->ID; ?>" data-view_type="list"> <span class="fa fa-th-list"> </span><?php _e('List View','dt_themes');?></a>
			</div>
			
			<div id="dt-sc-ajax-load-image" style="display:none;"><img src="<?php echo IAMD_BASE_URL."images/loading.gif"; ?>" alt="" /></div>
			<div id="ajax_tpl_class_content"></div>
			
			<?php
			wp_link_pages( array('before' => '<div class="page-link">','after' =>'</div>', 'link_before' => '<span>', 'link_after' => '</span>', 'next_or_number' => 'number', 'pagelink' => '%', 'echo' => 1 ) );
			edit_post_link( __( ' Edit ','dt_themes' ) );					
			?>
        
        <?php
		} else {
			echo '<div class="dt-sc-error-box">'.__('You have disabled theme default courses in Buddha Panel settings. Please enable it.', 'dt_themes').'</div>';
		}
		?>


	</section><!-- ** Primary Section End ** --><?php

	if ( $show_sidebar ):
		if ( $show_right_sidebar ): ?>
			<!-- Secondary Right -->
			<section id="secondary-right" class="secondary-sidebar <?php echo $sidebar_class;?>"><?php get_sidebar( 'right' );?></section><?php
		endif;
	endif;?>
<?php get_footer(); ?>