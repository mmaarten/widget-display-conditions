<?php

namespace wdc;

class Post_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post', __( 'Post', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'order'     => 80,
		));
	}

	public function value_field_items( $items )
	{
		$post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'names' );

		array_unshift( $post_types, 'post' );

		return get_post_field_items( $post_types );
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, is_single( $value ), true );
	}
}

register_condition( 'wdc\Post_Condition' );
