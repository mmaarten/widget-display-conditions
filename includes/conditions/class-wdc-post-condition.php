<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Post condition
 */
class WDC_Post_Condition extends WDC_Condition
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

		return wdc_get_post_field_items( $post_types, true );
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
		return wdc_do_operator( $operator, is_single( $value ), true );
	}
}

wdc_register_condition( 'WDC_Post_Condition' );
