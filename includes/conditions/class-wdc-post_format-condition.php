<?php 

namespace wdc;

class Post_Format_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_format', __( 'Post Format', 'wdc' ), array
		(
			'category' => 'post',
		));
	}

	public function get_value_field_items()
	{
		return get_term_field_items( 'post_format' );
	}

	public function apply( $operator, $value )
	{
		return apply_operator( $operator, $value, get_post_format() );
	}
}

register_condition( 'wdc\Post_Format_Condition' );
