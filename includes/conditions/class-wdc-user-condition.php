<?php

namespace wdc;

class User_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'user', __( 'User', 'wdc' ), array
		(
			'category' => 'user'
		));
	}

	public function value_field_items( $items )
	{
		return wdc_user_choices();
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, get_current_user_id(), $value );
	}
}

register_condition( 'wdc\User_Condition' );
