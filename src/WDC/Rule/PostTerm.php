<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Rule_PostTerm extends WDC_Rule_Base
{
	public function __construct()
	{
		parent::__construct( 'post_term', __( 'Post Term', 'wdc' ) );
	}

	public function choices()
	{
		return wdc_term_choices( array
		(
			'group' => true
		));
	}

	public function apply( $value, $operator )
	{
		if ( ! is_category() && ! is_tag() && ! is_tax() ) 
		{
			return false;
		}

		$queried_object = get_queried_object();

		return $operator->apply( $value == $queried_object->term_id, true );
	}
}