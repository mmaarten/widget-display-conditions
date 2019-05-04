<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.
/**
 * Widget functions
 */

/**
 * Get widget regex
 *
 * Regular Expression to get widget base id and number from widget id.
 *
 * @return string
 */
function wdc_get_widget_regex()
{
	return apply_filters( 'wdc/widget_regexp', '^([\w-]+)-(\d+)$' );
}

/**
 * Get widget instance
 *
 * @param string $widget_id
 *
 * @return mixed
 */
function wdc_get_widget_instance( $widget_id )
{
	// Get widget type (base id) and number

	if ( ! preg_match( '/' . wdc_get_widget_regex() . '/', $widget_id, $matches ) ) 
	{
		return null;
	}

	list(, $id_base, $num ) = $matches;

	// Get all instances of same type

	$instances = get_option( "widget_$id_base" );

	// Get widget instance

	if ( is_array( $instances ) && isset( $instances[ $num ] ) ) 
	{
		return $instances[ $num ];
	}

	return null;
}

/**
 * Set widget instance
 *
 * @param string $widget_id
 * @param array  $instance
 *
 * @return bool
 */
function wdc_set_widget_instance( $widget_id, $instance )
{
	// Get widget type (base id) and number

	if ( ! preg_match( '/' . wdc_get_widget_regex() . '/', $widget_id, $matches ) )
	{
		return false;
	}

	list(, $id_base, $num ) = $matches;

	// Get all instances of same type

	$instances = get_option( "widget_$id_base" );

	// Check widget instance

	if ( is_array( $instances ) && isset( $instances[ $num ] ) ) 
	{
		// Update instance

		$instances[ $num ] = (array) $instance;

		return update_option( "widget_$id_base", $instances );
	}

	return false;
}

/**
 * Get widget conditions
 *
 * @param string $widget_id
 *
 * @return mixed
 */
function wdc_get_widget_conditions( $widget_id )
{
	$instance = wdc_get_widget_instance( $widget_id );

	if ( isset( $instance, $instance['wdc_conditions'] ) ) 
	{
		return $instance['wdc_conditions'];
	}

	return null;
}

/**
 * Set widget conditions
 *
 * @param string $widget_id
 * @param array  $conditions
 *
 * @return bool
 */
function wdc_set_widget_conditions( $widget_id, $conditions )
{
	$instance = wdc_get_widget_instance( $widget_id );

	if ( isset( $instance ) ) 
	{
		$instance['wdc_conditions'] = (array) $conditions;

		return wdc_set_widget_instance( $widget_id, $instance );
	}

	return false;
}

/**
 * Delete widget conditions
 *
 * @param string $widget_id
 *
 * @return bool
 */
function wdc_delete_widget_conditions( $widget_id )
{
	$instance = wdc_get_widget_instance( $widget_id );

	if ( isset( $instance, $instance['wdc_conditions'] ) ) 
	{
		unset( $instance['wdc_conditions'] );

		return wdc_set_widget_instance( $widget_id, $instance );
	}

	return false;
}

/**
 * Has widgets conditions
 *
 * Check if there are widgets with conditions.
 *
 * @return bool
 */
function wdc_has_widgets_conditions()
{
	$sidebars_widgets = get_option( 'sidebars_widgets' );

	if ( ! is_array( $sidebars_widgets ) ) return false;

	foreach ( $sidebars_widgets as $widgets ) 
	{
		if ( ! is_array( $widgets ) ) continue;

		foreach ( $widgets as $widget_id ) 
		{
			if ( wdc_get_widget_conditions( $widget_id ) ) 
			{
				return true;
			}
		}
	}

	return false;
}

/**
 * Delete widgets conditions
 *
 * Delete all conditions from all widgets.
 */
function wdc_delete_widgets_conditions()
{
	$sidebars_widgets = get_option( 'sidebars_widgets' );

	if ( ! is_array( $sidebars_widgets ) ) return;

	foreach ( $sidebars_widgets as $sidebar_index => $widgets ) 
	{
		if ( ! is_array( $widgets ) ) continue;

		foreach ( $widgets as $widget_id ) 
		{
			wdc_delete_widget_conditions( $widget_id );
		}
	}
}

/**
 * Do widget conditions
 *
 * @param string $widget_id
 *
 * @return mixed
 */
function wdc_do_widget_conditions( $widget_id )
{
	$conditions = wdc_get_widget_conditions( $widget_id );

	if ( isset( $conditions ) ) 
	{
		return wdc_do_conditions( $conditions );
	}

	return null;
}

/**
 * Sidebar widgets
 *
 * Exclude widgets which display conditions are not met.
 *
 * @param array $sidebars_widgets
 *
 * @return array
 */
function wdc_sidebars_widgets( $sidebars_widgets )
{
	if ( is_admin() ) return $sidebars_widgets;

	$return = array();

	foreach ( $sidebars_widgets as $sidebar_index => $widgets ) 
	{
		if ( ! is_array( $widgets ) ) continue;

		foreach ( $widgets as $widget_id ) 
		{
			$result = wdc_do_widget_conditions( $widget_id );

			if ( ! isset( $result ) || $result ) 
			{
				$return[ $sidebar_index ][] = $widget_id;
			}
		}
	}

	return $return;
}

add_filter( 'sidebars_widgets', 'wdc_sidebars_widgets', 999 );
