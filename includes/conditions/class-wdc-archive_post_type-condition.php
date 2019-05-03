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
	 * Values
	 *
	 * @param array $choices
	 *
	 * @return array
	 */
	public function values( $choices )
	{
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		$post_types = wp_filter_object_list( $post_types, array( 'has_archive' => true ) );

		$values = array();

		foreach ( $post_types as $post_type ) 
		{
			$values[ $post_type->name ] = $post_type->labels->singular_name;
		}

		return $values;
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
