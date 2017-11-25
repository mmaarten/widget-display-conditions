<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Rule_PostType extends WDC_Rule_Base
{
	public function __construct()
	{
		parent::__construct( 'post_type', __( 'Post Type', 'wdc' ) );
	}

	public function choices()
	{
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		$choices = array();

		foreach ( $post_types as $post_type ) 
		{
			$choices[] = array
			(
				'id'   => $post_type->name,
				'text' => $post_type->labels->singular_name
			);
		}

		return $choices;
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( is_singular( $value ), true );
	}
}