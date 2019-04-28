<?php 

namespace wdc;

/**
 * Attachment condition
 */
class Attachment_Condition extends Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'attachment', __( 'Attachment', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'attachment',
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
		return get_post_field_items( 'attachment' );
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
		return do_operator( $operator, is_attachment( $value ), true );
	}
}

register_condition( __NAMESPACE__ . '\Attachment_Condition' );
