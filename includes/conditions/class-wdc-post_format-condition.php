<?php

namespace wdc;

class Post_Format_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_format', __( 'Post Format', 'wdc' ), array
		(
			'category' => 'post'
		));
	}

	public function value_field_items( $items )
	{
		return wdc_term_choices( array
		(
			'taxonomy' => 'post_format'
		));
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, $value, get_post_format() );
	}
}

register_condition( 'wdc\Post_Format_Condition' );
