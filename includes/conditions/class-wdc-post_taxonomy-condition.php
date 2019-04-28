<?php 

namespace wdc;

/**
 * Post taxonomy condition
 */
class Post_Taxonomy_Condition extends Condition
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

		return get_term_field_items( $taxonomies );
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
		return do_operator( $operator, is_tax( $value ), true );
	}
}

register_condition( __NAMESPACE__ . '\Post_Taxonomy_Condition' );
