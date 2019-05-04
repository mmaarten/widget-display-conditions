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

/**
 * Get post templates
 *
 * @return array
 */
function wdc_get_post_templates() 
{
	$post_templates = array
	(
		'page' => array(),
	);
	
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
	
	return $post_templates;
}

function wdc_get_post_choices( $post_type )
{
	$post_type = get_post_type_object( $post_type );

	if ( ! $post_type ) return null;

	// Get posts

	if ( $post_type->hierarchical ) 
	{
		$posts = get_pages( array
		(
			'post_type'    => $post_type->name,
			'post_status'  => 'publish',
			'hierarchical' => true,
			'sort_column'  => 'post_title',
			'sort_order'   => 'asc',
			'number'       => WDC_MAX_FIELD_ITEMS,
		));
	}

	else
	{
		$posts = get_posts( array
		(
			'post_type'   => $post_type->name,
			'post_status' => 'attachment' == $post_type->name ? 'inherit' : 'publish',
			'orderby'     => 'post_title',
			'order'       => 'ASC',
			'numberposts' => WDC_MAX_FIELD_ITEMS,
		));
	}

	// Get values

	$values = array();

	foreach ( $posts as $post ) 
	{
		$text = trim( $post->post_title ) ? $post->post_title : $post->ID;
		$pad  = '';

		if ( $post_type->hierarchical ) 
		{
			$ancestors = get_post_ancestors( $post );
			$pad = str_repeat( '-', count( $ancestors ) );
		}

		$values[ $post->ID ] = "$pad$text";
	}

	return $values;
}

function wdc_get_term_choices( $taxonomy )
{
	// Get taxonomy object

	$taxonomy = get_taxonomy( $taxonomy );

	if ( ! $taxonomy ) return null;

	// Get terms

	$terms = get_terms( array
	(
		'taxonomy'     => $taxonomy->name,
		'hierarchical' => $taxonomy->hierarchical,
		'orderby'      => 'parent name',
		'order'        => 'ASC',
		'number'       => WDC_MAX_FIELD_ITEMS,
	));

	// Get values

	$values = array();

	foreach ( $terms as $term ) 
	{
		$text = trim( $term->name ) ? $term->name : $term->term_id;
		$pad  = '';

		if ( $taxonomy->hierarchical ) 
		{
			$ancestors = get_ancestors( $term->term_id, $taxonomy->name, 'taxonomy' );
			$pad = str_repeat( '-', count( $ancestors ) );
		}

		$values[ $term->term_id ] = "$pad$text";
	}

	return $values;
}
