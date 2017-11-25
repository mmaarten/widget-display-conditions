<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Rule_UserRole extends WDC_Rule_Base
{
	public function __construct()
	{
		parent::__construct( 'user_role', __( 'User Role', 'wdc' ) );
	}

	public function choices()
	{
		$choices = array();

		$roles = get_editable_roles();

		foreach ( get_editable_roles() as $role_name => $role_info )
		{
			$choices[] = array
			(
				'id'   => $role_name,
				'text' => $role_info['name']
			);
		}

		return $choices;
	}

	public function apply( $value, $operator )
	{
		if ( ! is_user_logged_in() ) 
		{
			return false;
		}

		$user = wp_get_current_user();

		if ( empty( $user->roles ) ) 
		{
			return false;
		}

		return $operator->apply( $value == $user->roles[0], true );
	}
}