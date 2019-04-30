<?php 
/**
 * Operators
 */

namespace wdc;

$wdc_operators = array();

/**
 * Register operator
 *
 * @param mixed $operator
 */
function register_operator( $operator )
{
	if ( ! $operator instanceof Operator ) 
	{
		$operator = new $operator;
	}

	$GLOBALS['wdc_operators'][ $operator->id ] = $operator;
}

/**
 * Unregister operator
 *
 * @param string $operator_id
 */
function unregister_operator( $operator_id )
{
	unset( $GLOBALS['wdc_operators'][ $operator_id ] );
}

/**
 * Get operators
 *
 * @return array
 */
function get_operators()
{
	return $GLOBALS['wdc_operators'];
}

/**
 * Get operator
 *
 * @param string $operator_id
 *
 * @return mixed
 */
function get_operator( $operator_id )
{
	$operators = get_operators();
	
	if ( isset( $operators[ $operator_id ] ) ) 
	{
		return $operators[ $operator_id ];
	}

	return null;
}

/**
 * Get operator objects
 *
 * @param array $operator_ids
 *
 * @return array
 */
function get_operator_objects( $operator_ids )
{
	return array_intersect_key( get_operators(), array_flip( (array) $operator_ids ) );
}
