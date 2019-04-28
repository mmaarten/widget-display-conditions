<?php 

namespace wdc;

/**
 * Post type condition
 */
class Post_Type_Condition extends Condition
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

		$items = array();

		foreach ( $post_types as $post_type ) 
		{
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
		return do_operator( $operator, is_singular( $value ), true );
	}
}

register_condition( __NAMESPACE__ . '\Post_Type_Condition' );
