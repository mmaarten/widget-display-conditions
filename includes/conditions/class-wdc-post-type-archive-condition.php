<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Post_Type_Archive_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'post_type_archive', __( 'Archive Post Type', 'wdc' ), array
		(
			'category' => 'archive'
		));
	}

	public function get_values()
	{
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		$values = array();

		foreach ( $post_types as $post_type ) 
		{
			if ( ! $post_type->has_archive ) 
			{
				continue;
			}

			$values[] = array
			(
				'id'   => $post_type->name,
				'text' => $post_type->labels->singular_name
			);
		}

		return $values;
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( is_post_type_archive( $value ), true );
	}
}

wdc_register_condition( 'WDC_Post_Type_Archive_Condition' );
