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
	$conditions = get_conditions();

	uasort( $conditions, 'wdc\sort_order' );

	$items = array();

	foreach ( $conditions as $condition ) 
	{
		$items[ $condition->id ] = array
		(
			'id'   => $condition->id,
			'text' => $condition->title,
		);
	}

	return $items;
}

/**
 * Get condition field items
 *
 * @param string $param
 *
 * @return mixed
 */

function get_condition_field_items( $param )
{
	return array
	(
		'operator' => apply_filters( "wdc/operator_field_items/param=$param", array(), $param ),
		'value'    => apply_filters( "wdc/value_field_items/param=$param", array(), $param ),
	);
}
