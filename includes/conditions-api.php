<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.
/**
 * Condition functions
 */

/**
 * Do conditions
 *
 * @param array $conditions
 *
 * @return mixed
 */
function wdc_do_conditions( $conditions )
{
	$return = null;

	foreach ( $conditions as $group ) 
	{
		foreach ( $group as $condition ) 
		{
			$return = wdc_do_condition( $condition['param'], $condition['operator'], $condition['value'] );

			if ( isset( $return ) && ! $return ) break;
		}

		if ( isset( $return ) && $return ) break;
	}

	return $return;
}

/**
 * Do condition
 *
 * @param string $condition
 * @param string $operator_id
 * @param mixed  $value
 *
 * @return mixed
 */
function wdc_do_condition( $condition, $operator, $value )
{
	$return = null;
	$return = apply_filters( "wdc/do_condition/condition=$condition", $return, $operator, $value, $condition );
	$return = apply_filters( "wdc/do_condition"                     , $return, $operator, $value, $condition );

	return $return;
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
function wdc_do_operator( $operator, $a, $b )
{
	$return = null;
	$return = apply_filters( "wdc/do_operator/operator=$operator", $return, $a, $b, $operator );
	$return = apply_filters( "wdc/do_operator"                   , $return, $a, $b, $operator );

	return $return;
}
