<?php 
/**
 * Widgets
 */

namespace wdc;

/**
 * Get widget regex
 *
 * @return string
 */
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
 * @param array $instance
 *
 * @return bool
 */
function set_widget_instance( $widget_id, $instance )
{
	if ( ! preg_match( '/' . get_widget_regex() . '/', $widget_id, $matches ) ) 
	{
		return false;
	}

	list(, $id_base, $num ) = $matches;

	$instances = get_option( "widget_$id_base" );

	if ( is_array( $instances ) && isset( $instances[ $num ] ) ) 
	{
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
function get_widget_conditions( $widget_id )
{
	$instance = get_widget_instance( $widget_id );

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
 * @param array $conditions
 *
 * @return bool
 */
function set_widget_conditions( $widget_id, $conditions )
{
	$instance = get_widget_instance( $widget_id );

	if ( isset( $instance ) ) 
	{
		$instance['wdc_conditions'] = (array) $conditions;

		return set_widget_instance( $widget_id, $instance );
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
function delete_widget_conditions( $widget_id )
{
	$instance = get_widget_instance( $widget_id );

	if ( isset( $instance, $instance['wdc_conditions'] ) ) 
	{
		unset( $instance['wdc_conditions'] );

		return set_widget_instance( $widget_id, $instance );
	}

	return false;
}

/**
 * Has widgets conditions
 *
 * @return bool
 */
function has_widgets_conditions()
{
	$sidebars_widgets = get_option( 'sidebars_widgets' );

	if ( ! is_array( $sidebars_widgets ) ) return false;

	foreach ( $sidebars_widgets as $sidebar_index => $widgets ) 
	{
		if ( ! is_array( $widgets ) ) continue;

		foreach ( $widgets as $widget_id ) 
		{
			if ( get_widget_conditions( $widget_id ) ) 
			{
				return true;
			}
		}
	}

	return false;
}

/**
 * Do widget conditions
 *
 * @param string $widget_id
 *
 * @return mixed
 */
function do_widget_conditions( $widget_id )
{
	$conditions = get_widget_conditions( $widget_id );

	if ( isset( $conditions ) ) 
	{
		return do_conditions( $conditions );
	}

	return null;
}

/**
 * Sidebars widgets
 *
 * @param array $sidebars_widgets
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
			$result = do_widget_conditions( $widget_id );

			if ( isset( $result ) && $result ) 
			{
				$return[ $sidebar_index ][] = $widget_id; 
			}
		}
	}

	return $return;
}

add_filter( 'sidebars_widgets', __NAMESPACE__ . '\sidebars_widgets' );
