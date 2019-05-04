<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Post taxonomy condition
 */
class WDC_Post_Taxonomy_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'post_taxonomy', __( 'Post Taxonomy', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'post',
			'order'     => 70,
		));
	}

	/**
	 * Values
	 *
	 * @param array $choices
	 *
	 * @return array
	 */
	public function values( $choices )
	{
		$taxonomies = get_taxonomies( array( 'public' => true, '_builtin' => false ), 'objects' );

		$values = array();

		foreach ( $taxonomies as $taxonomy ) 
		{
			$term_values = wdc_get_term_choices( $taxonomy->name );

			if ( $term_values ) 
			{
				$values[ "{$taxonomy->labels->singular_name} ({$taxonomy->name})" ] = $term_values;
			}
		}

		return $values;
	}
	
	/**
	 * Apply
	 *
	 * @param bool   $return
	 * @param string $operator
	 * @param mixed  $value
	 *
	 * @return bool
	 */
	public function apply( $return, $operator, $value )
	{
		return wdc_do_operator( $operator, is_tax( $value ), true );
	}
}

wdc_register_condition( 'WDC_Post_Taxonomy_Condition' );
