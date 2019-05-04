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
	 * Values
	 *
	 * @param array $choices
	 *
	 * @return array
	 */
	public function values( $choices )
	{
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		unset( $post_types['page'], $post_types['attachment'] );

		$values = array();

		foreach ( $post_types as $post_type ) 
		{
			$post_values = wdc_get_post_choices( $post_type->name );

			if ( $post_values ) 
			{
				$values[ $post_type->labels->singular_name ] = $post_values;
			}
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
		return wdc_do_operator( $operator, is_single( $value ), true );
	}
}

wdc_register_condition( 'WDC_Post_Condition' );
