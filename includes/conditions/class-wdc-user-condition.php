<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * User condition
 */
class WDC_User_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'user', __( 'User', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'user',
			'order'     => 1000,
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
		$users = get_users( array
		(
			'orderby' => 'display_name',
			'order'   => 'ASC'
		));

		$values = array();

		foreach ( $users as $user ) 
		{
			$values[ $user->ID ] = $user->display_name;
		}
		
		return wdc_create_field_items( $values );
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
		return wdc_do_operator( $operator, $value, get_current_user_id() );
	}
}

wdc_register_condition( 'WDC_User_Condition' );
