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

function wdc_get_post_templates() 
{
	// Defaults.
	$post_templates = array
	(
		'page'	=> array()
	);
	
	// Loop over post types and append their templates.
	if( method_exists( 'WP_Theme', 'get_page_templates' ) ) 
	{
		$post_types = get_post_types( array( 'public' => true ), 'names' );

		foreach( $post_types as $post_type ) 
		{
			$templates = wp_get_theme()->get_page_templates( null, $post_type );
			
			if ( $templates ) 
			{
				$post_templates[ $post_type ] = $templates;
			}
		}
	}
	
	// Return.
	return $post_templates;
}
