<?php
global $product_container_class;

#WooCommerce Theme Support
add_theme_support( 'woocommerce' );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

#Disable WooCommerce Styles
if ( version_compare( get_option('woocommerce_version'), "2.1" ) >= 0 ) {
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );
} else {
	define( 'WOOCOMMERCE_USE_CSS', false );
}

#For Woocommerce shortcode
	add_action( 'wp', 'init' );
	function init() {
		if( is_shop() || is_product_category() || is_product_tag() ) {
		} else{
			global $post,$dt_page_layout;
			if( !is_null($post)) {
				$id = $post->ID;
				$tpl_default_settings = get_post_meta( $id ,'_tpl_default_settings',TRUE);
				$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();
				$dt_page_layout  = array_key_exists("layout",$tpl_default_settings) ? $tpl_default_settings['layout'] : "content-full-width";
				$dt_page_layout = ( $dt_page_layout === "content-full-width" ) ? "" : "-with-sidebar";
			}
		}
	}

//register my own styles, remove woo theme style sheet
	if(!is_admin()){
		add_action('init', 'dt_woocommerce_register_assets');
	}

	function dt_woocommerce_register_assets() {
		wp_enqueue_style( 'dt-woocommerce-css', IAMD_FW_URL.'woocommerce/style.css');
	}

#To add extra class form product images
	if(!is_admin()) {
		add_filter( 'post_class', 'dt_product_has_gallery' );
		function dt_product_has_gallery( $classes ) {
			global $product;
		
			$post_type = get_post_type( get_the_ID() );
			if ( $post_type == 'product' ) {
				$attachment_ids = $product->get_gallery_attachment_ids();
				if ( $attachment_ids ) {
					$classes[] = 'pif-has-gallery';
				}
			}
			return $classes;
		}
	}
#End of Adding extra class to product

/*No of products per row*/
	add_filter( 'loop_shop_columns', 'dt_woocommerce_loop_columns' );	
	if (!function_exists('dt_woocommerce_loop_columns')) {
		function dt_woocommerce_loop_columns() {
			$shop_layout = dttheme_option('woo',"shop-page-product-layout");
			$columns = "";
			switch($shop_layout) {
				case "one-half-column":		$columns = 2;	break;
				case "one-third-column":	$columns = 3;	break;
				case "one-fourth-column":	$columns = 4;	break;				
				default:					$columns = 4;	break;
			}
			return $columns;
		}
	}
/*End of No of products per row*/

// No of products per page
	add_filter( 'loop_shop_per_page', 'dt_woocommerce_product_count' );
	if (!function_exists('dt_woocommerce_product_count')) {
		function dt_woocommerce_product_count() {
			$shop_product_per_page = dttheme_wp_kses(trim(stripslashes(dttheme_option('woo','shop-product-per-page'))));
			$shop_product_per_page = !empty( $shop_product_per_page)  ? $shop_product_per_page : 10;
			return $shop_product_per_page;
		}
	}
// End of No of products per page

//Remove Shop Page Title
	add_action( 'woocommerce_show_page_title', 'dt_woocommerce_show_page_title', 10);
	if( !function_exists('dt_woocommerce_show_page_title') ) {
    	   function dt_woocommerce_show_page_title() {
        	       return false;
       	}
	}
//End of Remove Shop Page Title


