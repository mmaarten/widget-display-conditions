<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly
/*
Plugin Name:  Widget Display Conditions
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

define( 'WDC_FILE' , __FILE__ );

require_once plugin_dir_path( WDC_FILE ) . 'includes/constants.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/common.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/operators.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-operator.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-condition.php';

if ( is_admin() ) 
{
	require_once plugin_dir_path( WDC_FILE ) . 'includes/admin.php';
}

class WDC
{
	public function init()
	{
		/**
		 * Operators
		 * ---------------------------------------------------------------
		 */

		require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-is-equal-to-operator.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-is-greater-than-operator.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-is-greater-than-or-equal-to-operator.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-is-less-than-operator.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-is-less-than-or-equal-to-operator.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-is-not-equal-to-operator.php';

		/**
		 * Categories
		 * ---------------------------------------------------------------
		 */
		$this->conditions->add_category( 'default', __( 'General', 'wdc' ) );
		$this->conditions->add_category( 'post'   , __( 'Post', 'wdc' ) );
		$this->conditions->add_category( 'page'   , __( 'Page', 'wdc' ) );
		$this->conditions->add_category( 'attachment'  , __( 'Media', 'wdc' ) );
		$this->conditions->add_category( 'archive', __( 'Archive', 'wdc' ) );
		$this->conditions->add_category( 'user'   , __( 'User', 'wdc' ) );

		/**
		 * Conditions
		 * ---------------------------------------------------------------
		 */

		// Post
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post-type-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post-status-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post-template-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post-category-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post-format-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post-tag-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post-taxonomy-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post-condition.php';
		
		// Page
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page-type-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page-parent-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page-template-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page-condition.php';

		// Media
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-attachment-condition.php';

		// Archive
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post-type-archive-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-taxonomy-archive-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-author-archive-condition.php';

		// User
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-user-role-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-user-logged-in-condition.php';
		require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-user-condition.php';
	}

	/**
	 * Filter Sidebars Widgets
	 *
	 * Removes widgets when their display conditions are not met.
	 *
	 * @param array $sidebars_widgets List of widget ids grouped by sidebar.
	 * @return array The filtered widgets.
	 */
	public function filter_sidebars_widgets( $sidebars_widgets )
	{
		foreach ( $sidebars_widgets as $sidebar_id => &$widgets ) 
		{
			/**
			 * From WordPress 4.1.0 to 4.6.0
			 * $widgets can be NULL
			 */

			if ( is_array( $widgets ) ) 
			{
				$_widgets = array();

				foreach ( $widgets as $widget_id ) 
				{
					$conditions = wdc_get_widget_conditions( $widget_id );
					
					if ( $conditions ) 
					{
						$valid = $this->conditions->apply( $conditions );
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
		}

		return $sidebars_widgets;
	}
}

add_action( 'init', array( wdc(), 'init' ) );

if ( ! is_admin() ) 
{
	add_filter( 'sidebars_widgets', array( wdc(), 'filter_sidebars_widgets' ), 15 );
}






