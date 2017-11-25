<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly
/*
Plugin Name:  Widget Display Conditions
Plugin URI:   https://github.com/mmaarten/widget-display-conditions
Description:  Manages widget display by rules.
Version:      0.1.0
Author:       Maarten Menten
Author URI:   https://profiles.wordpress.org/maartenm/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wdc
Domain Path:  /languages
*/

define( 'WDC_FILE' , __FILE__ );

require_once plugin_dir_path( WDC_FILE ) . 'includes/constants.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/autoload.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/common.php';

if ( is_admin() ) 
{
	require_once plugin_dir_path( WDC_FILE ) . 'includes/admin.php';
}

function wdc_init()
{
	WDC_API::register_operator( 'WDC_Operator_Is' );
	WDC_API::register_operator( 'WDC_Operator_IsNot' );
	
	WDC_API::register_rule( 'WDC_Rule_PostStatus' );
	WDC_API::register_rule( 'WDC_Rule_PostTemplate' );
	WDC_API::register_rule( 'WDC_Rule_PostTerm' );
	WDC_API::register_rule( 'WDC_Rule_PostType' );
	WDC_API::register_rule( 'WDC_Rule_Post' );

	WDC_API::register_rule( 'WDC_Rule_PageParent' );
	WDC_API::register_rule( 'WDC_Rule_PageType' );
	WDC_API::register_rule( 'WDC_Rule_Page' );

	WDC_API::register_rule( 'WDC_Rule_UserRole' );
	WDC_API::register_rule( 'WDC_Rule_UserLoggedIn' );
	WDC_API::register_rule( 'WDC_Rule_User' );
}

add_action( 'init', 'wdc_init', 999 );

/**
 * Filter Sidebars Widgets
 *
 * Removes widgets when their display conditions are not met.
 *
 * @param array $sidebars_widgets List of widget ids grouped by sidebar.
 * @return array The filtered widgets.
 */

function wdc_filter_sidebars_widgets( $sidebars_widgets )
{
	foreach ( $sidebars_widgets as $sidebar_id => &$widgets ) 
	{
		$_widgets = array();

		foreach ( $widgets as $widget_id ) 
		{
			$rule_data = wdc_get_widget_rules( $widget_id );
			
			if ( $rule_data ) 
			{
				$valid = WDC_API::apply_rules( $rule_data );
			}

			else
			{
				$valid = true;
			}

			if ( $valid ) 
			{
				$_widgets[] = $widget_id;
			}
		}

		$widgets = $_widgets;
	}

	return $sidebars_widgets;
}

if ( ! is_admin() ) 
{
	add_filter( 'sidebars_widgets', 'wdc_filter_sidebars_widgets', 15 );
}

