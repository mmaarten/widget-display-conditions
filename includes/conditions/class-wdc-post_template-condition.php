<?php

namespace wdc;

class Post_Template_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_template', __( 'Post Template', 'wdc' ), array
		(
			'category'  => 'post',
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
		if ( ! is_single() ) 
		{
			return false;
		}

		return do_operator( $operator, get_page_template(), $value );
	}
}

register_condition( 'wdc\Post_Template_Condition' );
