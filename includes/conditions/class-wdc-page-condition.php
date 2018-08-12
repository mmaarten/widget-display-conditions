<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Page_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'page', __( 'Page', 'wdc' ), array
		(
			'category' => 'page'
		));
	}

	public function get_values()
	{
		return wdc_post_choices( array
		(
			'post_type' => 'page'
		));
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( is_page( $value ), true );
	}
}

wdc_register_condition( 'WDC_Page_Condition' );