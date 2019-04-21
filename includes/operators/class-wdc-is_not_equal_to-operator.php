<?php 

namespace wdc;

class Is_Not_Equal_To_Operator extends Operator
{
	public function __construct()
	{
		parent::__construct( '!=', __( 'Is not equal to', 'wdc' ) );
	}

	public function apply( $a, $b )
	{
		return $a != $b;
	}
}

register_operator( 'wdc\Is_Not_Equal_To_Operator' );
