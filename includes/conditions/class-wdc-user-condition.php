<?php

namespace wdc;

class User_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'user', __( 'User', 'wdc' ), array
		(
			'category'  => 'user',
			'operators' => array( '==', '!=' ),
			'order'     => 10,
		));
	}

	public function value_field_items( $items )
	{
		$users = get_users( array
		(
			'orderby' => 'display_name',
			'order'   => 'ASC'
		));

		foreach ( $users as $user ) 
		{
			$items[ $user->ID ] = array
			(
				'id'   => $user->ID,
				'text' => $user->display_name,
			);
		}
		
		return $items;
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, get_current_user_id(), $value );
	}
}

register_condition( 'wdc\User_Condition' );
