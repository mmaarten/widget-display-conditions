<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Page_Parent_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'page_parent', __( 'Page Parent', 'wdc' ), array
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
		if ( ! is_page() ) 
		{
			return false;
		}

		$ancestors = get_post_ancestors( get_the_ID() );

		return $operator->apply( in_array( $value, $ancestors ), true );
	}
}

wdc_register_condition( 'WDC_Page_Parent_Condition' );