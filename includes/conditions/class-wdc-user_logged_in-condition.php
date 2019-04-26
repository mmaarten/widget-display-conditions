<?php

namespace wdc;

class User_Logged_In_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'user_logged_in', __( 'User Logged In', 'wdc' ), array
		(
			'category' => 'user'
		));
	}

	public function value_field_items( $items )
	{
		return array
		(
			array( 'id' => '1', 'text' => __( 'Yes', 'wdc' ) )
		);
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, is_user_logged_in(), true );
	}
}

register_condition( 'wdc\User_Logged_In_Condition' );
