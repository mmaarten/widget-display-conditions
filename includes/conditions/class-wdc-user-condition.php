<?php 

namespace wdc;

class User_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'user', __( 'User', 'wdc' ), array
		(
			'category' => 'user',
			'order'    => 1000,
		));
	}

	public function get_value_field_items()
	{
		return get_user_field_items();
	}

	public function apply( $operator, $value )
	{
		return apply_operator( $operator, get_current_user_id(), $value );
	}
}

register_condition( 'wdc\User_Condition' );
