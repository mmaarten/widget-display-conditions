<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Archive author condition
 */
class WDC_Archive_Author_Condition extends WDC_Condition
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
		return wdc_do_operator( $operator, is_author( $value ), true );
	}
}

wdc_register_condition( 'WDC_Archive_Author_Condition' );
