<?php 

namespace wdc;

/**
 * Archive author condition
 */
class Archive_Author_Condition extends Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'archive_author', __( 'Archive Author', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'archive',
			'order'     => 30,
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
			'who'     => 'authors',
			'orderby' => 'display_name',
			'order'   => 'ASC'
		));

		$items = array();

		foreach ( $users as $user ) 
		{
			$items[ $user->ID ] = array
			(
				'id'   => $user->ID,
				'text' => $user->display_name,
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
		return do_operator( $operator, is_author( $value ), true );
	}
}

register_condition( __NAMESPACE__ . '\Archive_Author_Condition' );
