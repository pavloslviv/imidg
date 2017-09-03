<?php /*Template Name: Membership Template*/?>
<?php 
get_header();

$tpl_default_settings = get_post_meta( $post->ID, '_tpl_default_settings', TRUE );
$tpl_default_settings = is_array( $tpl_default_settings ) ? $tpl_default_settings  : array();

if($GLOBALS['force_enable'] == true)
	$page_layout = $GLOBALS['page_layout'];
else
	$page_layout  = array_key_exists( "layout", $tpl_default_settings ) ? $tpl_default_settings['layout'] : "content-full-width";

$show_sidebar = $show_left_sidebar = $show_right_sidebar =  false;
$sidebar_class = $thumbnail_sidebar = $post_thumbnail = "";

switch ( $page_layout ) {
	case 'with-left-sidebar':
		$page_layout = "page-with-sidebar with-left-sidebar";
		$show_sidebar = $show_left_sidebar = true;
		$sidebar_class = "secondary-has-left-sidebar";
		$thumbnail_sidebar = "-single-sidebar";
	break;

	case 'with-right-sidebar':
		$page_layout = "page-with-sidebar with-right-sidebar";
		$show_sidebar = $show_right_sidebar	= true;
		$sidebar_class = "secondary-has-right-sidebar";
		$thumbnail_sidebar = "-single-sidebar";
	break;

	case 'both-sidebar':
		$page_layout = "page-with-sidebar page-with-both-sidebar";
		$show_sidebar = $show_right_sidebar	= $show_left_sidebar = true;
		$sidebar_class = "secondary-has-both-sidebar";
		$thumbnail_sidebar = "-both-sidebar";
	break;

	case 'content-full-width':
	default:
		$page_layout = "content-full-width";
		$thumbnail_sidebar = "";
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
    if( have_posts() ):
        while( have_posts() ):
            the_post();
            get_template_part( 'framework/loops/content', 'page' );
        endwhile;
    endif;
    
    $payment_method = dttheme_option('general','payment-method');
    
    if($payment_method == 'woocommerce') {
		
		if(dttheme_is_plugin_active('woocommerce/woocommerce.php')) {
			get_template_part( 'framework/loops/membership', 'woocommerce' );
		} else {
			echo '<div class="dt-sc-info-box">'.__('WooCommerce plugin is not active please activate to continue!', 'dt_themes').'</div>';
		}
		
	} else {

		if(dttheme_is_plugin_active('s2member/s2member.php')) {
			get_template_part( 'framework/loops/membership', 's2member' );
		} else {
			echo '<div class="dt-sc-info-box">'.__('s2Member plugin is not active please activate to continue!', 'dt_themes').'</div>';
		}
		
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