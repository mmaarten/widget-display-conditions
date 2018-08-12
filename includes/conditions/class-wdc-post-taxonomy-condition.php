<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Post_Taxonomy_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'post_taxonomy', __( 'Post Taxonomy', 'wdc' ), array
		(
			'category' => 'post'
		));
	}

	public function get_values()
	{
		// TODO : _builtin => false also returns 'category' and 'post_tag'
		$taxonomies = get_taxonomies( array( 'public' => true, '_builtin' => false ), 'names' );

		return wdc_term_choices( array
		(
			'taxonomy' => $taxonomies,
			'group'    => true
		));
	}

	public function apply( $value, $operator )
	{
		if ( ! is_category() && ! is_tag() && ! is_tax() ) 
		{
			return false;
		}

		return $operator->apply( is_category( $value ) || is_tag( $value ) || is_tax( $value ), true );
	}
}

wdc_register_condition( 'WDC_Post_Taxonomy_Condition' );
