<?php defined( 'WP_UNINSTALL_PLUGIN' ) or exit;

require_once dirname( __FILE__ ) . '/includes/widgets.php';

// Remove options

delete_option( 'wdc_version' );

// Remove widget conditions

$sidebars_widgets = get_option( 'sidebars_widgets' );

if ( is_array( $sidebars_widgets ) ) 
{
	if ( isset( $sidebars_widgets['array_version'] ) ) 
	{
		unset( $sidebars_widgets['array_version'] );
	}

	foreach ( $sidebars_widgets as $widgets ) 
	{
		if ( ! is_array( $widgets ) ) continue;

		foreach ( $widgets as $widget_id ) 
		{
			delete_widget_conditions( $widget_id );
		}
	}
}
