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
	 * Values
	 *
	 * @param array $choices
	 *
	 * @return array
	 */
	public function values( $choices )
	{
		$post_templates = wdc_get_post_templates();

		$values = array
		(
			'' => __( 'Default', 'wdc' ),
		);

		foreach ( $post_templates as $post_type => $templates ) 
		{
			$post_type = get_post_type_object( $post_type );

			$values[ $post_type->labels->singular_name ] = $templates;
		}

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

wdc_register_condition( 'WDC_Post_Template_Condition' );
