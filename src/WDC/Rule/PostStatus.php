<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Rule_PostStatus extends WDC_Rule_Base
{
	public function __construct()
	{
		parent::__construct( 'post_status', __( 'Post Status', 'wdc' ) );
	}

	public function choices()
	{
		$choices = array();

		$post_statuses = get_post_statuses();

		foreach ( $post_statuses as $post_status_name => $post_status_label ) 
		{
			$choices[] = array
			(
				'id'   => $post_status_name,
				'text' => $post_status_label
			);
		}

		return $choices;
	}

	public function apply( $value, $operator )
	{
		if ( ! is_singular() ) 
		{
			return false;
		}

		return $operator->apply( $value == get_post_status(), true );
	}
}