<?php 

namespace wdc;

class User_Logged_In_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'user_logged_in', __( 'User Logged In', 'wdc' ), array
		(
			'category' => 'user',
		));
	}

	public function get_value_field_items()
	{
		return array
		(
			array( 'id' => '1', 'text' => __( 'Yes', 'wdc' ) )
		);
	}

	public function apply( $operator, $value )
	{
		return apply_operator( $operator, is_user_logged_in(), true );
	}
}

register_condition( 'wdc\User_Logged_In_Condition' );
