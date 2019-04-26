<?php 
/**
 * Conditions API
 */

namespace wdc;

function do_conditions( $conditions )
{
	$result = true;

	foreach ( $conditions as $group ) 
	{
		foreach ( $group as $condition ) 
		{
			$result = do_condition( $condition['param'], $condition['operator'], $condition['value'] );

			if ( ! $result ) break;
		}

		if ( $result ) break;
	}

	return $result;
}

function do_condition( $param, $operator, $value )
{
	return apply_filters( "wdc/do_condition/param=$param", true, $operator, $value, $param );
}

function do_operator( $operator, $a, $b )
{
	return apply_filters( "wdc/do_operator/operator=$operator", true, $a, $b, $operator );
}

function sanitize_condition_result( $result )
{
	return isset( $result ) ? (bool) $result : $result;
}
