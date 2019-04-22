<?php
/**
 * Common
 */

namespace wdc;

function sort_order( $a, $b )
{
	if ( ! is_array( $a ) ) $a = get_object_vars( $a );
	if ( ! is_array( $b ) ) $b = get_object_vars( $b );

	if ( $a['order'] == $b['order'] ) 
	{
        return 0;
    }

    return ( $a['order'] < $b['order'] ) ? -1 : 1;
}

// info|warning|error
function admin_notice( $message, $type = 'info' ) 
{
	$class = sprintf( 'notice notice-%s', sanitize_html_class( $type ) );

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message ); 
}

/**
 * Get Dropdown Options
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
		$item = wp_parse_args( $item, array( 'id' => '', 'text' => '', 'selected' => false ) );

		if ( array_key_exists( 'children', $item ) ) 
		{
			if ( is_array( $item['children'] ) ) 
			{
				$return .= sprintf( '<optgroup label="%s">', esc_attr( $item['text'] ) );
				$return .= get_dropdown_options( $item['children'] );
				$return .= '</optgroup>';
			}
		}

		else
		{
			$return .= sprintf( '<option value="%s"%s>%s</option>>', 
				esc_attr( $item['id'] ), $item['selected'] ? ' selected' : '', esc_html( $item['text'] ) );
		}
	}

	return $return;
}

function get_post_field_items( $post_type )
{
	$post_types = (array) $post_type;
	$is_group   = count( $post_types ) > 1;

	$counter = 0;

	foreach ( $post_types as $post_type ) 
	{
		// Get post type object

		$post_type = get_post_type_object( $post_type );

		if ( ! $post_type ) continue;

		// Get posts

		$is_hierarchical = is_post_type_hierarchical( $post_type->name );

		if ( $is_hierarchical ) 
		{
			$posts = get_pages( array
			(
				'post_type'    => $post_type->name,
				'hierarchical' => true,
				'post_status'  => 'publish',
				'sort_column'  => 'post_title',
				'sort_order'   => 'asc',
				'number'       => WDC_MAX_NUMBER_POSTS,
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
				'numberposts' => WDC_MAX_NUMBER_POSTS,
			));
		}

		// Check if posts

		if ( ! $posts ) continue;

		// Create items

		$group = array
		(
			'id'       => $post_type->name,
			'text'     => $post_type->labels->singular_name,
			'children' => array(),
		);

		foreach ( $posts as $post ) 
		{
			$text = $post->post_title;
			$pad  = '';

			if ( '' == trim( $text ) ) 
			{
				$text = $post->ID;
			}

			// Show hierarchy

			if ( $is_hierarchical ) 
			{
				$depth = count( get_post_ancestors( $post ) );
				$pad   = str_repeat( '&nbsp;', $depth * 3 );
			}

			//

			$group['children'][] = array
			(
				'id'   => $post->ID,
				//'text' => "{$text_before}{$text}",
				'html' => $pad . esc_html( $text ),
			);
		}

		$items[] = $group;
	}

	if ( ! $is_group && $items ) 
	{
		$items = $items[0]['children'];
	}

	return $items;
}

function get_term_field_items( $taxonomy )
{
	$taxonomies = (array) $taxonomy;
	$is_group   = count( $taxonomies ) > 1;

	$items = array();

	foreach ( $taxonomies as $taxonomy ) 
	{
		// Get taxonomy object

		$taxonomy = get_taxonomy( $taxonomy );

		if ( ! $taxonomy ) continue;

		$is_hierarchical = is_taxonomy_hierarchical( $taxonomy->name );

		// Get terms

		if ( $is_hierarchical ) 
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

		// Checks if terms.

		if ( ! count( $terms ) ) continue;

		// Create items

		$group = array
		(
			'text'     => $taxonomy->labels->singular_name,
			'children' => array(),
		);

		// Creates `<option>` elements.

		foreach ( $terms as $term ) 
		{
			$text = $term->name;
			$text_before = '';

			if ( '' == trim( $text ) ) 
			{
				$text = $term->term_id;
			}

			// Show hierarchy

			if ( $is_hierarchical ) 
			{
				$ancestors = get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' );

				$text_before = str_repeat( '–', count( $ancestors ) ) . ' ';
			}

			//

			$group['children'][] = array
			(
				'id'   => $term->term_id,
				'text' => "{$text_before}{$text}"
			);
		}

		$items[] = $group;
	}

	if ( ! $is_group && $items ) 
	{
		$items = $items[0]['children'];
	}

	return $items;
}

function get_user_field_items( $args = null )
{
	$defaults = array
	(
		'role' => '',
		'who'  => ''
	);

	$args = wp_parse_args( $args, $defaults );

	$choices = array();

	$users = get_users( array
	(
		'role'    => $args['role'],
		'who'     => $args['who'],
		'orderby' => 'display_name',
		'order'   => 'ASC'
	));

	foreach ( $users as $user ) 
	{
		$choices[ $user->ID ] = array
		(
			'id'   => $user->ID,
			'text' => $user->display_name,
		);
	}

	return $choices;
}

function get_page_template_field_items()
{
	$items['default'] = array
	(
		'id'   => 'default',
		'text' => __( 'Default', 'wdc' )
	);

	$page_templates = get_page_templates();

	foreach ( $page_templates as $template_name => $template_file ) 
	{
		$items[ $template_name ] = array
		(
			'id'   => $template_file,
			'text' => $template_name
		);
	}

	return $items;
}
