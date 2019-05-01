<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.
/**
 * Field functions
 */

/**
 * Get condition param field items
 *
 * @return array
 */
function wdc_get_condition_param_field_items()
{
	$conditions = wdc_get_conditions();
	$categories = wdc_get_condition_categories();

	uasort( $categories, 'wdc_sort_order' );

	$items = array();

	foreach ( $categories as $category ) 
	{
		$category_conditions = wp_filter_object_list( $conditions, array( 'category' => $category['id'] ) );

		if ( ! $category_conditions ) continue;

		uasort( $category_conditions, 'wdc_sort_order' );

		$group = array
		(
			'id'       => $category['id'],
			'text'     => $category['title'],
			'children' => array(),
		);

		foreach ( $category_conditions as $condition ) 
		{
			$group['children'][ $condition->id ] = array
			(
				'id'   => $condition->id,
				'text' => $condition->title,
			);
		}

		$items[ $group['id'] ] = $group;
	}

	$items = apply_filters( "wdc/condition_param_field_items", $items );

	return $items;
}

/**
 * Get condition operator field items
 *
 * @param string $condition_id
 *
 * @return mixed
 */
function wdc_get_condition_operator_field_items( $condition_id )
{
	$condition = wdc_get_condition( $condition_id );

	if ( ! $condition ) return null;

	$operators = wdc_get_operator_objects( $condition->operators );

	uasort( $operators, 'wdc_sort_order' );

	$items = array();

	foreach ( $operators as $operator ) 
	{
		$items[ $operator->id ] = array
		(
			'id'   => $operator->id,
			'text' => $operator->title,
		);
	}

	$items = apply_filters( "wdc/condition_operator_field_items/condition={$condition->id}", $items, $condition );
	$items = apply_filters( "wdc/condition_operator_field_items"                           , $items, $condition );

	return $items;
}

/**
 * Get condition value field items
 *
 * @param string $condition_id
 *
 * @return mixed
 */
function wdc_get_condition_value_field_items( $condition_id )
{
	$condition = wdc_get_condition( $condition_id );

	if ( ! $condition ) return null;

	$items = array();
	$items = apply_filters( "wdc/condition_value_field_items/condition={$condition->id}", $items, $condition );
	$items = apply_filters( "wdc/condition_value_field_items"                           , $items, $condition );

	return $items;
}

/**
 * Get post field items
 *
 * @param mixed $post_type
 * @param bool  $labels
 *
 * @return array
 */
function wdc_get_post_field_items( $post_type, $labels = false )
{
	$post_types = (array) $post_type;

	$items = array();

	foreach ( $post_types as $post_type ) 
	{
		$post_type = get_post_type_object( $post_type );

		if ( ! $post_type ) continue;

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

		if ( ! $posts ) continue;

		$group = array
		(
			'id'       => $post_type->name,
			'text'     => $post_type->labels->singular_name,
			'children' => array(),
		);

		foreach ( $posts as $post ) 
		{
			$ancestors = get_post_ancestors( $post );

			$text = trim( $post->post_title ) ? $post->post_title : $post->ID;
			$pad  = str_repeat( '&nbsp;', count( $ancestors ) * 3 );

			$group['children'][ $post->ID ] = array
			(
				'id'   => $post->ID,
				'html' => $pad . esc_html( $text ),
			);
		}

		$items[ $group['id'] ] = $group;
	}

	if ( false == $labels ) 
	{
		$_items = array();

		foreach ( $items as $group ) 
		{
			$_items += $group['children'];
		}

		$items = $_items;
	}

	return $items;
}

/**
 * Get term field items
 *
 * @param mixed $taxonomy
 * @param bool  $labels
 *
 * @return array
 */
function wdc_get_term_field_items( $taxonomy, $labels = false )
{
	$taxonomies = (array) $taxonomy;

	$items = array();

	foreach ( $taxonomies as $taxonomy ) 
	{
		$taxonomy = get_taxonomy( $taxonomy );

		if ( ! $taxonomy ) continue;

		$terms = get_terms( array
		(
			'taxonomy'     => $taxonomy->name,
			'hierarchical' => $taxonomy->hierarchical,
			'orderby'      => 'parent name',
			'order'        => 'ASC',
			'number'       => WDC_MAX_FIELD_ITEMS,
		));

		if ( ! $terms ) continue;

		$group = array
		(
			'id'       => $taxonomy->name,
			'text'     => $taxonomy->labels->singular_name,
			'children' => array(),
		);

		foreach ( $terms as $term ) 
		{
			$ancestors = get_ancestors( $term->term_id, $taxonomy->name, 'taxonomy' );

			$text = trim( $term->name ) ? $term->name : $term->term_id;
			$pad  = str_repeat( '&nbsp;', count( $ancestors ) * 3 );

			$group['children'][ $term->term_id ] = array
			(
				'id'   => $term->term_id,
				'html' => $pad . esc_html( $text ),
			);
		}

		$items[ $group['id'] ] = $group;
	}

	if ( false == $labels ) 
	{
		$_items = array();

		foreach ( $items as $group ) 
		{
			$_items += $group['children'];
		}

		$items = $_items;
	}

	return $items;
}

/**
 * Get dropdown options
 *
 * @param array $items
 *
 * @return string
 */
function wdc_get_dropdown_options( $items )
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
			$return .= wdc_get_dropdown_options( $item['children'] );
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
 * Prepare field items json
 *
 * Make sure array keys are nummeric indexes.
 *
 * @param array $items
 *
 * @return array
 */
function wdc_prepare_field_items_json( $items )
{
	$return = array();

	foreach ( $items as $item ) 
	{
		$i = count( $return );

		$return[] = $item;

		if ( isset( $item['children'] ) ) 
		{
			$return[ $i ]['children'] = wdc_prepare_field_items_json( $item['children'] );
		}
	}

	return $return;
}
