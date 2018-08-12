<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_User_Role_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'user_role', __( 'User Role', 'wdc' ), array
		(
			'category' => 'user'
		));
	}

	public function get_values()
	{
		$values = array();

		$roles = get_editable_roles();

		foreach ( $roles as $role_name => $role_info ) 
		{
			$values[] = array
			(
				'id'   => $role_name,
				'text' => $role_info['name']
			);
		}

		return $values;
	}

	public function apply( $value, $operator )
	{
		$user = wp_get_current_user();

		if ( empty( $user->roles ) ) 
		{
			return false;
		}
		
		return $operator->apply( $user->roles[0], $value );
	}
}

wdc_register_condition( 'WDC_User_Role_Condition' );
