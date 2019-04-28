<?php 

namespace wdc;

/**
 * Post condition
 */
class Post_Condition extends Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'post', __( 'Post', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'post',
			'order'     => 1000,
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
		$post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'names' );

		array_unshift( $post_types, 'post' );

		return get_post_field_items( $post_types );
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
		return do_operator( $operator, is_single( $value ), true );
	}
}

register_condition( __NAMESPACE__ . '\Post_Condition' );
