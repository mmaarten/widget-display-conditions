<?php 

namespace wdc;

/**
 * Post category condition
 */
class Post_Category_Condition extends Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'post_category', __( 'Post Category', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'post',
			'order'     => 40,
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
		return get_term_field_items( 'category' );
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
		return do_operator( $operator, is_category( $value ), true );
	}
}

register_condition( __NAMESPACE__ . '\Post_Category_Condition' );
