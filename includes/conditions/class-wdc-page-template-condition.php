<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Page_Template_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'page_template', __( 'Page Template', 'wdc' ), array
		(
			'category' => 'page'
		));
	}

	public function get_values()
	{
		return wdc_page_template_choices();
	}

	public function apply( $value, $operator )
	{
		if ( ! is_page() ) 
		{
			return false;
		}

		return $operator->apply( get_page_template(), $value );
	}
}

wdc_register_condition( 'WDC_Page_Template_Condition' );