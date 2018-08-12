<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Post_Format_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'post_format', __( 'Post Format', 'wdc' ), array
		(
			'category' => 'post'
		));
	}

	public function get_values()
	{
		return wdc_term_choices( array
		(
			'taxonomy' => 'post_format'
		));
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( $value, get_post_format() );
	}
}

wdc_register_condition( 'WDC_Post_Format_Condition' );
