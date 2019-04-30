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
 * @return mixed
 */
function do_conditions( $conditions )
{
	$return = null;

	foreach ( $conditions as $group ) 
	{
		foreach ( $group as $condition ) 
		{
			$return = do_condition( $condition['param'], $condition['operator'], $condition['value'] );

			if ( isset( $return ) && ! $return ) break;
		}

		if ( isset( $return ) && $return ) break;
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
 * @return mixed
 */
function do_condition( $param, $operator, $value )
{
	return apply_filters( "wdc/do_condition/param=$param", null, $operator, $value, $param );
}

/**
 * Do operator
 *
 * @param string $operator
 * @param mixed  $a
 * @param mixed  $b
 *
 * @return mixed
 */
function do_operator( $operator, $a, $b )
{
	return apply_filters( "wdc/do_operator/operator=$operator", null, $a, $b, $operator );
}
