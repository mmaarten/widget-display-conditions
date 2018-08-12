<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Post_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'post', __( 'Post', 'wdc' ), array
		(
			'category' => 'post'
		));
	}

	public function get_values()
	{
		$post_types = get_post_types( array( 'public' => true ), 'names' );

		if ( isset( $post_types['page'] ) ) 
		{
			unset( $post_types['page'] );
		}

		if ( isset( $post_types['attachment'] ) ) 
		{
			unset( $post_types['attachment'] );
		}

		return wdc_post_choices( array
		(
			'post_type' => $post_types,
			'group'     => true
		));
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( is_single( $value ), true );
	}
}

wdc_register_condition( 'WDC_Post_Condition' );
