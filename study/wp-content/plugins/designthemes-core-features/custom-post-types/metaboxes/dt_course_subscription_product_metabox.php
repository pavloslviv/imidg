<?php
global $post;

$product_args = array(	'post_type' 		=> array( 'product' ),
						'posts_per_page' 	=> -1,
						'orderby'         	=> 'title',
						'order'           	=> 'ASC',
						'meta_key' 			=> '_ywsbs_subscription', 
						'post_status'		=> array( 'publish', 'private', 'draft' ),
						'suppress_filters' 	=> 0
					);
$products_array = get_posts( $product_args );

$dt_subscription_product_id = get_post_meta( $post->ID, 'dt-course-subscription-product-id', true );

?>
<p><?php _e( 'Choose WooCommerce Subscription Product here to make this course available in subscription package.', 'dt_themes' ); ?></p>
<p><?php _e("Leave this field empty if you are going to add this course to class.",'dt_themes');?></p>
<select name="dt-course-subscription-product-id[]" id="dt-course-subscription-product-id" class="dt-chosen-select" multiple>
    <option value=""><?php _e( 'None', 'dt_themes' ); ?></option>
    <?php
    foreach ( $products_array as $product ) {
		if ( YITH_WC_Subscription()->is_subscription( $product->ID ) ) {
			$sel_str = '';
			if(!empty($dt_subscription_product_id) && in_array($product->ID, $dt_subscription_product_id)) {
				$sel_str = 'selected="selected"'; 
			}
			?>
			<option value="<?php echo $product->ID; ?>" <?php echo $sel_str; ?>><?php _e( $product->post_title, 'dt_themes' ); ?></option>
			<?php
		}
    }
    ?>
</select>