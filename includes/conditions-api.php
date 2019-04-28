<?php 
/**
 * Conditions
 */

namespace wdc;

/**
 * Do conditions
 *
 * @param array $conditions
 *
 * @return bool
 */
function do_conditions( $conditions )
{
	$return = true;

	foreach ( $conditions as $group ) 
	{
		foreach ( $group as $condition ) 
		{
			$return = do_condition( $condition['param'], $condition['operator'], $condition['value'] );

			if ( ! $return ) break;
		}

		if ( $return ) break;
	}

	return $return;
}

/**
 * Do condition
 *
 * @param string $param
 * @param string $operator
 * @param mixed  $value
 *
 * @return bool
 */
function do_condition( $condition, $operator, $value )
{
	$return = null;
	$return = apply_filters( "wdc/do_condition/condition=$param", $return, $operator, $value, $param );
	$return = apply_filters( "wdc/do_condition"                 , $return, $operator, $value, $param );

	return isset( $return ) ? (bool) $return : $return;
}

/**
 * Do operator
 *
 * @param string $operator
 * @param mixed  $a
 * @param mixed  $b
 *
 * @return bool
 */
function do_operator( $operator, $a, $b )
{
	$return = null;
	$return = apply_filters( "wdc/do_operator/operator=$operator", $return, $a, $b, $operator );
	$return = apply_filters( "wdc/do_operator"                   , $return, $a, $b, $operator );

	return isset( $return ) ? (bool) $return : $return;
}
