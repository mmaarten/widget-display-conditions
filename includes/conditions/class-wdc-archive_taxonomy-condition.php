<?php

namespace wdc;

class Archive_Taxonomy_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'archive_taxonomy', __( 'Archive Taxonomy', 'wdc' ), array
		(
			'category'  => 'archive',
			'operators' => array( '==', '!=' ),
			'order'     => 10,
		));
	}

	public function value_field_items( $items )
	{
		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

		$items = array();

		foreach ( $taxonomies as $taxonomy ) 
		{
			$items[] = array
			(
				'id'   => $taxonomy->name,
				'text' => $taxonomy->labels->singular_name
			);
		}

		return $items;
	}

	public function apply( $return, $operator, $value )
	{
		if ( ! is_category() && ! is_tag() && ! is_tax() ) 
		{
			return false;
		}

		return do_operator( $operator, is_archive( $value ), true );
	}
}

register_condition( 'wdc\Archive_Taxonomy_Condition' );
