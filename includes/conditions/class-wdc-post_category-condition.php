<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Post category condition
 */
class WDC_Post_Category_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'post_category', __( 'Post Category', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'post',
			'order'     => 40,
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
		return wdc_get_term_field_items( 'category' );
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
		return wdc_do_operator( $operator, is_category( $value ), true );
	}
}

wdc_register_condition( 'WDC_Post_Category_Condition' );
