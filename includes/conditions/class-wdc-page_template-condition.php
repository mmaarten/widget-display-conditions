<?php 

namespace wdc;

class Page_Template_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'page_template', __( 'Page Template', 'wdc' ), array
		(
			'category' => 'page',
			'order'    => 20,
		));
	}

	public function get_value_field_items()
	{
		return get_page_template_field_items();
	}

	public function apply( $operator, $value )
	{
		if ( ! is_page() ) 
		{
			return false;
		}

		return apply_operator( $operator, get_page_template(), $value );
	}
}

register_condition( 'wdc\Page_Template_Condition' );
