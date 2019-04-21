<?php 

namespace wdc;

class Post_Type_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_type', __( 'Post Type', 'wdc' ), array
		(
			'category' => 'post',
		));
	}

	public function get_value_field_items()
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

	public function apply( $operator, $value )
	{
		return apply_operator( $operator, is_singular( $value ), true );
	}
}

register_condition( 'wdc\Post_Type_Condition' );
