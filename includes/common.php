<?php 
/**
 * Common
 */

namespace wdc;

/**
 * Sort order
 *
 * @param mixed $a
 * @param mixed $b
 *
 * @return int
 */
function sort_order( $a, $b )
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
 * Get dropdown options
 *
 * @param array $items
 *
 * @return string
 */
function get_dropdown_options( $items )
{
	$return = '';

	foreach ( $items as $item ) 
	{
		$item = wp_parse_args( $item, array
		( 
			'id'       => '', 
			'text'     => '', 
			'selected' => false,
		));

		if ( isset( $item['children'] ) ) 
		{
			$return .= sprintf( '<optgroup label="%s">', esc_attr( $item['text'] ) );
			$return .= get_dropdown_options( $item['children'] );
			$return .= '</optgroup>';
		}

		else
		{
			$text = isset( $item['html'] ) ? $item['html'] : esc_html( $item['text'] );

			$return .= sprintf( '<option value="%s"%s>%s</option>', 
				esc_attr( $item['id'] ), selected( $item['selected'], true, false ), $text );
		}
	}

	return $return;
}

/**
 * Get post field items
 *
 * @param mixed $post_type
 *
 * @return array
 */
function get_post_field_items( $post_type = 'post', $show_labels = false )
{
	$post_types = (array) $post_type;

	$items = array();

	foreach ( $post_types as $post_type ) 
	{
		// Get post type object

		$post_type = get_post_type_object( $post_type );

		if ( ! $post_type ) continue;

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
				'number'       => WDC_MAX_NUMBERPOSTS,
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
				'numberposts' => WDC_MAX_NUMBERPOSTS,
			));
		}

		// stop when no posts

		if ( ! $posts ) continue;

		// Get items

		$group = array
		(
			'id'       => $post_type->name,
			'text'     => $post_type->labels->singular_name,
			'children' => array(),
		);

		if ( $show_labels ) 
		{
			$items[ $group['id'] ] = $group;

			$parent = &$items[ $group['id'] ]['children'];
		}

		else
		{
			$parent = &$items;  
		}

		foreach ( $posts as $post ) 
		{
			$text = trim( $post->post_title ) ? $post->post_title : $post->ID;
			$pad  = '';

			if ( $post_type->hierarchical ) 
			{
				$ancestors = get_post_ancestors( $post );
				$pad = str_repeat( '&nbsp;', count( $ancestors ) * 3 );
			}

			$parent[ $post->ID ] = array
			(
				'id'   => $post->ID,
				'html' => $pad . esc_html( $text ),
			);
		}
	}

	return $items;
}

/**
 * Get term field items
 *
 * @param mixed $taxonomy
 *
 * @return array
 */
function get_term_field_items( $taxonomy, $show_labels = false )
{
	$taxonomies = (array) $taxonomy;

	$items = array();
	
	foreach ( $taxonomies as $taxonomy ) 
	{
		$taxonomy = get_taxonomy( $taxonomy );

		if ( ! $taxonomy ) continue;

		// Get terms

		if ( $taxonomy->hierarchical ) 
		{
			$terms = get_categories( array
			(
				'taxonomy' => $taxonomy->name,
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

		if ( ! $terms ) continue;

		// Get items

		$group = array
		(
			'id'       => $taxonomy->name,
			'text'     => $taxonomy->labels->singular_name,
			'children' => array()
		);

		if ( $show_labels ) 
		{
			$items[ $group['id'] ] = $group;

			$parent = &$items[ $group['id'] ]['children'];
		}

		else
		{
			$parent = &$items;  
		}

		foreach ( $terms as $term ) 
		{
			$text = trim( $term->name ) ? $term->name : $term->term_id;
			$pad  = '';

			if ( $taxonomy->hierarchical ) 
			{
				$ancestors = get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' );
				$pad = str_repeat( '&nbsp;', count( $ancestors ) * 3 );
			}

			$parent[ $term->term_id ] = array
			(
				'id'   => $term->term_id,
				'html' => $pad . esc_html( $text ),
			);
		}
	}

	return $items;
}
