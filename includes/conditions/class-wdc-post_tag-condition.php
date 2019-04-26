<?php

namespace wdc;

class Post_Tag_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_tag', __( 'Post Tag', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'order'     => 60,
		));
	}

	public function value_field_items( $items )
	{
		return get_term_field_items( 'post_tag' );
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, is_tag( $value ), true );
	}
}

register_condition( 'wdc\Post_Tag_Condition' );
