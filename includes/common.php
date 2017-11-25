<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

/**
 * Get Widget Instance
 *
 * Returns an array containing widget settings.
 *
 * @param string $widget_id The widget id.
 * @return array Widget settings.
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

function wdc_get_widget_rules( $widget_id )
{
	$instance = wdc_get_widget_instance( $widget_id );

	if ( ! $instance ) 
	{
		return null;
	}

	if ( isset( $instance['wdc_rules'] ) ) 
	{
		return (array) $instance['wdc_rules'];
	}

	return array();
}

function wdc_post_choices( $args = null )
{
	$default = array
	(
		'post_type' => 'post',
		'group'     => false
	);

	$args = wp_parse_args( $args, $defaults );

	$choices = array();

	$post_types = (array) $args['post_type'];

	foreach ( $post_types as $post_type ) 
	{
		$post_type = get_post_type_object( $post_type );

		if ( ! $post_type ) 
		{
			continue;
		}

		$is_post_type_hierarchical = is_post_type_hierarchical( $post_type->name );

		if ( $is_post_type_hierarchical ) 
		{
			$posts = get_pages( array
			(
				'post_type'    => $post_type->name,
				'hierarchical' => $args['hierarchical'],
				'numberposts'  => WDC_MAX_NUMBERPOSTS
			));
		}

		else
		{
			$posts = get_posts( array
			(
				'post_type'   => $post_type->name,
				'orderby'     => 'post_title',
				'order'       => 'ASC',
				'numberposts' => WDC_MAX_NUMBERPOSTS
			));
		}

		if ( ! count( $posts ) ) 
		{
			continue;
		}

		if ( $args['group'] ) 
		{
			// Creates `<optgroup>`.

			$choices[ $post_type->name ] = array
			(
				'text'     => $post_type->labels->singular_name,
				'children' => array()
			);

			$parent = &$choices[ $post_type->name ]['children'];
		}

		else
		{
			$parent = &$choices;
		}

		// Creates `<option>` elements.

		foreach ( $posts as $post ) 
		{
			$prefix = '';

			// Shows hierarchy

			if ( $is_post_type_hierarchical ) 
			{
				$ancestors = get_ancestors( $post->ID, $post->post_type, 'post_type' );

				$prefix = str_repeat( '–', count( $ancestors ) ) . ' ';
			}

			//

			$parent[] = array
			(
				'id'   => $post->ID,
				'text' => $prefix . $post->post_title
			);
		}
	}

	return $choices;
}

function wdc_term_choices( $args )
{
	$defaults = array
	(
		'taxonomy' => '',
		'group'    => false
	);

	$args = wp_parse_args( $args, $defaults );

	$choices = array();

	if ( $args['taxonomy'] ) 
	{
		$taxonomies = (array) $args['taxonomy'];
	}

	else
	{
		$taxonomies = get_taxonomies( array( 'public' => true ), 'names' );
	}

	foreach ( $taxonomies as $taxonomy_name ) 
	{
		$taxonomy = get_taxonomy( $taxonomy_name );

		if ( ! $taxonomy ) 
		{
			continue;
		}

		$is_taxonomy_hierarchical = is_taxonomy_hierarchical( $taxonomy->name );

		if ( $is_taxonomy_hierarchical ) 
		{
			$terms = get_categories( array
			(
				'taxonomy' => $taxonomy->name
			));
		}

		else
		{
			$terms = get_terms( array
			(
				'taxonomy' => $taxonomy->name,
				'orderby'  => 'name',
				'order'    => 'ASC'
			));
		}

		// Checks if posts.

		if ( ! count( $terms ) ) 
		{
			continue;
		}

		if ( $args['group'] ) 
		{
			// Creates `<optgroup>`.

			$choices[ $taxonomy->name ] = array
			(
				'text'     => $taxonomy->labels->singular_name,
				'children' => array()
			);

			$parent = &$choices[ $taxonomy->name ]['children'];
		}

		else
		{
			$parent = &$choices;
		}

		// Creates `<option>` elements.

		foreach ( $terms as $term ) 
		{
			$prefix = '';

			// Shows hierarchy

			if ( $is_taxonomy_hierarchical ) 
			{
				$ancestors = get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' );

				$prefix = str_repeat( '–', count( $ancestors ) ) . ' ';
			}

			//

			$parent[] = array
			(
				'id'   => $term->term_id,
				'text' => $prefix. $term->name
			);
		}
	}

	return $choices;
}
