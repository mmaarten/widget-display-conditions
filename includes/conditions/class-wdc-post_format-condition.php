<?php 

namespace wdc;

/**
 * Post format condition
 */
class Post_Format_Condition extends Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'post_format', __( 'Post Format', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'post',
			'order'     => 50,
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
		return get_term_field_items( 'post_format' );
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
		return do_operator( $operator, $value, get_post_format() );
	}
}

register_condition( __NAMESPACE__ . '\Post_Format_Condition' );
