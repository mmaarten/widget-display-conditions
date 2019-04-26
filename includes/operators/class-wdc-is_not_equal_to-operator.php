<?php 
/**
 * Is equal to operator
 */

namespace wdc;

class Is_Not_Equal_To_Operator extends Operator
{
	public function __construct()
	{
		parent::__construct( '!=', __( 'Is not equal to', 'wdc' ), array
		(
			'order' => 20,
		));
	}

	public function apply( $return, $a, $b )
	{
		return $a != $b;
	}
}

register_operator( 'wdc\Is_Not_Equal_To_Operator' );
