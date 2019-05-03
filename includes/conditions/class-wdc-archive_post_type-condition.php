<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Archive post type condition
 */
class WDC_Archive_Post_Type_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'archive_post_type', __( 'Archive Post Type', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'archive',
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
			if ( $post_type->has_archive ) 
			{
				$values[ $post_type->name ] = $post_type->labels->singular_name;
			}
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
		return wdc_do_operator( $operator, is_post_type_archive( $value ), true );
	}
}

wdc_register_condition( 'WDC_Archive_Post_Type_Condition' );
