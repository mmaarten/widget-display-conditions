<?php

namespace wdc;

class Post_Category_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_category', __( 'Post Category', 'wdc' ), array
		(
			'category' => 'post'
		));
	}

	public function value_field_items( $items )
	{
		return wdc_term_choices( array
		(
			'taxonomy' => 'category'
		));
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, is_category( $value ), true );
	}
}

register_condition( 'wdc\Post_Category_Condition' );
