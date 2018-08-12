<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Post_Category_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'post_category', __( 'Post Category', 'wdc' ), array
		(
			'category' => 'post'
		));
	}

	public function get_values()
	{
		return wdc_term_choices( array
		(
			'taxonomy' => 'category'
		));
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( is_category( $value ), true );
	}
}

wdc_register_condition( 'WDC_Post_Category_Condition' );
