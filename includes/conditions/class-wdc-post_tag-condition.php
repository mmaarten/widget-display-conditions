<?php 

namespace wdc;

class Post_Tag_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_tag', __( 'Post Tag', 'wdc' ), array
		(
			'category' => 'post',
		));
	}

	public function get_value_field_items()
	{
		return get_term_field_items( 'post_tag' );
	}

	public function apply( $operator, $value )
	{
		return apply_operator( $operator, is_tag( $value ), true );
	}
}

register_condition( 'wdc\Post_Tag_Condition' );
