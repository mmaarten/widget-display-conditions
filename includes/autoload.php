<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

function wdc_autoload( $class )
{
	$prefix = 'WDC';

	// Checks prefix.

	if ( ! preg_match( "/^($prefix)/", $class ) ) 
	{
		return;
	}

	// Gets path.

	$path = explode( '_', $class );

	array_unshift( $path , 'src' );

	$path = implode( DIRECTORY_SEPARATOR, $path );

	// Loads file.

	require_once plugin_dir_path( WDC_FILE ) . $path . '.php';
}

spl_autoload_register( 'wdc_autoload' );
