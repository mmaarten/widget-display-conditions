<?php 
/**
 * Application
 */

namespace wdc;

// Add operators
require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-is_equal_to-operator.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-is_not_equal_to-operator.php';

// Add condition categories
add_condition_category( 'post'      , __( 'Post', 'wdc' )   , 'order=100' );
add_condition_category( 'page'      , __( 'Page', 'wdc' )   , 'order=200' );
add_condition_category( 'attachment', __( 'Media', 'wdc' )  , 'order=300' );
add_condition_category( 'archive'   , __( 'Archive', 'wdc' ), 'order=400' );
add_condition_category( 'user'      , __( 'User', 'wdc' )   , 'order=500' );

// Add conditions
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_category-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_format-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_status-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_tag-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_taxonomy-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_template-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_type-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page_parent-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page_template-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page_type-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-attachment-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-user-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-user_logged_in-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-user_role-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-archive_author-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-archive_post_type-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-archive_taxonomy-condition.php';

if ( is_admin() ) 
{
	// Add update tasks
	//require_once plugin_dir_path( WDC_FILE ) . 'updater-tasks.php' );
}
