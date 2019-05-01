<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.
/*
Plugin Name:  Widget Display Conditions
Plugin URI:   https://wordpress.org/plugins/widget-display-conditions/
Description:  Control on which page you want a particular widget to be displayed.
Version:      0.2.2
Author:       Maarten Menten
Author URI:   https://profiles.wordpress.org/maartenm/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wdc
Domain Path:  /languages
*/

defined( 'WDC_PLUGIN_FILE' )     or define( 'WDC_PLUGIN_FILE', __FILE__ );
defined( 'WDC_ABSPATH' )         or define( 'WDC_ABSPATH', dirname( WDC_PLUGIN_FILE ) . '/' );
defined( 'WDC_VERSION' )         or define( 'WDC_VERSION', '0.2.2' );
defined( 'WDC_NONCE_NAME' )      or define( 'WDC_NONCE_NAME', 'wdc_nonce' );
defined( 'WDC_MAX_FIELD_ITEMS' ) or define( 'WDC_MAX_FIELD_ITEMS', 9999 );

include_once WDC_ABSPATH . 'includes/common.php';
include_once WDC_ABSPATH . 'includes/conditions-api.php';
include_once WDC_ABSPATH . 'includes/widgets.php';
include_once WDC_ABSPATH . 'includes/operators/class-wdc-operator.php';
include_once WDC_ABSPATH . 'includes/operators.php';
include_once WDC_ABSPATH . 'includes/conditions/class-wdc-condition.php';
include_once WDC_ABSPATH . 'includes/conditions.php';

if ( is_admin() ) 
{
	include_once WDC_ABSPATH . 'includes/updater.php';
	include_once WDC_ABSPATH . 'includes/updater-tasks.php';
	include_once WDC_ABSPATH . 'includes/fields.php';
	include_once WDC_ABSPATH . 'includes/ui.php';
}

/**
 * Init
 */
function wdc_init()
{
	// Add operators
	include_once WDC_ABSPATH . 'includes/operators/class-wdc-is_equal_to-operator.php';
	include_once WDC_ABSPATH . 'includes/operators/class-wdc-is_not_equal_to-operator.php';

	// Add condition categories
	wdc_add_condition_category( 'post'      , __( 'Post', 'wdc' )   , 'order=100' );
	wdc_add_condition_category( 'page'      , __( 'Page', 'wdc' )   , 'order=200' );
	wdc_add_condition_category( 'attachment', __( 'Media', 'wdc' )  , 'order=300' );
	wdc_add_condition_category( 'archive'   , __( 'Archive', 'wdc' ), 'order=400' );
	wdc_add_condition_category( 'user'      , __( 'User', 'wdc' )   , 'order=500' );

	// Add conditions
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-post-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-post_category-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-post_format-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-post_status-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-post_tag-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-post_taxonomy-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-post_template-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-post_type-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-page-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-page_parent-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-page_template-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-page_type-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-attachment-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-user-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-user_logged_in-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-user_role-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-archive_author-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-archive_post_type-condition.php';
	include_once WDC_ABSPATH . 'includes/conditions/class-wdc-archive_taxonomy-condition.php';
}

add_action( 'init', 'wdc_init' );
