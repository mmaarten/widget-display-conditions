<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Post format condition
 */
class WDC_Post_Format_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'post_format', __( 'Post Format', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'post',
			'order'     => 50,
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
		return wdc_get_term_field_items( 'post_format' );
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
		return wdc_do_operator( $operator, $value, get_post_format() );
	}
}

wdc_register_condition( 'WDC_Post_Format_Condition' );
