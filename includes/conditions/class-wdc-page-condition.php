<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Page condition
 */
class WDC_Page_Condition extends WDC_Condition
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
		return wdc_get_post_field_items( 'page' );
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
		return wdc_do_operator( $operator, is_page( $value ), true );
	}
}

wdc_register_condition( 'WDC_Page_Condition' );
