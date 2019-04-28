<?php 

namespace wdc;

/**
 * User role condition
 */
class User_Role_Condition extends Condition
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

		$items = array();

		foreach ( $roles as $role_name => $role_info ) 
		{
			$items[ $role_name ] = array
			(
				'id'   => $role_name,
				'text' => $role_info['name']
			);
		}

		return $items;
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
		
		return do_operator( $operator, $value, $user->roles[0] );
	}
}

register_condition( __NAMESPACE__ . '\User_Role_Condition' );
