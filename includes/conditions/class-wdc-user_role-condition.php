<?php 

namespace wdc;

class User_Role_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'user_role', __( 'User Role', 'wdc' ), array
		(
			'category' => 'user',
		));
	}

	public function get_value_field_items()
	{
		$items = array();

		$roles = get_editable_roles();

		foreach ( $roles as $role_name => $role_info ) 
		{
			$items[] = array
			(
				'id'   => $role_name,
				'text' => $role_info['name']
			);
		}

		return $items;
	}

	public function apply( $operator, $value )
	{
		$user = wp_get_current_user();

		if ( empty( $user->roles ) ) 
		{
			return false;
		}
		
		return apply_operator( $operator, $user->roles[0], $value );
	}
}

register_condition( 'wdc\User_Role_Condition' );
