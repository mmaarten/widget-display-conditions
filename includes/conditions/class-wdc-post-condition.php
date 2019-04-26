<?php

namespace wdc;

class Post_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post', __( 'Post', 'wdc' ), array
		(
			'category' => 'post'
		));
	}

	public function value_field_items( $items )
	{
		$post_types = get_post_types( array( 'public' => true ), 'names' );

		if ( isset( $post_types['page'] ) ) 
		{
			unset( $post_types['page'] );
		}

		if ( isset( $post_types['attachment'] ) ) 
		{
			unset( $post_types['attachment'] );
		}

		return wdc_post_choices( array
		(
			'post_type' => $post_types,
			'group'     => true
		));
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, is_single( $value ), true );
	}
}

register_condition( 'wdc\Post_Condition' );
