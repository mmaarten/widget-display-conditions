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
	 * Values
	 *
	 * @param array $choices
	 *
	 * @return array
	 */
	public function values( $choices )
	{
		return wdc_get_post_choices( 'attachment' );
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
