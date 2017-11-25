<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Rule_PageParent extends WDC_Rule_Base
{
	public function __construct()
	{
		parent::__construct( 'page_parent', __( 'Page Parent', 'wdc' ) );
	}

	public function choices()
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
		
		$ancestors = get_ancestors( get_the_ID(), get_post_type(), 'post_type' );

		return $operator->apply( in_array( $value, $ancestors ), true );
	}
}