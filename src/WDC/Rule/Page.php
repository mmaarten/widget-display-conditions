<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Rule_Page extends WDC_Rule_Base
{
	public function __construct()
	{
		parent::__construct( 'page', __( 'Page', 'wdc' ) );
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
		return $operator->apply( is_page( $value ), true );
	}
}