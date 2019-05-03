<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Post status condition
 */
class WDC_Post_Status_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'post_status', __( 'Post Status', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'post',
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
		$values = get_post_statuses();

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
		if ( ! is_singular() ) return false;

		return wdc_do_operator( $operator, $value, get_post_status() );
	}
}

wdc_register_condition( 'WDC_Post_Status_Condition' );
