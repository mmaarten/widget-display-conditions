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
	 * Value field items
	 *
	 * @param array $items
	 *
	 * @return array
	 */
	public function value_field_items( $items )
	{
		$taxonomies = get_taxonomies( array( 'public' => true, '_builtin' => false ), 'names' );

		return wdc_get_term_field_items( $taxonomies, true );
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
