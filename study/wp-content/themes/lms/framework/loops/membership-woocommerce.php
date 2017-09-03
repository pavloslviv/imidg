<?php

echo '<h3 class="border-title">'.esc_html__('Available Subscriptions', 'dt_themes').'<span></span></h3>';

$args = array(
	'post_type'				=> 'product',
	'post_status'			=> 'publish',
	'posts_per_page' 		=> -1,
	'meta_key' 				=> '_ywsbs_subscription', 
	'suppress_filters' 		=> 0
);


$product_layout = dttheme_option('woo',"shop-page-product-layout");
$product_layout = !empty( $product_layout ) ? $product_layout : "one-half-column";

switch( $product_layout ){
	case "one-half-column":		$product_layout = 'product-two-column';	break;
	case "one-third-column":	$product_layout = 'product-three-column';	break;
	case "one-fourth-column":	$product_layout = 'product-four-column';	break;
	default:					$product_layout = 'product-three-column';	break;
}


$products = new WP_Query($args);
if($products->have_posts()):

	$i = 1;
	echo '<ul class="products subscription-products">';
	
	while($products->have_posts()): $products->the_post();
		global $product, $post;
		
		$product_id = $product->id;
		$permalink = get_permalink($product_id);
		$title = get_the_title($product_id);
		
		$position_class = '';
		$post_class = get_post_class();
		$post_class = implode(' ', $post_class);
		
		$featureimg_id = get_post_thumbnail_id($product_id);
		$featureimg_attrs = wp_get_attachment_image_src($featureimg_id, 'full');
		
		$attachment_ids = $product->get_gallery_attachment_ids();
		
		if( ! $product->is_in_stock() || in_array( $product->product_type, array('external','grouped','variable') ) ){
			$add_to_cart = '<a href="'. get_permalink() .'" rel="nofollow" data-product_id="'.$product_id.'" class="dt-sc-button button product_type_'.$product->product_type.'"><span class="fa fa-sliders"></span> '.esc_html__('View Details', 'dt_plugins').'</a>';
		} else {
			$add_to_cart = '<a href="'. apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) ) .'" rel="nofollow" data-product_id="'.$product_id.'" class="dt-sc-button button add_to_cart_button ajax_add_to_cart product_type_'.$product->product_type.'"><span class="fa fa-shopping-cart"></span> '.esc_html__('Add to Cart', 'dt_plugins').'</a>';
		}
		
		if (shortcode_exists('yith_wcwl_add_to_wishlist')) { $wishlist = do_shortcode('[yith_wcwl_add_to_wishlist /]'); }
		else { $wishlist = ''; }
		
		$price_html = $product->get_price_html();
		
		echo '<li class="'.$post_class.' '.$position_class.'"><div class="product-wrapper '.$product_layout.'">';
		
			echo '<div class="product-container">
						<a href="'.$permalink.'">';
					echo '<div class="product-thumb">
								<img src="'.$featureimg_attrs[0].'" alt="'.$title.'" title="'.$title.'" class="primary-image" />';
								if(isset($attachment_ids[0])) {
									$attachmentimg_attrs = wp_get_attachment_image_src($attachment_ids[0], 'full');
									echo '<img src="'.$attachmentimg_attrs[0].'" alt="'.$title.'" title="'.$title.'" class="secondary-image" />';
								}
								if($product->is_featured()) { 
									echo apply_filters( 'woocommerce_sale_flash', '<span class="featured"><span>'.esc_html__( 'Featured', 'dt_plugins' ).'</span></span>', $post, $product ); 
								}
								if($product->is_on_sale() and $product->is_in_stock()) { 
									echo apply_filters('woocommerce_sale_flash', '<span class="onsale"><span>'.esc_html__( 'Sale!', 'dt_plugins' ).'</span></span>', $post, $product); 
								} elseif(!$product->is_in_stock()) { 
									echo apply_filters( 'woocommerce_sale_flash', '<span class="out-of-stock"><span>'.esc_html__( 'Out of Stock', 'dt_plugins' ).'</span></span>', $post, $product ); 
								}
					echo '</div>';
						echo '<div class="product-title"> 
								<h3>'.$title.'</h3>
							</div>
						</a>
						<div class="product-details"> 
							<span class="price">'.$price_html.'</span>
							'.$add_to_cart.'
						</div>
						<div class="product-details-hover"> 
							<h3> <a href="'.$permalink.'">'.$title.'</a> </h3>
							'.$product->get_rating_html().'
							<span class="price">'.$price_html.'</span>
							'.$add_to_cart.$wishlist.'
							<div class="clear"></div>
						</div>
					</div>';
			
		
		 echo '</div></li>';
		
		$i++;
		
	endwhile;
	
	echo '</ul>';
	
	endif;
					

?>