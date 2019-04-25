<?php 
/**
 * Conditions API
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
function do_condition( $param, $operator, $value )
{
	return apply_filters( "wdc/do_condition/param=$param", true, $operator, $value, $param );
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
	return apply_filters( "wdc/do_operator/operator=$operator", true, $a, $b, $operator );
}
