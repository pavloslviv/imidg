<?php

/* ---------------------------------------------------------------------------
 * Facebook login utils
 * --------------------------------------------------------------------------- */
 
function dttheme_facebook_login_url() {
	
  return site_url('wp-login.php') . '?dtLMSFacebookLogin=1';
  
}
 
function dttheme_facebook_login() {
	
  if (isset($_REQUEST['dtLMSFacebookLogin']) && $_REQUEST['dtLMSFacebookLogin'] == '1') {
	dttheme_facebook_login_action();
  }
  
}
add_action('login_init', 'dttheme_facebook_login');
 
 	 
function dttheme_facebook_login_action() {

	require_once WP_PLUGIN_DIR.'/designthemes-core-features/apis/facebook/facebook.php';
	$appId = dttheme_option('general','facebook-app-id'); //Facebook App ID
	$appSecret = dttheme_option('general','facebook-app-secret'); // Facebook App Secret
	$fbPermissions = array('email', 'public_profile', 'user_friends');  //Required facebook permissions
		
	//Call Facebook API
	$facebook = new Facebook(array(
		'appId'  => $appId,
		'secret' => $appSecret,
	));
	$fbuser = $facebook->getUser();
	
	if ($fbuser) {
		
		$user_profile = $facebook->api('/me', 'GET', array('fields' => 'id,name,email,first_name,last_name'));
		
		$args = array(
			'meta_key'     => 'facebook_id',
			'meta_value'   => $user_profile['id'],
			'meta_compare' => '=',
		 ); 
		$users = get_users( $args );		
		
		if(is_array($users) && !empty($users)) {
			$ID = $users[0]->data->ID;
		} else {
			$ID = NULL;
		}
		
		if ($ID == NULL) {
			
			if (!isset($user_profile['email'])) {
				$user_profile['email'] = $user_profile['id'] . '@facebook.com';
			}
			
			require_once (ABSPATH . WPINC . '/registration.php');
			
			$random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
			
			$username = strtolower($user_profile['first_name'].$user_profile['last_name']);
			$username = trim(str_replace(' ', '', $username));
			
			$sanitized_user_login = sanitize_user('facebook-'.$username);
			
			if (!validate_username($sanitized_user_login)) {
				$sanitized_user_login = sanitize_user('facebook-' . $user_profile['id']);
			}
			
			$defaul_user_name = $sanitized_user_login;
			$i = 1;
			while (username_exists($sanitized_user_login)) {
			  $sanitized_user_login = $defaul_user_name . $i;
			  $i++;
			}
			
			$ID = wp_create_user($sanitized_user_login, $random_password, $user_profile['email']);
			
			if (!is_wp_error($ID)) {
				
				$payment_method = dttheme_option('general','payment-method') != '' ? dttheme_option('general','payment-method') : 's2member';
				if($payment_method == 'woocommerce') {
					$user_role = 'customer';
				} else {
					$user_role = 's2member_level1';
				}
				
				wp_new_user_notification($ID, $random_password);
				$user_info = get_userdata($ID);
				wp_update_user(array(
					'ID' => $ID,
					'display_name' => $user_profile['name'],
					'first_name' => $user_profile['first_name'],
					'last_name' => $user_profile['last_name'],
					'role' => $user_role
				));
				
				update_user_meta($ID, 'facebook_id', $user_profile['id']);
			
			}
			
		}
		
		// Login
		if ($ID) { 

		  $secure_cookie = is_ssl();
		  $secure_cookie = apply_filters('secure_signon_cookie', $secure_cookie, array());
		  global $auth_secure_cookie;

		  $auth_secure_cookie = $secure_cookie;
		  wp_set_auth_cookie($ID, false, $secure_cookie);
		  $user_info = get_userdata($ID);
		  update_user_meta($ID, 'fb_profile_picture', 'https://graph.facebook.com/' . $user_profile['id'] . '/picture?type=large');
		  do_action('wp_login', $user_info->user_login, $user_info, 10, 2);
		  update_user_meta($ID, 'fb_user_access_token', $facebook->getAccessToken());
		  
		}
		
		
	} else {
		
		$loginUrl = $facebook->getLoginUrl(array(
		  'scope' => $fbPermissions,
		));
				
		header('Location: ' . $loginUrl);
		exit;

	}
	
	
}


/* ---------------------------------------------------------------------------
 * Google Plus login utils
 * --------------------------------------------------------------------------- */

