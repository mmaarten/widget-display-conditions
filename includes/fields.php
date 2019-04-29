<?php 
/**
 * Fields
 */

namespace wdc;

/**
 * Get condition param field items
 *
 * @return array
 */
function get_condition_param_field_items()
{
	$conditions = get_conditions();
	$categories = get_condition_categories();

	uasort( $categories, __NAMESPACE__ . '\sort_order' );

	$items = array();

	foreach ( $categories as $category ) 
	{
		$category_conditions = wp_filter_object_list( $conditions, array( 'category' => $category['id'] ) );

		if ( ! $category_conditions ) continue;

		uasort( $category_conditions, __NAMESPACE__ . '\sort_order' );

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

	$items = apply_filters( 'wdc/condition_param_field_items', $items );

	return $items;
}

/**
 * Get condition operator field items
 *
 * @param string $condition_id
 *
 * @return mixed
 */
function get_condition_operator_field_items( $condition_id )
{
	$condition = get_condition( $condition_id );

	if ( ! $condition ) 
	{
		return null;
	}

	$operators = get_operator_objects( $condition->operators );

	uasort( $operators, __NAMESPACE__ . '\sort_order' );

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
function get_condition_value_field_items( $condition_id )
{
	$condition = get_condition( $condition_id );

	if ( ! $condition ) 
	{
		return null;
	}

	$items = array();
	$items = apply_filters( "wdc/condition_value_field_items/condition={$condition->id}", $items, $condition );
	$items = apply_filters( "wdc/condition_value_field_items"                           , $items, $condition );

	return $items;
}

/**
 * Get condition field items
 *
 * @param string $condition_id
 *
 * @return mixed
 */
function get_condition_field_items( $condition_id )
{
	$condition = get_condition( $condition_id );

	if ( ! $condition ) 
	{
		return null;
	}

	$items = array
	(
		'operator' => get_condition_operator_field_items( $condition->id ),
		'value'    => get_condition_value_field_items( $condition->id ),
	);

	$items = apply_filters( "wdc/condition_field_items/condition={$condition->id}", $items, $condition );
	$items = apply_filters( "wdc/condition_field_items"                           , $items, $condition );

	return $items;
}

function get_condition_field_items_json( $param )
{
	$items = get_condition_field_items( $param );

	if ( ! isset( $items ) ) 
	{
		return null;
	}

	foreach ( $items as $key => $value ) 
	{
		$items[ $key ] = prepare_field_items_json( $value );
	}

	return $items;
}

/**
 * Prepare field items json
 *
 * JSON sorts arrays keys alphabetically.
 *
 * e.g. $items = [ 'b' => 1, 'a' => 2 ]; becomes : [ 'a' => 2, 'b' => 1 ].
 *
 * @param array $items
 */
function prepare_field_items_json( $items )
{
	$sanitized = array();

    foreach ( $items as $item )
    {
    	$i = count( $sanitized );

    	$sanitized[] = $item;

    	if ( isset( $item['children'] ) ) 
    	{
    		$sanitized[ $i ]['children'] = prepare_field_items_json( $item['children'] );
    	}
    }

    return $sanitized;
}

