<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Author_Archive_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'author_archive', __( 'Archive Author', 'wdc' ), array
		(
			'category' => 'archive'
		));
	}

	public function get_values()
	{
		return wdc_user_choices( array
		(
			'who' => 'authors'
		));
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( is_author( $value ), true );
	}
}

wdc_register_condition( 'WDC_Author_Archive_Condition' );