function dttheme_google_login_url() {
	
  return site_url('wp-login.php') . '?dtLMSGoogleLogin=1';
  
}

function dttheme_google_login() {
	
  if (isset($_REQUEST['dtLMSGoogleLogin']) && $_REQUEST['dtLMSGoogleLogin'] == '1') {
	dttheme_google_login_action();
  }
  
}
add_action('login_init', 'dttheme_google_login');

function dttheme_google_login_action() {

	require_once WP_PLUGIN_DIR.'/designthemes-core-features/apis/google/Google_Client.php';
	require_once WP_PLUGIN_DIR.'/designthemes-core-features/apis/google/contrib/Google_Oauth2Service.php';
	
	$clientId = dttheme_option('general','google-client-id'); //Google CLIENT ID
	$clientSecret = dttheme_option('general','google-client-secret'); //Google CLIENT SECRET
	$redirectUrl = dttheme_google_login_url();  //return url (url to script)
		
	$gClient = new Google_Client();
	$gClient->setApplicationName(esc_html__('Login To', 'dt_themes').' '.IAMD_THEME_NAME);
	$gClient->setClientId($clientId);
	$gClient->setClientSecret($clientSecret);
	$gClient->setRedirectUri($redirectUrl);
	
	$google_oauthV2 = new Google_Oauth2Service($gClient);
	
	if(isset($google_oauthV2)){
		
		$gClient->authenticate();
		$_SESSION['token'] = $gClient->getAccessToken();

		if (isset($_SESSION['token'])) {
			$gClient->setAccessToken($_SESSION['token']);
		}
		
		$user_profile = $google_oauthV2->userinfo->get();
	
		$args = array(
			'meta_key'     => 'google_id',
			'meta_value'   => $user_profile['id'],
			'meta_compare' => '=',
		 ); 
		$users = get_users( $args );		
		
		if(is_array($users) && !empty($users)) {
			$ID = $users[0]->data->ID;
		} else {
			$ID = NULL;
		}
		
		if ($ID == NULL) {
						
			if (!isset($user_profile['email'])) {
				$user_profile['email'] = $user_profile['id'] . '@google.com';
			}
			
			require_once (ABSPATH . WPINC . '/registration.php');
			
			$random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
			
			$username = strtolower($user_profile['name']);
			$username = trim(str_replace(' ', '', $username));
			
			$sanitized_user_login = sanitize_user('google-'.$username);
			
			if (!validate_username($sanitized_user_login)) {
				$sanitized_user_login = sanitize_user('google-' . $user_profile['id']);
			}
			
			$defaul_user_name = $sanitized_user_login;
			$i = 1;
			while (username_exists($sanitized_user_login)) {
			  $sanitized_user_login = $defaul_user_name . $i;
			  $i++;
			}
			
			$ID = wp_create_user($sanitized_user_login, $random_password, $user_profile['email']);
						
			if (!is_wp_error($ID)) {
				
				$payment_method = dttheme_option('general','payment-method') != '' ? dttheme_option('general','payment-method') : 's2member';
				if($payment_method == 'woocommerce') {
					$user_role = 'customer';
				} else {
					$user_role = 's2member_level1';
				}
				
				wp_new_user_notification($ID, $random_password);
				$user_info = get_userdata($ID);
				wp_update_user(array(
					'ID' => $ID,
					'display_name' => $user_profile['name'],
					'first_name' => $user_profile['name'],
					'role' => $user_role
				));
				
				update_user_meta($ID, 'google_id', $user_profile['id']);
			
			}
			
		}
		
		// Login
		if ($ID) { 

		  $secure_cookie = is_ssl();
		  $secure_cookie = apply_filters('secure_signon_cookie', $secure_cookie, array());
		  global $auth_secure_cookie;

		  $auth_secure_cookie = $secure_cookie;
		  wp_set_auth_cookie($ID, false, $secure_cookie);
		  $user_info = get_userdata($ID);
		  update_user_meta($ID, 'google_profile_picture', $user_profile['picture']);
		  do_action('wp_login', $user_info->user_login, $user_info, 10, 2);
		  update_user_meta($ID, 'google_user_access_token', $_SESSION['token']);
		  
		}
		
	} else {
		
		$authUrl = $gClient->createAuthUrl();
		header('Location: ' . $authUrl);
		exit;
		
	}
	
}

?>