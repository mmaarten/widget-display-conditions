<?php 
/**
 * Conditions API
 */

namespace wdc;

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

function do_condition( $param, $operator, $value )
{
	return apply_filters( "wdc/do_condition/param=$param", true, $operator, $value, $param );
}

function do_operator( $operator, $a, $b )
{
	return apply_filters( "wdc/do_operator/operator=$operator", true, $a, $b, $operator );
}
