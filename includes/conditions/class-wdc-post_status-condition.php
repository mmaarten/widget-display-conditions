<?php 

namespace wdc;

class Post_Status_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_status', __( 'Post Status', 'wdc' ), array
		(
			'category' => 'post',
			'order'    => 50,
		));
	}

	public function get_value_field_items()
	{
		$items = array();

		$post_statuses = get_post_statuses();

		foreach ( $post_statuses as $id => $title ) 
		{
			$items[] = array
			(
				'id'   => $id,
				'text' => $title
			);
		}

		return $items;
	}

	public function apply( $operator, $value )
	{
		if ( ! is_singular() ) 
		{
			return false;
		}

		return apply_operator( $operator, $value, get_post_status() );
	}
}

register_condition( 'wdc\Post_Status_Condition' );
