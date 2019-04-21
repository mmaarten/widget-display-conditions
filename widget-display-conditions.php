<?php 
/*
Plugin Name:  Widget Display Conditions - NEW
Plugin URI:   https://wordpress.org/plugins/widget-display-conditions/
Description:  Control on which page you want a particular widget to be displayed.
Version:      0.2.0
Author:       Maarten Menten
Author URI:   https://profiles.wordpress.org/maartenm/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wdc
Domain Path:  /languages
*/

namespace wdc;

define( 'WDC_FILE' , __FILE__ );
define( 'WDC_VERSION', '0.2.0' );
define( 'WDC_NONCE_NAME', 'wdc_nonce' );
defined( 'WDC_MAX_NUMBER_POSTS' ) or define( 'WDC_MAX_NUMBER_POSTS', 9999 );

require_once plugin_dir_path( WDC_FILE ) . 'includes/common.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/widgets.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/operators.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-operator.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-condition.php';

function init()
{
	// Operators
	require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-is_equal_to-operator.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-is_not_equal_to-operator.php';

	// Condition categories
	// add_condition_category( 'post'      , __( 'Post', 'wdc' ) );
	// add_condition_category( 'page'      , __( 'Page', 'wdc' ) );
	// add_condition_category( 'attachment', __( 'Attachment', 'wdc' ) );
	// add_condition_category( 'archive'   , __( 'Archive', 'wdc' ) );
	// add_condition_category( 'user'      , __( 'User', 'wdc' ) );

	// Conditions
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page_type-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page_template-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page_parent-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page-condition.php';

	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_type-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_tag-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_taxonomy-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_category-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_status-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_template-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_format-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post-condition.php';

	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-attachment-condition.php';
	
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-archive_post_type-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-archive_author-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-archive_taxonomy-condition.php';
	
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-user_role-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-user_logged_in-condition.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-user-condition.php';

}

// Set high prio to make sure all post type, taxonomies, … are setup.
add_action( 'init', 'wdc\init', 999 );

function condition_defaults( $defaults )
{
	$defaults['operators'] = array( '==', '!=' );

	return $defaults;
}

add_filter( 'wdc/condition_defaults', 'wdc\condition_defaults', 0 );

if ( is_admin() ) 
{
	require_once plugin_dir_path( WDC_FILE ) . 'includes/ui.php';
}
