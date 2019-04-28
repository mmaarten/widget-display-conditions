<?php 

namespace wdc;

/**
 * User logged in condition
 */
class User_Logged_In_Condition extends Condition
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
	 * Value field items
	 *
	 * @param array $items
	 *
	 * @return array
	 */
	public function value_field_items( $items )
	{
		return array
		(
			array( 'id' => '1', 'text' => __( 'Yes', 'wdc' ) )
		);
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
		return do_operator( $operator, is_user_logged_in(), true );
	}
}

register_condition( __NAMESPACE__ . '\User_Logged_In_Condition' );
