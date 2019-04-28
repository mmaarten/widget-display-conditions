<?php 

namespace wdc;

/**
 * Archive post type condition
 */
class Archive_Post_Type_Condition extends Condition
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

		$items = array();

		foreach ( $post_types as $post_type ) 
		{
			if ( ! $post_type->has_archive ) 
			{
				continue;
			}

			$items[ $post_type->name ] = array
			(
				'id'   => $post_type->name,
				'text' => $post_type->labels->singular_name,
			);
		}

		return $items;
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
		return do_operator( $operator, is_post_type_archive( $value ), true );
	}
}

register_condition( __NAMESPACE__ . '\Archive_Post_Type_Condition' );
