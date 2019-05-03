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
	 * Values
	 *
	 * @param array $choices
	 *
	 * @return array
	 */
	public function values( $choices )
	{
		$post_templates = wdc_get_post_templates();

		$values = array( '' => __( 'Default', 'wdc' ) );
		$values += $post_templates['page'];

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
		return wdc_do_operator( $operator, $value, get_page_template_slug() );
	}
}

wdc_register_condition( 'WDC_Page_Template_Condition' );
