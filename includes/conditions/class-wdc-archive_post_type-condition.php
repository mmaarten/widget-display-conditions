<?php 

namespace wdc;

class Archive_Post_Type_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_type_archive', __( 'Archive Post Type', 'wdc' ), array
		(
			'category' => 'archive',
		));
	}

	public function get_value_field_items()
	{
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		$items = array();

		foreach ( $post_types as $post_type ) 
		{
			if ( ! $post_type->has_archive ) 
			{
				continue;
			}

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
		return apply_operator( $operator, is_post_type_archive( $value ), true );
	}
}

register_condition( 'wdc\Archive_Post_Type_Condition' );
