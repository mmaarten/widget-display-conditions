<?php 
/**
 * Widgets
 */

namespace wdc;

function get_widget_regex()
{
	return apply_filters( 'wdc/widget_regex', '^([\w-]+)-(\d+)$' );
}

/**
 * Get widget instance
 *
 * @param string $widget_id
 *
 * @return mixed
 */
function get_widget_instance( $widget_id )
{
	if ( ! preg_match( '/' . get_widget_regex() . '/', $widget_id, $matches ) ) 
	{
		return null;
	}

	list(, $id_base, $num ) = $matches;

	$instances = get_option( "widget_$id_base" );

	if ( ! is_array( $instances ) || ! isset( $instances[ $num ] ) ) 
	{
		return null;
	}

	return (array) $instances[ $num ];
}

/**
 * Set widget instance
 *
 * @param string $widget_id
 * @param array  $instance
 *
 * @return mixed
 */
function set_widget_instance( $widget_id, $instance )
{
	if ( ! preg_match( '/' . get_widget_regex() . '/', $widget_id, $matches ) )
	{
		return false;
	}

	list(, $id_base, $num ) = $matches;

	$instances = get_option( "widget_$id_base" );

	if ( ! is_array( $instances ) || ! isset( $instances[ $num ] ) ) 
	{
		return false;
	}

	$instances[ $num ] = (array) $instance;

	return update_option( "widget_$id_base", $instances );
}

/**
 * Get widget conditions
 *
 * @param string $widget_id
 *
 * @return mixed
 */
function get_widget_conditions( $widget_id )
{
	$instance = get_widget_instance( $widget_id );

	if ( ! is_array( $instance ) ) 
	{
		return null;
	}

	if ( isset( $instance['wdc_conditions'] ) ) 
	{
		return (array) $instance['wdc_conditions'];
	}

	return array();
}

/**
 * Set widget conditions
 *
 * @param string $widget_id
 * @param array  $conditions
 *
 * @return mixed
 */
function set_widget_conditions( $widget_id, $conditions )
{
	$instance = get_widget_instance( $widget_id );

	if ( ! is_array( $instance ) ) 
	{
		return false;
	}

	$instance['wdc_conditions'] = (array) $conditions;

	return set_widget_instance( $widget_id, $instance );
}

/**
 * Do widget conditions
 *
 * @param string $widget_id
 *
 * @return bool
 */
function do_widget_conditions( $widget_id )
{
	$conditions = get_widget_conditions( $widget_id );

	if ( is_array( $conditions ) ) 
	{
		return do_conditions( $conditions );
	}

	return true;
}

/**
 * Sidebars widgets
 *
 * @param array  $sidebars_widgets
 *
 * @return array
 */
function sidebars_widgets( $sidebars_widgets )
{
	if ( is_admin() ) return $sidebars_widgets;

	$return = array();

	foreach ( $sidebars_widgets as $sidebar_index => $widgets ) 
	{
		if ( ! is_array( $widgets ) ) continue;

		foreach ( $widgets as $widget_id ) 
		{
			$conditions_met = do_widget_conditions( $widget_id );

			if ( ! isset( $conditions_met ) || $conditions_met ) 
			{
				$return[ $sidebar_index ][] = $widget_id;
			}
		}
	}

	return $return;
}

add_filter( 'sidebars_widgets', __NAMESPACE__ . '\sidebars_widgets', 999 );