#Adjust markup on all WooCommerce pages
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
	remove_action( 'woocommerce_pagination', 'woocommerce_catalog_ordering', 20 );
	remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 );
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 ); #remove result count above products
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 ); #remove woo commerce ordering drop down
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 ); #remove rating
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 ); //remove woo pagination

	add_action( 'woocommerce_before_main_content', 'dt_woocommerce_before_main_content', 10);
	if( !function_exists('dt_woocommerce_before_main_content') ) {
		function dt_woocommerce_before_main_content() {

			global $product_container_class;

			$product_layout = dttheme_option('woo',"shop-page-product-layout");
			$product_layout = !empty( $product_layout ) ? $product_layout : "one-half-column";

			switch( $product_layout ){
				case "one-half-column":		$product_layout = 2;	break;
				case "one-third-column":	$product_layout = 3;	break;
				case "one-fourth-column":	$product_layout = 4;	break;
				default:					$product_layout = 3;	break;
			}


			$show_sidebar = $show_left_sidebar = $show_right_sidebar =  false;
			$sidebar_class = "";

			#For Shop Page
			if( is_shop() ){
				$tpl_default_settings = get_post_meta( get_option('woocommerce_shop_page_id') ,'_tpl_default_settings',TRUE);
				$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();

				$page_layout  = array_key_exists("layout",$tpl_default_settings) ? $tpl_default_settings['layout'] : "content-full-width";
			}
			#For Product Page 
			elseif( is_product()) { 
				$page_layout = dttheme_option('woo',"product-layout");
				$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";				
			}
			#For Product Category
			elseif( is_product_category() ) {
				$page_layout = dttheme_option('woo',"product-category-layout");
				$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";			
			}
			#For Product Tag
			elseif( is_product_tag() ) {
				$page_layout = dttheme_option('woo',"product-tag-layout");
				$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";
			}	
			
			if($GLOBALS['force_enable'] == true) {
				$page_layout = dttheme_option('general', 'global-page-layout');
				$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";
			}
	
			#Define Product Layout 
			switch( $product_layout ):
				case '2':	$product_container_class = "product-two-column";	break;
				case '3':	$product_container_class = "product-three-column";	break;
				case '4':	$product_container_class = "product-four-column";	break;
			endswitch;

			#Define Page Layout	
			switch ( $page_layout ) {
				case 'with-left-sidebar':
					$product_container_class = $product_container_class." ".$product_container_class."-with-sidebar";
					$page_layout = "page-with-sidebar with-left-sidebar";
					$show_sidebar = $show_left_sidebar = true;
					$sidebar_class = "secondary-has-left-sidebar";
				break;

				case 'with-right-sidebar':
					$product_container_class = $product_container_class." ".$product_container_class."-with-sidebar";
					$page_layout = "page-with-sidebar with-right-sidebar";
					$show_sidebar = $show_right_sidebar	= true;
					$sidebar_class = "secondary-has-right-sidebar";
				break;

				case 'both-sidebar':
					$product_container_class = $product_container_class." ".$product_container_class."-with-sidebar";
					$page_layout = "page-with-sidebar page-with-both-sidebar";
					$show_sidebar = $show_right_sidebar	= $show_left_sidebar = true;
					$sidebar_class = "secondary-has-both-sidebar";
				break;

				case 'content-full-width':
				default:
					$page_layout = "content-full-width";
					$product_container_class = $product_container_class;
				break;
			}

			if ( $show_sidebar ):
				if ( $show_left_sidebar ):
					echo "<section id='secondary-left' class='secondary-sidebar {$sidebar_class}'>";
					get_sidebar( 'left' );
					echo '</section>';
				endif;
			endif;

			echo '<!-- ** Primary Section ** -->';
			echo "<section id='primary' class='{$page_layout}'>";
		}
	}

	add_action( 'woocommerce_after_main_content', 'dt_woocommerce_after_main_content', 20);
	if( !function_exists('dt_woocommerce_after_main_content') ) {
		function dt_woocommerce_after_main_content() {

			echo "</section><!-- ** Primary Section End ** -->";

			$show_sidebar = $show_left_sidebar = $show_right_sidebar =  false;
			$sidebar_class = "";

			#For Shop Page
			if( is_shop() ){
				$tpl_default_settings = get_post_meta( get_option('woocommerce_shop_page_id') ,'_tpl_default_settings',TRUE);
				$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();

				$page_layout  = array_key_exists("layout",$tpl_default_settings) ? $tpl_default_settings['layout'] : "content-full-width";
			}
			#For Product Page 
			elseif( is_product()) { 
				$page_layout = dttheme_option('woo',"product-layout");
				$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";				
			}
			#For Product Category
			elseif( is_product_category() ) {
				$page_layout = dttheme_option('woo',"product-category-layout");
				$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";			
			}
			#For Product Tag
			elseif( is_product_tag() ) {
				$page_layout = dttheme_option('woo',"product-tag-layout");
				$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";
			}			

			if($GLOBALS['force_enable'] == true) {
				$page_layout = dttheme_option('general', 'global-page-layout');
				$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";
			}
	
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
				if ( $show_right_sidebar ):
					echo "<section id='secondary-right' class='secondary-sidebar {$sidebar_class}'>";
					get_sidebar( 'right' );
					echo '</section>';
				endif;
			endif;

		}
	}

