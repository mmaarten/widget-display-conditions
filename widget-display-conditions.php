<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly
/*
Plugin Name:  Widget Display Conditions
Plugin URI:   https://github.com/mmaarten/widget-display-conditions
Description: 
Version:      0.1.0
Author:       Maarten Menten
Author URI:   https://profiles.wordpress.org/maartenm/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wdc
Domain Path:  /languages
*/

define( 'WDC_FILE' , __FILE__ );
defined( 'WDC_MAX_NUMBERPOSTS' ) or define( 'WDC_MAX_NUMBERPOSTS', 999 );

require_once plugin_dir_path( WDC_FILE ) . 'includes/common.php';

if ( is_admin() ) 
{
	require_once plugin_dir_path( WDC_FILE ) . 'includes/admin.php';
}

/**
 * Apply Rule
 */
function wdc_apply_condition( $condition )
{
	$queried_object = get_queried_object();

	$is_location = false;

	switch ( $condition->param ) 
	{
		case 'post_type' :
				
			$is_location = is_singular( $condition->value );

			break;

		case 'post' :
				
			$is_location = is_single( $condition->value );

			break;

		case 'post_term' :

			if ( is_category() || is_tag() || is_tax() ) 
			{
				$is_location = $condition->value == $queried_object->term_id;
			}

			break;

		case 'post_archive' :

			$is_location = is_post_type_archive( $condition->value );

			break;

		case 'post_template' :
		case 'page_template' :

			$is_location = is_page_template( $condition->value );

			break;

		case 'page' :
				
			$is_location = is_page( $condition->value );

			break;

		case 'page_parent' :

			if ( is_page() ) 
			{
				$ancestors = get_ancestors( $queried_object->ID, 'page', 'post_type' );

				$is_location = in_array( $condition->value, $ancestors );
			}

			break;

		case 'page_type' :
				
			switch ( $condition->value ) 
			{
				case 'front_page' :
					
					$is_location == is_front_page();

					break;

				case 'posts_page' :
					
					$is_location == is_home() && ! is_front_page();

					break;


				case 'top_level' :

					$is_location = is_page() && $queried_object->post_parent = 0;

					break;

				case 'parent' :

					if ( is_page() ) 
					{
						$children = get_children( array
						( 
							'post_parent' => $queried_object->ID, 
							'post_type'   => 'page',
							'numberposts' => 1
						));

						$is_location = count( $children ) ? true : false;
					}

					break;

				case 'child' :

					$is_location = is_page() && $queried_object->post_parent != 0;

					break;

				case 'search_page' :

					$is_location = is_search();

					break;

				case '404_page' :

					$is_location = is_404();

					break;

				case 'date_page' :

					$is_location = is_date();

					break;

				case 'author_page' :

					$is_location = is_author();

					break;
				
			}

			break;
	}

	// checks operator

	$valid = ( $is_location && $condition->operator == '==' ) || ( ! $is_location && $condition->operator == '!=' );

	//

	return apply_filters( 'wdc_apply_condition', $valid, $condition );
}

/**
 * Get Widget Rules Rules
 */
function wdc_get_widget_conditions( $widget_id )
{
	$instance = wdc_get_widget_instance( $widget_id );

	if ( $instance && isset( $instance['wdc_conditions'] ) ) 
	{
		return (array) $instance['wdc_conditions'];
	}

	return array();
}

/**
 * Apply Rules
 */
function wdc_apply_conditions( $widget_id )
{
	$conditions = wdc_get_widget_conditions( $widget_id );
	
	foreach ( $conditions as $condition_group ) 
	{
		foreach ( $condition_group as $condition )
		{
			$valid = wdc_apply_condition( $condition );

			if ( ! $valid ) 
			{
				break;
			}
		}

		if ( $valid ) 
		{
			break;
		}
	}

	return $valid;
}


/**
 * Filter Sidebars Widgets
 *
 * Removes widgets when their location conditions don't apply.
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
			$valid = wdc_apply_conditions( $widget_id );

			if ( $valid ) 
			{
				$_widgets[] = $widget_id;
			}
		}

		$widgets = $_widgets;
	}

	return $sidebars_widgets;
}

add_action( 'template_redirect', function()
{
	add_filter( 'sidebars_widgets', 'wdc_filter_sidebars_widgets', 15 );
});
	

