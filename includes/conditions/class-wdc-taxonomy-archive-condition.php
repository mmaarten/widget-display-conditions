<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Taxonomy_Archive_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'taxonomy_archive', __( 'Archive Taxonomy', 'wdc' ), array
		(
			'category' => 'archive'
		));
	}

	public function get_values()
	{
		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

		$values = array();

		foreach ( $taxonomies as $taxonomy ) 
		{
			$values[] = array
			(
				'id'   => $taxonomy->name,
				'text' => $taxonomy->labels->singular_name
			);
		}

		return $values;
	}

	public function apply( $value, $operator )
	{
		if ( ! is_category() && ! is_tag() && ! is_tax() ) 
		{
			return false;
		}

		return $operator->apply( is_archive( $value ), true );
	}
}

wdc_register_condition( 'WDC_Taxonomy_Archive_Condition' );
