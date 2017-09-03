<?php

// Yith subscription plugin status
function dttheme_yith_subscription_plugin_active() {

	if(dttheme_is_plugin_active('yith-woocommerce-subscription/init.php') || dttheme_is_plugin_active('yith-woocommerce-subscription-premium/init.php')) {
		return true;
	} else {
		return false;
	}
	
}

// Get course product
function dttheme_get_course_product_id( $course_id ){

	$product_id = get_post_meta( $course_id, 'dt-course-product-id', true );
	return $product_id;

}

// Get course subscription products
function dttheme_get_course_subscription_product_ids( $course_id ){
	
	$product_ids = array();
	
	if(dttheme_yith_subscription_plugin_active()) {
		$product_ids = get_post_meta( $course_id, 'dt-course-subscription-product-id', true );
		$product_ids = ($product_ids == '') ? array() : $product_ids;
	}
	
	return $product_ids;

}

// Get all products course
function dttheme_get_course_all_products( $course_id ){

	$product_ids = array();
	
	$product_ids = dttheme_get_course_subscription_product_ids( $course_id );
	if(empty($product_ids)) {
		$product_ids[] = dttheme_get_course_product_id( $course_id );
	}
	
	return $product_ids;

}


// Get user purchased products
function dttheme_get_user_purchased_product_ids($user_id) {
	
	$user_purchased_products = array();
	
	if($user_id == '') {
		$user_id = get_current_user_id();
	}
	
	$order_args = array(
		'post_type' => 'shop_order',
		'posts_per_page' => -1,
		'post_status' => array( 'wc-processing', 'wc-completed' ),
		'meta_query' => array(
			array(
				'key' => '_customer_user',
				'value' => $user_id
			)
		),
		'fields' => 'ids',
	);
	$orders = get_posts( $order_args );
	
	foreach( $orders as $order_post_id ) {
		
		$order = new WC_Order( $order_post_id );
		$items = $order->get_items();
		
		foreach( $items as $item ) {
			$user_purchased_products[$order->id] = $item['product_id'];		
		}
		
	}
	
	return $user_purchased_products;

}

// Check is user purchased this course
function dttheme_check_if_user_purchased_this_course($course_id) {
	
	$user_purchased_products = dttheme_get_user_purchased_product_ids('');
	$dt_course_product_id = dttheme_get_course_product_id($course_id);
	
	if(in_array($dt_course_product_id, $user_purchased_products)) {
		return true;
	}
	
	return false;
	
}

// Check is user subscribed this course
function dttheme_check_if_user_subscribed_this_course($course_id) {
	
	if(dttheme_yith_subscription_plugin_active()) {
		
		$user_purchased_products = dttheme_get_user_purchased_product_ids('');
		$dt_course_product_ids = dttheme_get_course_subscription_product_ids($course_id);
		
		$subscribed_products = array_intersect($user_purchased_products, $dt_course_product_ids);
			
		if(count($subscribed_products) > 0) {
			
			$subscribed_order_keys = array_keys($subscribed_products);
			$subscribed_order = $subscribed_order_keys[0];
			
			$subscriptions = get_post_meta( $subscribed_order, 'subscriptions', true );
		
			if ( $subscriptions ) {
				foreach ( $subscriptions as $subscription_id ) {
					$subscription = ywsbs_get_subscription( $subscription_id );
				}
			}
			
			$status = $subscription->status;
			$exp_date = $subscription->expired_date;
			$cur_date = current_time('timestamp');
			
			if($status == 'active' && $cur_date <= $exp_date) {
				return true;
			}
			
		}
	
	}
	
	return false;

}


// Get product object
function dttheme_get_product_object ( $wc_product_id = 0 ) {

	$wc_product_object = wc_get_product( $wc_product_id );
	return $wc_product_object;

}

// Check course is in cart
function dttheme_is_course_in_cart( $course_id ){

	$product_id = dttheme_get_course_product_id( $course_id );
	
	if ( $product_id > 0 ) {

		$product = wc_get_product( $product_id );

		foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {

			$cart_product = $values['data'];
			if( $product_id == $cart_product->id ) {

				return true;

			}

		}
		
	}

	return false;

}

