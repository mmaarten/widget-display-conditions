<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Post type condition
 */
class WDC_Post_Type_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'post_type', __( 'Post Type', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'post',
			'order'     => 10,
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
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		$values = array();

		foreach ( $post_types as $post_type ) 
		{
			$values[ $post_type->name ] = $post_type->labels->singular_name;
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
		return wdc_do_operator( $operator, is_singular( $value ), true );
	}
}

wdc_register_condition( 'WDC_Post_Type_Condition' );
