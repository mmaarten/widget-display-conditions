<?php 
/**
 * Fields
 */

namespace wdc;

/**
 * Get param field items
 *
 * @return array
 */
function get_param_field_items()
{
	$params     = get_params();
	$categories = get_param_categories();

	uasort( $categories, 'wdc\sort_order' );

	$items = array();

	foreach ( $categories as $category ) 
	{
		$category_params = wp_filter_object_list( $params, array( 'category' => $category['id'] ) );

		if ( ! $category_params ) continue;

		uasort( $category_params, 'wdc\sort_order' );

		$group = array
		(
			'id'       => $category['id'],
			'text'     => $category['title'],
			'children' => array(),
		);

		foreach ( $category_params as $param ) 
		{
			$group['children'][ $param['id'] ] = array
			(
				'id'   => $param['id'],
				'text' => $param['title'],
			);
		}

		$items[ $group['id'] ] = $group;
	}

	return $items;
}

/**
 * Get operator field items
 *
 * @param string $param_id
 *
 * @return mixed
 */
function get_operator_field_items( $param_id )
{
	$param = get_param( $param_id );

	if ( ! $param ) return null;

	$operators = get_operator_objects( $param['operators'] );

	uasort( $operators, 'wdc\sort_order' );
	
	$items = array();

	foreach ( $operators as $operator ) 
	{
		$items[ $operator['id'] ] = array
		(
			'id'   => $operator['id'],
			'text' => $operator['title'],
		);
	}

	return $items;
}

/**
 * Get value field items
 *
 * @param string $param_id
 *
 * @return mixed
 */
function get_value_field_items( $param_id )
{
	$param = get_param( $param_id );

	if ( ! $param ) return null;

	return apply_filters( "wdc/value_field_items/param={$param['id']}", array(), $param['id'] );
}

/**
 * Get condition field items
 *
 * @param string $param_id
 *
 * @return mixed
 */

function get_condition_field_items( $param_id )
{
	$param = get_param( $param_id );

	if ( ! $param ) return null;

	return array
	(
		'operator' => get_operator_field_items( $param['id'] ),
		'value'    => get_value_field_items( $param['id'] ),
	);
}
