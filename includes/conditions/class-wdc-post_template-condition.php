<?php 

namespace wdc;

class Post_Template_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_template', __( 'Post Template', 'wdc' ), array
		(
			'category' => 'post',
			'order'    => 60,
		));
	}

	public function get_value_field_items()
	{
		return get_page_template_field_items();
	}

	public function apply( $operator, $value )
	{
		if ( ! is_single() ) return false;

		return apply_operator( $operator, get_page_template(), $value );
	}
}

register_condition( 'wdc\Post_Template_Condition' );
