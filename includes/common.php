<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

/**
 * Get Widget Instance
 */
function wdc_get_widget_instance( $widget_id )
{
	if ( ! preg_match( '/(.*?)-(\d+)$/', $widget_id, $matches ) ) 
	{
		return null;
	}

	list( , $id_base, $num ) = $matches;

	$instances = get_option( "widget_$id_base" );

	if ( ! is_array( $instances ) || ! isset( $instances[ $num ] ) ) 
	{
		return null;
	}

	return (array) $instances[ $num ];
}