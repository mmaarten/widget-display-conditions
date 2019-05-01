<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Post tag condition
 */
class WDC_Post_Tag_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'post_tag', __( 'Post Tag', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'post',
			'order'     => 60,
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
		return wdc_get_term_field_items( 'post_tag' );
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
		return wdc_do_operator( $operator, is_tag( $value ), true );
	}
}

wdc_register_condition( 'WDC_Post_Tag_Condition' );
