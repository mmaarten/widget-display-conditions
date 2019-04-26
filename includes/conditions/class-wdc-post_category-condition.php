<?php

namespace wdc;

class Post_Category_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_category', __( 'Post Category', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'order'     => 40,
		));
	}

	public function value_field_items( $items )
	{
		return get_term_field_items( 'category' );
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, is_category( $value ), true );
	}
}

register_condition( 'wdc\Post_Category_Condition' );
