<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Post template condition
 */
class WDC_Post_Template_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'post_template', __( 'Post Template', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'post',
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
		$templates = get_page_templates();

		$items = array();

		$items['default'] = array
		(
			'id'   => '',
			'text' => __( 'Default', 'wdc' ),
		);
   
		foreach ( $templates as $name => $filename ) 
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

wdc_register_condition( 'WDC_Post_Template_Condition' );
