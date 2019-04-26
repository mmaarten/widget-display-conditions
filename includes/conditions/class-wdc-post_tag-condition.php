<?php

namespace wdc;

class Post_Tag_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_tag', __( 'Post Tag', 'wdc' ), array
		(
			'category' => 'post'
		));
	}

	public function value_field_items( $items )
	{
		return wdc_term_choices( array
		(
			'taxonomy' => 'post_tag'
		));
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, is_tag( $value ), true );
	}
}

register_condition( 'wdc\Post_Tag_Condition' );
