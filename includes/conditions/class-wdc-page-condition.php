<?php

namespace wdc;

class Page_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'page', __( 'Page', 'wdc' ), array
		(
			'category'  => 'page',
			'operators' => array( '==', '!=' ),
			'order'     => 10,
		));
	}

	public function value_field_items( $items )
	{
		return get_post_field_items( 'page' );
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, is_page( $value ), true );
	}
}

register_condition( 'wdc\Page_Condition' );
