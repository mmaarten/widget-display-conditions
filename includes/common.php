<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.
/**
 * Common functions
 */

/**
 * Sort order
 *
 * @param mixed $a
 * @param mixed $b
 *
 * @return int
 */
function wdc_sort_order( $a, $b )
{
	if ( is_object( $a ) ) $a = get_object_vars( $a );
	if ( is_object( $b ) ) $b = get_object_vars( $b );

	if ( $a['order'] == $b['order'] ) 
	{
        return 0;
    }

    return ( $a['order'] < $b['order'] ) ? -1 : 1;
}
