<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_User_Logged_In_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'user_logged_in', __( 'User Logged In', 'wdc' ), array
		(
			'category' => 'user'
		));
	}

	public function get_values()
	{
		return array
		(
			array( 'id' => '1', 'text' => __( 'Yes', 'wdc' ) )
		);
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( is_user_logged_in(), true );
	}
}

wdc_register_condition( 'WDC_User_Logged_In_Condition' );
