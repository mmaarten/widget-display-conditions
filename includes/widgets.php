<?php 
/**
 * Widgets API
 */

namespace wdc;

/**
 * Get Widget Instance
 *
 * @param string $widget_id
 *
 * @return mixed
 */
function get_widget_instance( $widget_id )
{
	if ( ! preg_match( '/^([\w-]+)-(\d+)$/', $widget_id, $matches ) ) 
	{
		trigger_error( sprintf( 'Invalid widget id "%s".', $widget_id ), E_USER_WARNING );

		return null;
	}

	list(, $id_base, $num ) = $matches;

	$instances = get_option( "widget_{$id_base}" );

	if ( ! is_array( $instances ) || ! array_key_exists( $num, $instances ) ) 
	{
		trigger_error( sprintf( 'Instance for widget "%s" could not be found.', $widget_id ), E_USER_WARNING );

		return null;
	}

	if ( is_array( $instances[ $num ] ) ) 
	{
		return $instances[ $num ];
	}

	return array();
}

/**
 * Set Widget Instance
 *
 * @param string $widget_id
 * @param array  $instance
 *
 * @return mixed
 */
function set_widget_instance( $widget_id, $instance )
{
	if ( ! preg_match( '/^([\w-]+)-(\d+)$/', $widget_id, $matches ) ) 
	{
		trigger_error( sprintf( 'Invalid widget id "%s".', $widget_id ), E_USER_WARNING );

		return null;
	}

	list(, $id_base, $num ) = $matches;

	$instances = get_option( "widget_{$id_base}" );

	if ( ! is_array( $instances ) || ! array_key_exists( $num, $instances ) ) 
	{
		trigger_error( sprintf( 'Instance for widget "%s" could not be found.', $widget_id ), E_USER_WARNING );

		return false;
	}

	$instances[ $num ] = (array) $instance;

	return update_option( "widget_{$id_base}", $instances );
}

/**
 * Get Widget Conditions
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

	return null;
}

/**
 * Set Widget Conditions
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
 * Do Widget Conditions
 *
 * @param string $widget_id
 *
 * @return bool
 */
function apply_widget_conditions( $widget_id )
{
	$rules = get_widget_conditions( $widget_id );

	if ( ! is_array( $rules ) ) 
	{
		return false;
	}

	return apply_conditions( $rules );
}

/**
 * Sidebars Widgets
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
		// From WordPress 4.1.0 to 4.6.0 $widgets can be NULL
		if ( is_array( $widgets ) ) 
		{
			$return[ $sidebar_index ] = array();

			foreach ( $widgets as $widget_id ) 
			{
				if ( apply_widget_conditions( $widget_id ) ) 
				{
					$return[ $sidebar_index ][] = $widget_id;
				}
			}
		}
	}

	return $return;
}

add_filter( 'sidebars_widgets', 'wdc\sidebars_widgets', 999 );
