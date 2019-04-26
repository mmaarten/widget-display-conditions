<?php

namespace wdc;

class Page_Template_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'page_template', __( 'Page Template', 'wdc' ), array
		(
			'category' => 'page'
		));
	}

	public function value_field_items( $items )
	{
		return wdc_page_template_choices();
	}

	public function apply( $return, $operator, $value )
	{
		if ( ! is_page() ) 
		{
			return false;
		}

		return do_operator( $operator, get_page_template(), $value );
	}
}

register_condition( 'wdc\Page_Template_Condition' );
