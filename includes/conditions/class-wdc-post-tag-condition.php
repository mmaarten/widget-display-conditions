<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Post_Tag_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'post_tag', __( 'Post Tag', 'wdc' ), array
		(
			'category' => 'post'
		));
	}

	public function get_values()
	{
		return wdc_term_choices( array
		(
			'taxonomy' => 'post_tag'
		));
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( is_tag( $value ), true );
	}
}

wdc_register_condition( 'WDC_Post_Tag_Condition' );
