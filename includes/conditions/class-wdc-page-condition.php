<?php 

namespace wdc;

/**
 * Page condition
 */
class Page_Condition extends Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'page', __( 'Page', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'page',
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
		return get_post_field_items( 'page' );
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
		return do_operator( $operator, is_page( $value ), true );
	}
}

register_condition( __NAMESPACE__ . '\Page_Condition' );
