<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Post_Template_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'post_template', __( 'Post Template', 'wdc' ), array
		(
			'category' => 'post'
		));
	}

	public function get_values()
	{
		return wdc_page_template_choices();
	}

	public function apply( $value, $operator )
	{
		if ( ! is_single() ) 
		{
			return false;
		}

		return $operator->apply( get_page_template(), $value );
	}
}

wdc_register_condition( 'WDC_Post_Template_Condition' );
