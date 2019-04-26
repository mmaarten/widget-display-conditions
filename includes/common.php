<?php 
/**
 * Common
 */

namespace wdc;

/**
 * Get instance
 *
 * @return stdClass
 */
function get_instance()
{
	static $wdc = null;

	if ( ! isset( $wdc ) ) 
	{
		$wdc = new \stdClass();
	}

	return $wdc;
}

/**
 * Get Version
 *
 * @return string
 */
function get_version()
{
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	$data = get_plugin_data( WDC_FILE, false, false );

	return $data['Version'];
}

/**
 * Admin notice
 *
 * @param string $message
 * @param string $type
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices
 */
function admin_notice( $message, $type = 'info' )
{
	$class = sprintf( 'notice notice-%s', sanitize_html_class( $type ) );

	printf( '<div class="%s"><p>%s</p></div>', esc_attr( $class ), $message );
}

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
			$return .= sprintf( '<option value="%s"%s>%s</option>', 
				esc_attr( $item['id'] ), selected( $item['selected'], true, false ), esc_html( $item['text'] ) );
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
function get_post_field_items( $post_type )
{
	$post_types = (array) $post_type;
	$is_group   = count( $post_types ) > 1;

	foreach ( $post_types as $post_type ) 
	{
		// Get post type object

		$post_type = get_post_type_object( $post_type );

		if ( ! $post_type ) continue;

		// Get posts

		if ( is_post_type_hierarchical( $post_type->name ) ) 
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

		foreach ( $posts as $post ) 
		{
			$text = trim( $post->post_title ) ? $post->post_title : $post->ID;
			$pad  = '';

			if ( is_post_type_hierarchical( $post_type->name ) ) 
			{
				$ancestors = get_post_ancestors( $post );
				$pad = str_repeat( '&nbsp;', count( $ancestors ) * 3 );
			}

			$group['children'][ $post->ID ] = array
			(
				'id'   => $post->ID,
				'html' => $pad . esc_html( $text ),
			);
		}

		$items[ $group['id'] ] = $group;
	}

	if ( ! $is_group && $items ) 
	{
		$first = array_keys( $items )[0];

		$items = $items[ $first ]['children'];
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
function get_term_field_items( $taxonomy )
{
	$taxonomies = (array) $taxonomy;
	$is_group   = count( $taxonomies ) > 1;

	$items = array();
	
	foreach ( $taxonomies as $taxonomy ) 
	{
		$taxonomy = get_taxonomy( $taxonomy );

		if ( ! $taxonomy ) continue;

		// Get terms

		if ( is_taxonomy_hierarchical( $taxonomy->name ) ) 
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

		foreach ( $terms as $term ) 
		{
			$text = trim( $term->name ) ? $term->name : $term->term_id;
			$pad  = '';

			if ( is_taxonomy_hierarchical( $taxonomy->name ) ) 
			{
				$ancestors = get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' );
				$pad = str_repeat( '&nbsp;', count( $ancestors ) * 3 );
			}

			$group['children'][ $term->term_id ] = array
			(
				'id'   => $term->term_id,
				'html' => $pad . esc_html( $text ),
			);
		}

		$items[ $group['id'] ] = $group;
	}

	if ( ! $is_group && $items ) 
	{
		$first = array_keys( $items )[0];

		$items = $items[ $first ]['children'];
	}

	return $items;
}

/**
 * Get page template field items
 *
 * @return array
 */
function get_page_template_field_items()
{
	$items = array
	(
		array
		(
			'id'   => 'default',
			'text' => __( 'Default', 'wdc' ),
		),
	);

	$page_templates = get_page_templates();

	foreach ( $page_templates as $template_name => $template_file ) 
	{
		$items[ $template_file ] = array
		(
			'id'   => $template_file,
			'text' => $template_name,
		);
	}

	return $items;
}
