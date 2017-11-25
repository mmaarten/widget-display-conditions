<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Rule_Post extends WDC_Rule_Base
{
	public function __construct()
	{
		parent::__construct( 'post', __( 'Post', 'wdc' ) );
	}

	public function choices()
	{
		$post_types = get_post_types( array( 'public' => true ), 'names' );

		unset( $post_types[ 'page' ] );

		return wdc_post_choices( array
		(
			'post_type' => $post_types,
			'group'     => true
		));
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( is_single( $value ) || is_attachment( $value ), true );
	}
}