<?php 

namespace wdc;

class Post_Category_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_category', __( 'Post Category', 'wdc' ), array
		(
			'category' => 'post',
			'order'    => 40,
		));
	}

	public function get_value_field_items()
	{
		return get_term_field_items( 'category' );
	}

	public function apply( $operator, $value )
	{
		return apply_operator( $operator, is_category( $value ), true );
	}
}

register_condition( 'wdc\Post_Category_Condition' );
