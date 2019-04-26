<?php

namespace wdc;

class Post_Taxonomy_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'post_taxonomy', __( 'Post Taxonomy', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'order'     => 70,
		));
	}

	public function value_field_items( $items )
	{
		// TODO : _builtin => false also returns 'category' and 'post_tag'
		$taxonomies = get_taxonomies( array( 'public' => true, '_builtin' => false ), 'names' );

		return get_term_field_items( $taxonomies );
	}

	public function apply( $return, $operator, $value )
	{
		if ( ! is_category() && ! is_tag() && ! is_tax() ) 
		{
			return false;
		}

		return do_operator( $operator, is_category( $value ) || is_tag( $value ) || is_tax( $value ), true );
	}
}

register_condition( 'wdc\Post_Taxonomy_Condition' );
