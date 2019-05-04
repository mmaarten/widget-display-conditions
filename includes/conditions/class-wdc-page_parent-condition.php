<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Page parent condition
 */
class WDC_Page_Parent_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'page_parent', __( 'Page Parent', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'page',
			'order'     => 20,
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
		return wdc_get_post_choices( 'page' );
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
		if ( ! is_page() ) return false;

		$ancestors = get_post_ancestors( get_post() );

		return wdc_do_operator( $operator, in_array( $value, $ancestors ), true );
	}
}

wdc_register_condition( 'WDC_Page_Parent_Condition' );
