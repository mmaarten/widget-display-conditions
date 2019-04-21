<?php 

namespace wdc;

class Page_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'page', __( 'Page', 'wdc' ), array
		(
			'category' => 'page',
		));
	}

	public function get_value_field_items()
	{
		return get_post_field_items( 'page' );
	}

	public function apply( $operator, $value )
	{
		return apply_operator( $operator, is_page( $value ), true );
	}
}

register_condition( 'wdc\Page_Condition' );
