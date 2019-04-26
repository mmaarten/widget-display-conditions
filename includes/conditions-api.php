<?php 
/**
 * Conditions API
 */

namespace wdc;

function do_conditions( $conditions )
{
	$result = null;

	foreach ( $conditions as $group ) 
	{
		foreach ( $group as $condition ) 
		{
			$result = do_condition( $condition['param'], $condition['operator'], $condition['value'] );

			if ( isset( $result ) && ! $result ) break;
		}

		if ( isset( $result ) && $result ) break;
	}

	return $result;
}

function do_condition( $param, $operator, $value )
{
	$result = null;
	$result = apply_filters( "wdc/do_condition/param=$param", $result, $operator, $value, $param );
	$result = apply_filters( "wdc/do_condition"             , $result, $operator, $value, $param );

	return sanitize_condition_result( $result );
}

function do_operator( $operator, $a, $b )
{
	$result = null;
	$result = apply_filters( "wdc/do_operator/operator=$operator", $result, $a, $b, $operator );
	$result = apply_filters( "wdc/do_operator"                   , $result, $a, $b, $operator );

	return sanitize_condition_result( $result );
}

function sanitize_condition_result( $result )
{
	return isset( $result ) ? (bool) $result : $result;
}
