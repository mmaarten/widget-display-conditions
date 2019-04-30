<?php 
/**
 * Conditions
 */

namespace wdc;

$wdc_conditions           = array();
$wdc_condition_categories = array();

/**
 * Register condition
 *
 * @param mixed $condition
 */
function register_condition( $condition )
{
	if ( ! $condition instanceof Condition ) 
	{
		$condition = new $condition;
	}

	$GLOBALS['wdc_conditions'][ $condition->id ] = $condition;
}

/**
 * Unregister condition
 *
 * @param string $condition_id
 */
function unregister_condition( $condition_id )
{
	unset( $GLOBALS['wdc_conditions'][ $condition_id ] );
}

/**
 * Get conditions
 *
 * @return array
 */
function get_conditions()
{
	return $GLOBALS['wdc_conditions'];
}

/**
 * Get condition
 *
 * @param string $condition_id
 *
 * @return mixed
 */
function get_condition( $condition_id )
{
	$conditions = get_conditions();

	if ( isset( $conditions[ $condition_id ] ) ) 
	{
		return $conditions[ $condition_id ];
	}

	return null;
}

/**
 * Add condition category
 *
 * @param string $id
 * @param string $title
 * @param array  $args
 */
function add_condition_category( $id, $title, $args = array() )
{
	$args = wp_parse_args( $args, array
	(
		'order' => 10,
	));

	$category = array
	(
		'id'    => $id,
		'title' => $title,
		'order' => (int) $args['order'],
	);
	
	$category = apply_filters( 'wdc/condition_category', $category );

	return $GLOBALS['wdc_condition_categories'][ $category['id'] ] = $category;
}

/**
 * Remove condition category
 *
 * @param string $category_id
 *
 * @return mixed
 */
function remove_condition_category( $category_id )
{
	unset( $GLOBALS['wdc_condition_categories'][ $category_id ] );
}
/**
 * Get condition categories
 *
 * @return array
 */
function get_condition_categories()
{
	return $GLOBALS['wdc_condition_categories'];
}

/**
 * Get condition category
 *
 * @param string $category_id
 *
 * @return mixed
 */
function get_condition_category( $category_id )
{
	$categories = get_condition_categories();

	if ( isset( $categories[ $category_id ] ) ) 
	{
		return $categories[ $category_id ];
	}

	return null;
}
