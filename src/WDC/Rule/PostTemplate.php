<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Rule_PostTemplate extends WDC_Rule_Base
{
	public function __construct()
	{
		parent::__construct( 'post_template', __( 'Post Template', 'wdc' ) );
	}

	public function choices()
	{
		$choices[] = array
		(
			'id'   => 'default',
			'text' => __( 'Default', 'wdc' )
		);

		$templates = get_page_templates();

		foreach ( $templates as $template_name => $template_file ) 
		{
			$choices[] = array
			(
				'id'   => $template_file,
				'text' => $template_name
			);
		}

		return $choices;
	}

	public function apply( $value, $operator )
	{
		return $operator->apply( is_page_template( $value ), true );
	}
}