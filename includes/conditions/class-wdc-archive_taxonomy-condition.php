<?php 

namespace wdc;

class Archive_Taxonomy_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'archive_taxonomy', __( 'Archive Taxonomy', 'wdc' ), array
		(
			'category' => 'archive',
			'order'    => 30,
		));
	}

	public function get_value_field_items()
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

	public function apply( $operator, $value )
	{
		if ( ! is_category() && ! is_tag() && ! is_tax() ) 
		{
			return false;
		}

		return apply_operator( $operator, is_archive( $value ), true );
	}
}

register_condition( 'wdc\Archive_Taxonomy_Condition' );