function dttheme_check_course_class_purhcase_status_product($course_id) {
		
	$class_ids = dttheme_get_course_classes_lists($course_id);
	
	foreach($class_ids as $class_id) {
		
		if(dttheme_check_if_user_purchased_this_class($class_id) || dttheme_check_if_user_subscribed_this_class($class_id)) {
			return true;
		}
		
	}
	
	return false;
	
}

// Get course details linked with woocommerce products
function dttheme_get_course_details_linked_with_products( $course_id, $page_type ){

	$out = '';
	
	$class_ids = dttheme_get_course_classes_lists($course_id);
		
	if(dttheme_check_course_class_purhcase_status_product($course_id) || dttheme_check_if_user_subscribed_this_course($course_id) || dttheme_check_if_user_purchased_this_course($course_id)) {
		
		$out .= '<div class="dt-sc-purchased-details">';
					$out .= '<span class="dt-sc-purchased"> <span class="fa fa fa-cart-arrow-down"> </span> '.__('Purchased','dt_themes').'</span>';
					$course_status = dt_get_users_course_status($course_id, '');
					if($course_status) {
						$out .= '<div class="dt-sc-course-completed"> <span class="fa fa-check-circle"> </span> '.__('Completed', 'dt_themes').'</div>';
					}
		$out .= '</div>';
		
	} else {
		
		$dt_course_product_id = dttheme_get_course_product_id($course_id);
		
		if(dttheme_is_course_in_cart($course_id)) {
			
			$out .= '<span class="dt-sc-purchased"> '.__('Added to cart already!','dt_themes').'</span>';
			
		} else {
			
			if(!empty($dt_course_product_id)) {
				
				$product = dttheme_get_product_object($dt_course_product_id);
				$woo_price = $product->get_price_html();
				
				if($page_type == 'single') {
					
					if($woo_price != '') {
						$out .= '<a href="'.esc_url($product->add_to_cart_url()).'" target="_self"  class="dt-sc-button small filled"><i class="fa fa-shopping-cart"></i> '.$woo_price.' - '.__('Add to Cart','dt_themes').'</a>';
					} else {
						$login_page_link = dttheme_get_page_permalink_by_its_template('tpl-login.php');
						if(!is_user_logged_in() && $login_page_link != '') {
							$out .= '<span class="dt-sc-purchased"><a href="'.$login_page_link.'" target="_self">'.__('Free','dt_themes').'</a></span>';
						} else {
							$out .= '<span class="dt-sc-purchased">'.__('Free','dt_themes').'</span>';
						}
					}
					
				} else {
					
					if($woo_price != '') {
						$out .= '<span class="dt-sc-course-price"><span class="amount">'.$woo_price.'</span></span>';
					} else {
						$out .= '<span class="dt-sc-course-price"><span class="amount">'.__('Free','dt_themes').'</span></span>';
					}
					
				}
				
			} else {
				
				if($page_type == 'single') {
					$login_page_link = dttheme_get_page_permalink_by_its_template('tpl-login.php');
					if(!is_user_logged_in() && $login_page_link != '') {
						$out .= '<span class="dt-sc-purchased"><a href="'.$login_page_link.'" target="_self">'.__('Free','dt_themes').'</a></span>';
					} else {
						$out .= '<span class="dt-sc-purchased">'.__('Free','dt_themes').'</span>';
					}
				} else {
					$out .= '<span class="dt-sc-course-price"><span class="amount">'.__('Free','dt_themes').'</span></span>';
				}
				
			}
		
		}
	
	}
	
	return $out;

}

// Get list of all products purchased by user
function dttheme_get_user_purchased_products($user_id) {
	
	if($user_id == '') {
		$user_id = get_current_user_id();
	}
	
	$order_args = array(
		'post_type' => 'shop_order',
		'posts_per_page' => -1,
		'post_status' => array( 'wc-processing', 'wc-completed' ),
		'meta_query' => array(
			array(
				'key' => '_customer_user',
				'value' => $user_id
			)
		),
		'fields' => 'ids',
	);
	$orders = get_posts( $order_args );
	
	$user_products = array();
	
	foreach( $orders as $order_post_id ) {

		$order = new WC_Order( $order_post_id );
		$items = $order->get_items();
		
		foreach( $items as $item ) {
			$user_products[] = $item['product_id'];
		}
		
	}
	
	return $user_products;
	
}

