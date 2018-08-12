<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Attachment_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'attachment', __( 'Attachment', 'wdc' ), array
		(
			'category' => 'attachment'
		));
	}

	public function get_values()
	{
		return wdc_post_choices( array
		(
			'post_type' => 'attachment'
		));
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( is_attachment( $value ), true );
	}
}

wdc_register_condition( 'WDC_Attachment_Condition' );