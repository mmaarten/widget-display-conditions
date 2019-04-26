<?php

namespace wdc;

class Post_Type_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_type', __( 'Post Type', 'wdc' ), array
		(
			'category'  => 'post',
			'operators' => array( '==', '!=' ),
			'order'     => 10,
		));
	}

	public function value_field_items( $items )
	{
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		$items = array();

		foreach ( $post_types as $post_type ) 
		{
			$items[] = array
			(
				'id'   => $post_type->name,
				'text' => $post_type->labels->singular_name
			);
		}

		return $items;
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, is_singular( $value ), true );
	}
}

register_condition( 'wdc\Post_Type_Condition' );