// Get list of all courses purchased by user
function dttheme_get_user_purchased_courses($user_id) {
	
	$user_products = dttheme_get_user_purchased_products($user_id);
	$user_courses = array();
		
	$args = array('posts_per_page' => -1, 'post_type' => 'dt_courses');

	$user_courses_list = get_posts( $args );

	foreach($user_courses_list as $user_courses_list_key => $user_courses_list_value) {
		
		$dt_product_id = get_post_meta( $user_courses_list_value->ID, 'dt-course-product-id', true );
		
		if($dt_product_id != '') {
			if(in_array($dt_product_id, $user_products)) {
				$user_courses[] = $user_courses_list_value->ID;
			}
		}
		
		$dt_subscription_product_id = get_post_meta( $user_courses_list_value->ID, 'dt-course-subscription-product-id', true );
		
		if(!empty($dt_subscription_product_id)) {
			$subscribed_products = array_intersect($dt_subscription_product_id, $user_products);
			if(!empty($subscribed_products)) {
				$user_courses[] = $user_courses_list_value->ID;
			}
		}
		
	}
			
	return array_unique($user_courses);
	
}

// Get list of all courses purchased by user withour subscription courses
function dttheme_get_user_purchased_courses_without_subscription_courses($user_id) {
	
	$user_products = dttheme_get_user_purchased_products($user_id);
	$user_courses = array();
		
	$args = array('posts_per_page' => -1, 'post_type' => 'dt_courses');

	$user_courses_list = get_posts( $args );

	foreach($user_courses_list as $user_courses_list_key => $user_courses_list_value) {
		
		$dt_product_id = get_post_meta( $user_courses_list_value->ID, 'dt-course-product-id', true );
		
		if($dt_product_id != '') {
			if(in_array($dt_product_id, $user_products)) {
				$user_courses[] = $user_courses_list_value->ID;
			}
		}
		
	}
			
	return array_unique($user_courses);
	
}

// Get course purchased student list
function dttheme_get_course_purchased_student_list($course_id) {
	
	$product_ids = dttheme_get_course_all_products($course_id);
		
	$order_ids = array();
	
	foreach($product_ids as $product_id) {
		$order_id_new = dttheme_get_product_orders($product_id);
		$order_ids = array_merge($order_id_new, $order_ids);
	}
	
	$users_list = array();
	
	foreach( $order_ids as $order_post_id ) {
		
		if($order_post_id > 0) {
			
			$user_id = get_post_meta($order_post_id, '_customer_user', true);
			if($user_id != '') {
				$users_list[] = $user_id;
			}
		
		}

	}
		
	return array_unique($users_list);
	
}

