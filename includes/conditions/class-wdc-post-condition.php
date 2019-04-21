<?php 

namespace wdc;

class Post_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post', __( 'Post', 'wdc' ), array
		(
			'category' => 'post',
		));
	}

	public function get_value_field_items()
	{
		$post_types = get_post_types( array( 'public' => true ), 'names' );

		if ( isset( $post_types['page'] ) )       unset( $post_types['page'] );
		if ( isset( $post_types['attachment'] ) ) unset( $post_types['attachment'] );

		return get_post_field_items( $post_types );
	}

	public function apply( $operator, $value )
	{
		return apply_operator( $operator, is_single( $value ), true );
	}
}

register_condition( 'wdc\Post_Condition' );
