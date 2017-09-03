<?php if( ! defined('IAMD_BASE_URL' ) ){	define( 'IAMD_BASE_URL',  get_template_directory_uri().'/'); }
define('IAMD_FW_URL', IAMD_BASE_URL . 'framework/' );
define('IAMD_FW',get_template_directory().'/framework/');
define('IAMD_TD',get_template_directory());
define('IAMD_IMPORTER_URL',IAMD_FW.'wordpress-importer/');
define('IAMD_THEME_SETTINGS', 'mytheme');
define('IAMD_THEME_URI', get_template_directory_uri());
define('IAMD_SAMPLE_FONT', __('The quick brown fox jumps over the lazy dog','dt_themes') );

$user_id = get_current_user_id();
if($user_id > 0) {
	
    global $current_user;
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);

	define('IAMD_USER_ROLE', $user_role);
		
} else {
	
	define('IAMD_USER_ROLE', '');
	
}

/* Define IAMD_THEME_NAME
 * Objective:	
 *		Used to show theme name where ever needed( eg: in widgets title ar the back-end).
 */
// get themedata version wp 3.4+
if(function_exists('wp_get_theme')):
	$theme_data = wp_get_theme();
	define('IAMD_THEME_NAME',$theme_data->get('Name'));
	define('IAMD_THEME_FOLDER_NAME',$theme_data->template);
	define('IAMD_THEME_VERSION',(float) $theme_data->get('Version'));
endif;

$options = get_option(IAMD_THEME_SETTINGS);
$GLOBALS['teachers-singular-label'] = ($options['general']['teachers-singular-label'] != '') ? $options['general']['teachers-singular-label'] : 'Teacher';
$GLOBALS['teachers-plural-label'] = ($options['general']['teachers-plural-label'] != '') ? $options['general']['teachers-plural-label'] : 'Teachers';

#ALL BACKEND DETAILS WILL BE IN include.php
require_once (get_template_directory () . '/framework/include.php');
if ( ! isset( $content_width ) ) $content_width = 1170;

$GLOBALS['force_enable'] = dttheme_option('general', 'force-enable-global-layout');
$GLOBALS['page_layout'] = dttheme_option('general', 'global-page-layout');

add_filter('gettext', 'translate_votes');
add_filter('ngettext', 'translate_votes');
function translate_votes($translated) {
	$translated = str_ireplace('votes', 'голосів', $translated);
	return $translated;
}
?>