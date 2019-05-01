<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Attachment condition
 */
class WDC_Attachment_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'attachment', __( 'Attachment', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'attachment',
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
		return wdc_get_post_field_items( 'attachment' );
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
		return wdc_do_operator( $operator, is_attachment( $value ), true );
	}
}

wdc_register_condition( 'WDC_Attachment_Condition' );
