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
	 * Values
	 *
	 * @param array $choices
	 *
	 * @return array
	 */
	public function values( $choices )
	{
		$users = get_users( array
		(
			'who'     => 'authors',
			'orderby' => 'display_name',
			'order'   => 'ASC'
		));

		return wp_list_pluck( $users, 'display_name', 'ID' );
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
