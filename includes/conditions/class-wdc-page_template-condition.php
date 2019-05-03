<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Page template condition
 */
class WDC_Page_Template_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'page_template', __( 'Page Template', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'page',
			'order'     => 30,
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
		$post_templates = wdc_get_post_templates();

		$items = array
		(
			array( 'id' => '', 'text' => __( 'Default', 'wdc' ) ),
		);
		
		foreach ( $post_templates['page'] as $filename => $name ) 
		{
			$items[ $filename ] = array
			(
				'id'   => $filename,
				'text' => $name,
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
		return wdc_do_operator( $operator, $value, get_page_template_slug() );
	}
}

wdc_register_condition( 'WDC_Page_Template_Condition' );
