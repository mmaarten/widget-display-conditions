<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Archive taxonomy condition
 */
class WDC_Archive_Taxonomy_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'archive_taxonomy', __( 'Archive Taxonomy', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'archive',
			'order'     => 20,
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
		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

		$values = array();

		foreach ( $taxonomies as $taxonomy ) 
		{
			$values[ $taxonomy->name ] = $taxonomy->labels->singular_name;
		}

		return wdc_create_field_items( $values );
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
		if ( ! is_category() && ! is_tag() && ! is_tax() ) 
		{
			return false;
		}

		return wdc_do_operator( $operator, is_archive( $value ), true );
	}
}

wdc_register_condition( 'WDC_Archive_Taxonomy_Condition' );
