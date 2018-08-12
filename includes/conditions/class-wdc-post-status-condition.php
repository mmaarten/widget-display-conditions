<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Post_Status_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'post_status', __( 'Post Status', 'wdc' ), array
		(
			'category' => 'post'
		));
	}

	public function get_values()
	{
		$values = array();

		$post_statuses = get_post_statuses();

		foreach ( $post_statuses as $id => $title ) 
		{
			$values[] = array
			(
				'id'   => $id,
				'text' => $title
			);
		}

		return $values;
	}

	public function apply( $value, $operator )
	{
		if ( ! is_singular() ) 
		{
			return false;
		}

		return $operator->apply( $value, get_post_status() );
	}
}

wdc_register_condition( 'WDC_Post_Status_Condition' );
