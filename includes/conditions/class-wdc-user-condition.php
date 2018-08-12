<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_User_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'user', __( 'User', 'wdc' ), array
		(
			'category' => 'user'
		));
	}

	public function get_values()
	{
		return wdc_user_choices();
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( get_current_user_id(), $value );
	}
}

wdc_register_condition( 'WDC_User_Condition' );
