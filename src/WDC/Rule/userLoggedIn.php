<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Rule_UserLoggedIn extends WDC_Rule_Base
{
	public function __construct()
	{
		parent::__construct( 'user_logged_in', __( 'User Logged In', 'wdc' ) );
	}

	public function choices()
	{
		return array
		(
			array
			(
				'id'   => '1',
				'text' => __( 'Yes', 'wdr' )
			)
		);
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( is_user_logged_in(), true );
	}
}