#Product Loop
# wrap products on overview pages into an extra div for improved styling options. adds "product_on_sale" class if product is on sale

	# 1. Adding Extra Div
	add_action( 'woocommerce_before_shop_loop_item', 'dt_woocommerce_shop_overview_extra_div', 5);
	if( !function_exists('dt_woocommerce_shop_overview_extra_div') ){
		function dt_woocommerce_shop_overview_extra_div() {
			global $product, $woocommerce_loop, $dt_page_layout, $product_container_class;

			if( is_shop() || is_product() || is_product_category() || is_product_tag() ) {
				$product_container_class = $product_container_class;
			} else {
				$column = $woocommerce_loop['columns'];	
				switch($column) {
					case '2':	$product_container_class = "product-two-column";	break;
					case '2-with-nospace':	$product_container_class = "product-two-column no-space";	break;
					case '3':	$product_container_class = "product-three-column";	break;
					case '3-with-nospace':	$product_container_class = "product-three-column no-space";	break;
					case '4':	$product_container_class = "product-four-column";	break;
					case '4-with-nospace': $product_container_class = "product-four-column no-space";	break;
					case '5':	$product_container_class = "product-five-column";	break;
					case '5-with-nospace': $product_container_class = "product-five-column no-space";	break;
					case '6':	$product_container_class = "product-six-column";	break;
					case '6-with-nospace': $product_container_class = "product-six-column no-space";	break;
					default:	$product_container_class = "product-two-column";	break;
				}
		
				$product_container_class .=  $dt_page_layout;
			}
	
			$class = "";
			if( $product->is_featured() )
				$class .= " featured-product ";
		
			if( $product->is_on_sale() )
				$class .= " on-sale-product ";

			if( $product->is_in_stock() )
				$class .= " in-stock-product ";
			else	
				$class .= " out-of-stock-product ";
	
			$out  = '<!-- Prodcut Wrapper -->';
			$out .= "<div class='product-wrapper {$class} {$product_container_class}'> <div class='product-container'>";
			echo $out;
		}
	}

	# 2. Thumbnail 
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
	add_action( 'woocommerce_before_shop_loop_item_title', 'dt_woocommerce_template_loop_product_thumbnail', 10);
	if( !function_exists('dt_woocommerce_template_loop_product_thumbnail')){
		function dt_woocommerce_template_loop_product_thumbnail() {
			global $post,$product,$woocommerce;

			$out = "";
			$id = get_the_ID();
			$image =  get_the_post_thumbnail( $id, 'shop_single' );
			$image = !empty( $image ) ? $image : '<img  width="100%" height="100%" src="http'.dttheme_ssl().'://placehold.it/500x500" alt="" />';

			$out .= '<!-- Product Thumnail -->';
			$out .= "<div class='product-thumb'>";
			$out .= $image;

			$attachment_ids = $product->get_gallery_attachment_ids();
			if ( $attachment_ids ) {
				$secondary_image_id = $attachment_ids['0'];
				$out .= wp_get_attachment_image( $secondary_image_id, 'shop_single', '', $attr = array( 'class' => 'secondary-image attachment-shop-catalog' ) );
			}

			if ($product->is_on_sale() and $product->is_in_stock() ) :
				$out .= apply_filters('woocommerce_sale_flash', '<span class="onsale"><span>'.__( 'Sale!', 'dt_themes' ).'</span></span>', $post, $product);
			elseif(!$product->is_in_stock()):
				$out .= apply_filters( 'woocommerce_sale_flash', '<span class="out-of-stock"><span>'.__( 'Out of Stock', 'dt_themes' ).'</span></span>', $post, $product );
			endif;

			if( $product->is_featured() )
				$out .=  apply_filters( 'woocommerce_sale_flash', '<span class="featured"><span>'.__( 'Featured', 'dt_themes' ).'</span></span>', $post, $product );

			$out .= "</div><!-- Product Thumbnail -->";
			echo $out;
		}
	}

	add_action( 'woocommerce_before_shop_loop_item_title', 'dt_woocommerce_before_shop_loop_item_title', 10);
	if( !function_exists('dt_woocommerce_before_shop_loop_item_title') ) {
		function dt_woocommerce_before_shop_loop_item_title() {
			$out = "";
			$out .= "<div class='product-title'>";
			echo $out;
		}
	}

	add_action( 'woocommerce_after_shop_loop_item_title', 'dt_woocommerce_after_shop_loop_item_title', 10);
	if( !function_exists('dt_woocommerce_after_shop_loop_item_title') ) {
		function dt_woocommerce_after_shop_loop_item_title() {
			$out = "";
			$out .= "</div>";
			echo $out;
		}
	}

	add_action( 'woocommerce_after_shop_loop_item', 'dt_woocommerce_shop_overview_extra_div_close', 1000);
	if( !function_exists('dt_woocommerce_shop_overview_extra_div_close')) {
		function dt_woocommerce_shop_overview_extra_div_close() {
			global $product;
			$link = apply_filters( 'out_of_stock_add_to_cart_url', get_permalink( $product->id ) );

			$out = "";
			ob_start();
			woocommerce_template_loop_price();

			$price = ob_get_clean();

			ob_start();
			woocommerce_template_loop_add_to_cart();

			$add_to_cart = ob_get_clean();

			if( !empty($add_to_cart) ) {
				$add_to_cart = str_replace(' class="',' class="dt-sc-button ',$add_to_cart);
			}

			$out .= '<!-- Product Details -->';
			$out .= "<div class='product-details'>";
			$out .= 	$price;
			$out .= 	$add_to_cart;
			$out .= '</div><!-- Product Details -->';

			$out .= '<div class="product-details-hover">';
			$out .= "<h3><a href='{$link}'>".$product->get_title().'</a></h3>';
			$rating = $product->get_rating_html(); //get rating
			$rating = !empty( $rating ) ? $rating : "";
			$out .= $rating;
			$out .= $price;

			$out .= $add_to_cart;
			if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) )
			$out .= do_shortcode('[yith_wcwl_add_to_wishlist]');
			$out .= '</div><!-- Product Details Hover -->';
			$out .= "</div> </div> <!-- Product Wrapper End-->";
			echo $out;
		}
	}

