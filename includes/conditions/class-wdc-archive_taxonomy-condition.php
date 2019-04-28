<?php 

namespace wdc;

/**
 * Archive taxonomy condition
 */
class Archive_Taxonomy_Condition extends Condition
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

		return do_operator( $operator, is_archive( $value ), true );
	}
}

register_condition( __NAMESPACE__ . '\Archive_Taxonomy_Condition' );
