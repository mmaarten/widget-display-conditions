<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Rule_User extends WDC_Rule_Base
{
	public function __construct()
	{
		parent::__construct( 'user', __( 'User', 'wdc' ) );
	}

	public function choices()
	{
		$choices = array();
		
		$users = get_users();

		foreach ( $users as $user ) 
		{
			$choices[] = array
			(
				'id'   => $user->ID,
				'text' => $user->display_name
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
		
		return $operator->apply( $value == get_current_user_id(), true );
	}
}