#To Pagination
	add_action( 'woocommerce_after_shop_loop', 'dt_woocommerce_after_shop_loop', 10);
	if( !function_exists('dt_woocommerce_after_shop_loop') ) {
		function dt_woocommerce_after_shop_loop() { ?>
			<div class="pagination">
    			<div class="prev-post"><?php previous_posts_link('<span class="fa fa-angle-double-left"></span> Prev');?></div>
        		<?php echo dttheme_pagination(); ?>
        		<div class="next-post"><?php next_posts_link('Next <span class="fa fa-angle-double-right"></span>');?></div>
        	</div>
    <?php
		}
	}

#Single Product
	#Showing Related Products
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products',20);
	remove_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products',10);

	add_action( 'woocommerce_after_single_product_summary', 'dt_woocommerce_output_related_products', 20);
	if( !function_exists('dt_woocommerce_output_related_products') ){
		function dt_woocommerce_output_related_products() {
			$page_layout = dttheme_option('woo',"product-layout");
			$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";

			$related_products = ( $page_layout === "content-full-width" ) ? 4 : 2;

			$output = "";
			ob_start();
			woocommerce_related_products(array('posts_per_page' => $related_products, 'columns' => $related_products)); // X products, X columns
			$content = ob_get_clean();
			if($content):
				$content =  str_replace('<h2>','<h2 class="border-title">', $content);
			    $content =  str_replace('</h2>','<span></span></h2>', $content);
			    $output .= "<div class='related-products-container'>{$content}</div>";
			endif;
			echo $output;
		}
	}

