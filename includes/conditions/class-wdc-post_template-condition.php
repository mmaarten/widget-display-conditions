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
		$post_templates = wdc_get_post_templates();

		$items = array
		(
			array( 'id' => '', 'text' => __( 'Default', 'wdc' ) ),
		);

		foreach ( $post_templates as $post_type => $templates ) 
		{
			$post_type = get_post_type_object( $post_type );

			$group = array
			(
				'id'       => $post_type->name,
				'text'     => $post_type->labels->singular_name,
				'children' => wdc_create_field_items( $templates ),
			);

			$items[ $group['id'] ] = $group;
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