// Get orders list of product
function dttheme_get_product_orders( $id ) {
	
	global $wpdb;
	
	$order_ids = $wpdb->get_col( $wpdb->prepare( "
		SELECT order_id
		FROM {$wpdb->prefix}woocommerce_order_items
		WHERE order_item_id IN ( SELECT order_item_id FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE meta_key = '_product_id' AND meta_value = %d )
		AND order_item_type = 'line_item'
	 ", $id ) );

	return $order_ids;
	
}

// Check user already purchased course or not
function dttheme_check_user_already_purchased_courses($user_id, $course_id) {
	
	$user_courses = dttheme_get_user_purchased_courses($user_id);
	
	if(in_array($course_id, $user_courses)) {
		return true;
	}
			
	return false;
	
}

// Check user already purchased course or not ( without subscription package )
function dttheme_check_user_already_purchased_courses_without_subscription_courses($user_id, $course_id) {
	
	$user_courses = dttheme_get_user_purchased_courses_without_subscription_courses($user_id);
	
	if(in_array($course_id, $user_courses)) {
		return true;
	}
			
	return false;
	
}

// Assign course to students ( create orders manually )
function dttheme_woo_assign_course_to_students($user_id, $course_id) {
	
	$product_id = dttheme_get_course_product_id($course_id);
	
	if($product_id != '') {
	
		$order_title = sprintf( __( 'Order &ndash; %s', 'dt_themes' ), strftime( _x( '%b %d, %Y @ %I:%M %p', 'Order date parsed by strftime', 'dt_themes' ) ) );
		
		$order_data = array(
			'post_author' => 1,
			'post_status' => "wc-completed",
			'post_title' => $order_title,
			'post_parent' => '',
			'post_type' => "shop_order",
		);
		$order_id = wp_insert_post($order_data);
		
		update_post_meta( $order_id, '_order_key', 'wc_' . apply_filters( 'woocommerce_generate_order_key', uniqid( 'order_' ) ) );
		update_post_meta( $order_id, '_order_currency', get_woocommerce_currency() );
		update_post_meta( $order_id, '_prices_include_tax', get_option( 'woocommerce_prices_include_tax' ) );
		update_post_meta( $order_id, '_customer_user', $user_id );
		update_post_meta( $order_id, '_payment_method_title', esc_html__('Manually assigned by admin', 'dt_themes') );
		
		$product = wc_get_product($product_id);
		$price = $product->price;
		update_post_meta( $order_id, '_order_total', $price );
		
		// Add products
		wc_get_order($order_id)->add_product($product, 1);
		
		// Add order notes
		$comment_post_ID        = $order_id;
		$comment_author_url     = '';
		$comment_content        = esc_html__('Manually assigned by admin', 'dt_themes');
		$comment_agent          = 'WooCommerce';
		$comment_type           = 'order_note';
		$comment_parent         = 0;
		$comment_approved       = 1;
		$commentdata            = apply_filters( 'woocommerce_new_order_note_data', compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_agent', 'comment_type', 'comment_parent', 'comment_approved' ), array( 'order_id' => $order_id, 'is_customer_note' => 0 ) );
		$comment_id = wp_insert_comment( $commentdata );
	
	}
	
}

// Delete user order
function dttheme_woo_delete_user_order($user_id, $course_id) {
	
	$product_id = dttheme_get_course_product_id($course_id);
	
	$user_products = dttheme_get_user_purchased_product_ids($user_id);
	
	if(!empty($user_products)) {
		
		if(in_array($product_id, $user_products)) {
			
			$order_id = array_search($product_id, $user_products);
			
		}
	
	}
	
	if($order_id != '' && $order_id > 0) {
		wp_delete_post($order_id, true);
	}
	
}


// Get class product
function dttheme_get_class_product_id( $class_id ){

	$product_id = get_post_meta( $class_id, 'dt-class-product-id', true );
	return $product_id;

}

// Get class subscription products
function dttheme_get_class_subscription_product_ids( $class_id ){
	
	$product_ids = array();
	
	if(dttheme_yith_subscription_plugin_active()) {
		$product_ids = get_post_meta( $class_id, 'dt-class-subscription-product-id', true );
		$product_ids = ($product_ids == '') ? array() : $product_ids;
	}
	
	return $product_ids;

}

// Check class is in cart
function dttheme_is_class_in_cart( $class_id ){

	$product_id = dttheme_get_class_product_id( $class_id );
	
	if ( $product_id > 0 ) {

		$product = wc_get_product( $product_id );

		foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {

			$cart_product = $values['data'];
			if( $product_id == $cart_product->id ) {

				return true;

			}

		}
		
	}

	return false;

}

// Check if user purchased this class
function dttheme_check_if_user_purchased_this_class($class_id) {
	
	$user_purchased_products = dttheme_get_user_purchased_product_ids('');
	$dt_class_product_id = dttheme_get_class_product_id($class_id);
	
	if(in_array($dt_class_product_id, $user_purchased_products)) {
		return true;
	}
	
	return false;

}

// Check is user subscribed this class
function dttheme_check_if_user_subscribed_this_class($class_id) {
	
	if(dttheme_yith_subscription_plugin_active()) {
		
		$dt_class_type = get_post_meta($class_id, 'dt-class-type', true);
		
		if($dt_class_type == 'online') {
		
			$user_purchased_products = dttheme_get_user_purchased_product_ids('');
			$dt_class_product_ids = dttheme_get_class_subscription_product_ids($class_id);
			
			$subscribed_products = array_intersect($user_purchased_products, $dt_class_product_ids);
				
			if(count($subscribed_products) > 0) {
				
				$subscribed_order_keys = array_keys($subscribed_products);
				$subscribed_order = $subscribed_order_keys[0];
				
				$subscriptions = get_post_meta( $subscribed_order, 'subscriptions', true );
			
				if ( $subscriptions ) {
					foreach ( $subscriptions as $subscription_id ) {
						$subscription = ywsbs_get_subscription( $subscription_id );
					}
				}
				
				$status = $subscription->status;
				$exp_date = $subscription->expired_date;
				$cur_date = current_time('timestamp');
				
				if($status == 'active' && $cur_date <= $exp_date) {
					return true;
				}
				
			}
		
		}
	
	}
	
	return false;

}

// Get class details linked with woocommerce products
function dttheme_get_class_details_linked_with_products( $class_id, $page_type ){

	$seats_available = dttheme_get_onsite_class_seats_available($class_id);
	$dt_class_disable_purchases_regsitration = get_post_meta($class_id, 'dt-class-disable-purchases-regsitration', true);

	$out = '';
	
	if(dttheme_check_if_user_subscribed_this_class($class_id) || dttheme_check_if_user_purchased_this_class($class_id)) {
				
		if($page_type == 'single') {
			$out .= '<div class="dt-sc-purchased-details">';
				$out .= '<span class="dt-sc-purchased"> <span class="fa fa fa-cart-arrow-down"> </span> '.__('Purchased','dt_themes').'</span>';
						$class_status = dt_get_users_class_status($class_id);
						if($class_status) {
							$out .= '<div class="dt-sc-course-completed"> <span class="fa fa-check-circle"> </span> '.__('Completed', 'dt_themes').'</div>';
						}
			$out .= '</div>';
		} else {
			$out .= '<span class="dt-sc-class-price dt-sc-class-amount"><span class="amount">'.__('Purchased','dt_themes').'</span></span>';
			$class_status = dt_get_users_class_status($class_id);
			if($class_status) {
				$out .= '<span class="dt-sc-class-price dt-sc-class-amount"><span class="amount"> '.__('Completed', 'dt_themes').'</span></span>';
			}
		}
		
	} else {
		
		$dt_class_product_id = dttheme_get_class_product_id($class_id);
		
		if(dttheme_is_class_in_cart($class_id)) {
			
			if($page_type == 'single') {
				$out .= '<span class="dt-sc-purchased"> '.__('Added to cart already!','dt_themes').'</span>';
			} else {
				$out .= '<span class="dt-sc-class-price dt-sc-class-amount"><span class="amount">'.__('Added to cart!','dt_themes').'</span></span>';
			}
			
		} else {
			
			if(!empty($dt_class_product_id)) {
				
				if($seats_available > 0 || ($seats_available <= 0 && $dt_class_disable_purchases_regsitration != 'true')) {
					
					$product = dttheme_get_product_object($dt_class_product_id);
					$woo_price = $product->get_price_html();
					
					if($page_type == 'single') {
						
						if($woo_price != '') {
							$out .= '<a href="'.esc_url($product->add_to_cart_url()).'" target="_self"  class="dt-sc-button small filled"><i class="fa fa-shopping-cart"></i> '.$woo_price.' - '.__('Add to Cart','dt_themes').'</a>';
						} else {
							$out .= '<span class="dt-sc-purchased">'.__('Free','dt_themes').'</span>';
						}
						
					} else {
						
						if($woo_price != '') {
							$out .= '<span class="dt-sc-class-price dt-sc-class-amount"><span class="amount">'.$woo_price.'</span></span>';
						} else {
							$out .= '<span class="dt-sc-class-price dt-sc-class-free"><span class="amount">'.__('Free','dt_themes').'</span></span>';
						}
						
					}
				
				} else {
					echo '<span class="dt-sc-purchased">'.esc_html__('Registration Closed', 'dt_themes').'</span>';	
				}
				
			} else {
				
				if($page_type == 'single') {
					$out .= '<span class="dt-sc-purchased">'.__('Free','dt_themes').'</span>';
				} else {
					$out .= '<span class="dt-sc-class-price dt-sc-class-free"><span class="amount">'.__('Free','dt_themes').'</span></span>';
				}
				
			}
		
		}
	
	}
	
	return $out;

}

?>