<?php

namespace wdc;

class Page_Template_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'page_template', __( 'Page Template', 'wdc' ), array
		(
			'category'  => 'page',
			'operators' => array( '==', '!=' ),
			'order'     => 10,
		));
	}

	public function value_field_items( $items )
	{
		return get_page_template_field_items();
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