#Showing Upsell Products( You may also like)
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display',10);

	add_action( 'woocommerce_after_single_product_summary', 'dt_woocommerce_output_upsells', 21); // needs to be called after the "related product" function to inherit columns and product count
	if( !function_exists('dt_woocommerce_output_upsells') ){
		function dt_woocommerce_output_upsells() {
			$page_layout = dttheme_option('woo',"product-layout");
			$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";
	
			$upsell_products = ( $page_layout === "content-full-width" ) ? 4 : 2;
	
			$output = "";
			ob_start();
			woocommerce_upsell_display($upsell_products,$upsell_products); // X products, X columns
			$content = ob_get_clean();
			if($content):
				$content =  str_replace('<h2>','<div class="border-title"><h2>', $content);
        		$content =  str_replace('</h2>','<span></span></h2></div>', $content);
				$output .= "<div class='upsell-products-container'>{$content}</div>";
			endif;
			echo $output;
		}
	}

	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
	add_action('woocommerce_before_single_product_summary','dt_woocommerce_show_product_sale_flash',10);
	if( !function_exists('dt_woocommerce_show_product_sale_flash') ){
		function dt_woocommerce_show_product_sale_flash() {
			global $product;
			$out = "";
			if( $product->is_on_sale() and $product->is_in_stock() )
				$out .= '<span class="onsale">'.__('Sale!','dt_themes').'</span>';
			elseif(!$product->is_in_stock())
				$out .= '<span class="out-of-stock">'.__('Out of Stock','dt_themes').'</span>';

			if( $product->is_featured() )
				$out .= '<span class="featured-product">'.__('Featured','dt_themes').'</span>';
			echo $out;
		}
	}
	
//	WooCommerce - Ensure cart contents updated when products are added to the cart via AJAX
global $woocommerce;
if( version_compare( $woocommerce->version, '2.3', '<' ) ){
	add_filter('add_to_cart_fragments', 'dttheme_header_add_to_cart_fragment'); // WooCommerce 2.2 -
} else {
	add_filter('woocommerce_add_to_cart_fragments', 'dttheme_header_add_to_cart_fragment'); // WooCommerce 2.3 +
}

function dttheme_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	
	ob_start();
	
	$cart_url = $woocommerce->cart->get_cart_url();
	$woo_cart_list = WC()->cart->get_cart();
	$out = '<li class="dt-sc-cart">
				<a href="'.$cart_url.'"><i class="fa fa-shopping-cart"></i><span class="cart-count">'.count($woo_cart_list).'</span></a>
			</li>';
	
	echo dttheme_wp_kses($out);
	$fragments['li.dt-sc-cart'] = ob_get_clean();
	
	return $fragments;
}
	
add_action('woocommerce_before_cart','dt_woocommerce_before_cart',10);
function dt_woocommerce_before_cart() {
	echo '<p class="return-to-shop"><a class="button wc-backward" href="'.esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ).'">'.esc_html__( 'Return To Shop', 'woocommerce' ).'</a></p><div class="dt-sc-hr-invisible-small"></div>';
}

// Customize Cross Sell Products In Product Single Page
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_collaterals', 'dttheme_shop_cross_sell_display', 30 );
if( !function_exists('dttheme_shop_cross_sell_display') ){
	function dttheme_shop_cross_sell_display() {
		
		$product_layout = dttheme_option('woo',"shop-page-product-layout");
		$product_layout = !empty($product_layout) ? $product_layout : 'one-third-column';

		if($product_layout == 'one-half-column') $related_column = 2;
		elseif($product_layout == 'one-third-column') $related_column = 3;
		elseif($product_layout == 'one-fourth-column') $related_column = 4;
		else $related_column = 3;
		
		woocommerce_cross_sell_display( $posts_per_page = $related_column, $columns = $related_column, $orderby = 'rand' );
							
	}
}

////


// Customize Tabs In Product Single Page

