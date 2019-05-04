<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * User logged in condition
 */
class WDC_User_Logged_In_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'user_logged_in', __( 'User Logged In', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'user',
			'order'     => 20,
		));
	}

	/**
	 * Values
	 *
	 * @param array $choices
	 *
	 * @return array
	 */
	public function values( $choices )
	{
		$choices = array
		(
			'1' => __( 'Yes', 'wdc' ),
		);

		return $choices;
	}
	
	/**
	 * Apply
	 *
	 * @param bool   $return
	 * @param string $operator
	 * @param mixed  $value
	 *
	 * @return bool
	 */
	public function apply( $return, $operator, $value )
	{
		return wdc_do_operator( $operator, is_user_logged_in(), true );
	}
}

wdc_register_condition( 'WDC_User_Logged_In_Condition' );
