<?php 

namespace wdc;

class Is_Equal_To_Operator extends Operator
{
	public function __construct()
	{
		parent::__construct( '==', __( 'Is equal to', 'wdc' ) );
	}

	public function apply( $a, $b )
	{
		return $a == $b;
	}
}

register_operator( 'wdc\Is_Equal_To_Operator' );