if(function_exists('dttheme_yith_subscription_plugin_active') && dttheme_yith_subscription_plugin_active()) {
	
	add_filter( 'woocommerce_product_tabs', 'dttheme_shop_product_tabs' );
	if( !function_exists('dttheme_shop_product_tabs') ) {
		function dttheme_shop_product_tabs($tabs = array()) {
			global $product, $post;
			
			$is_subscription_product = get_post_meta( $product->id, '_ywsbs_subscription', true );
			
			// Courses available in this subscription package
			if($is_subscription_product == 'yes') {
				
				if ( $post->post_content ) {
					$tabs['subscription_courses'] = array(
						'title'    => esc_html__( 'Subscription Products', 'dt_themes' ),
						'priority' => 1,
						'callback' => 'dttheme_shop_subscription_courses_tab'
					);
				}
			
			}
				
			return $tabs;
		}
	}
	
	function dttheme_shop_subscription_courses_tab() {
		
		global $product, $post;
					
		$class_args = array('posts_per_page' => -1, 'post_type' => 'dt_classes', 'meta_key' => 'dt-class-subscription-product-id', 'orderby' => 'title', 'order' => 'DESC');
		$classes = get_posts( $class_args );
				
		if ( count( $classes ) > 0 ) {
			
			echo '<h4>'.esc_html__('Classes', 'dt_themes').'</h4>';
			
			echo '<table cellspacing="10" cellpadding="0" border="0" style="width:100%;">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col" colspan="2">'.esc_html__('Class', 'dt_themes').'</th>
							<th scope="col">'.esc_html__('Price', 'dt_themes').'</th>
							<th scope="col">'.esc_html__('Option', 'dt_themes').'</th>
						</tr>
					</thead>
					<tbody>';
					
					$i = 1;
					foreach ($classes as $class){
						
						$class_id = $class->ID;
						$class_title = $class->post_title;
						
						$product_ids = dttheme_get_class_subscription_product_ids( $class_id );
						
						if(in_array($product->id, $product_ids)) {
							
							$price_html = '';
							
							$dt_class_product_id = dttheme_get_class_product_id($class_id);
							if($dt_class_product_id != '') {
								$product_inner = dttheme_get_product_object($dt_class_product_id);
								$woo_price = $product_inner->get_price_html();
								if($woo_price != '') {
									$price_html = $woo_price;	
								}
							}
								
						   echo '<tr>
									<td>'.$i.'</td>
									<td>'.get_the_post_thumbnail($class_id, array(42,42)).'</td>
									<td><a href="'.get_permalink($class_id).'">'.$class_title.'</a></td>
									<td>'.$price_html.'</td>
									<td><a class="dt-sc-button small filled" target="_blank" href="'.get_permalink($class_id).'">'.esc_html__('View Class', 'dt_themes').'</a></td>
								</tr>';
							
							$i++;
								
						}
							
					}
					
					if($i == 1) {
						
					   echo '<tr>
								<td colspan="5">'.esc_html__('No Records Found!', 'dt_themes').'</td>
							</tr>';
						
					}
						
			  echo '</tbody>
				</table>';
			
		}
					
		$course_args = array('posts_per_page' => -1, 'post_type' => 'dt_courses', 'meta_key' => 'dt-course-subscription-product-id', 'orderby' => 'title', 'order' => 'DESC');
		$courses = get_posts( $course_args );
				
		if ( count( $courses ) > 0 ) {
			
			echo '<div class="dt-sc-clear"></div><div class="dt-sc-hr-invisible"></div>';
			echo '<h4>'.esc_html__('Courses', 'dt_themes').'</h4>';
			
			echo '<table cellspacing="10" cellpadding="0" border="0" style="width:100%;">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col" colspan="2">'.esc_html__('Course', 'dt_themes').'</th>
							<th scope="col">'.esc_html__('Price', 'dt_themes').'</th>
							<th scope="col">'.esc_html__('Option', 'dt_themes').'</th>
						</tr>
					</thead>
					<tbody>';
					
					$i = 1;
					foreach ($courses as $course){
						$course_id = $course->ID;
						$course_title = $course->post_title;
						
						$product_ids = dttheme_get_course_subscription_product_ids( $course_id );
						
						if(in_array($product->id, $product_ids)) {
							
							$price_html = '';
							
							$dt_course_product_id = dttheme_get_course_product_id($course_id);
							if($dt_course_product_id != '') {
								$product_inner = dttheme_get_product_object($dt_course_product_id);
								$woo_price = $product_inner->get_price_html();
								if($woo_price != '') {
									$price_html = $woo_price;	
								}
							}
								
						   echo '<tr>
									<td>'.$i.'</td>
									<td>'.get_the_post_thumbnail($course_id, array(42,42)).'</td>
									<td><a href="'.get_permalink($course_id).'">'.$course_title.'</a></td>
									<td>'.$price_html.'</td>
									<td><a class="dt-sc-button small filled" target="_blank" href="'.get_permalink($course_id).'">'.esc_html__('View Course', 'dt_themes').'</a></td>
								</tr>';
							
							$i++;
								
						}
							
					}
					
					if($i == 1) {
						
					   echo '<tr>
								<td colspan="5">'.esc_html__('No Records Found!', 'dt_themes').'</td>
							</tr>';
						
					}
						
			  echo '</tbody>
				</table>';
			
		}
		
	}

}
	
?>