<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * User role condition
 */
class WDC_User_Role_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'user_role', __( 'User Role', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'user',
			'order'     => 10,
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
		$roles = get_editable_roles();

		$values = array();

		foreach ( $roles as $role_name => $role_info ) 
		{
			$values[ $role_name ] = $role_info['name'];
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
		$user = wp_get_current_user();

		if ( ! $user->ID || empty( $user->roles ) ) 
		{
			return false;
		}
		
		return wdc_do_operator( $operator, $value, $user->roles[0] );
	}
}

wdc_register_condition( 'WDC_User_Role_Condition' );
