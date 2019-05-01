<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Page Type condition
 */
class WDC_Page_Type_Condition extends WDC_Condition
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( 'page_type', __( 'Page Type', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'category'  => 'page',
			'order'     => 10,
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
		return array
		(
			'front_page'  => array( 'id' => 'front_page', 	'text' => __( 'Front Page', 'wdc' ) ),
			'posts_page'  => array( 'id' => 'posts_page', 	'text' => __( 'Posts Page', 'wdc' ) ),
			'search_page' => array( 'id' => 'search_page', 	'text' => __( 'Search Page', 'wdc' ) ),
			'404_page'    => array( 'id' => '404_page', 	'text' => __( '404 Page (not found)', 'wdc' ) ),
			'date_page'   => array( 'id' => 'date_page', 	'text' => __( 'Date Page', 'wdc' ) ),
			'author_page' => array( 'id' => 'author_page', 	'text' => __( 'Author Page', 'wdc' ) ),
			'top_level'   => array( 'id' => 'top_level', 	'text' => __( 'Top Level Page (no parent)', 'wdc' ) ),
			'parent'      => array( 'id' => 'parent', 		'text' => __( 'Parent Page (has children)', 'wdc' ) ),
			'child'       => array( 'id' => 'child', 		'text' => __( 'Child Page (has parent)', 'wdc' ) ),
		);
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
		$queried_object = get_queried_object();
		
		switch ( $value ) 
		{
			case 'front_page' :
				
				return wdc_do_operator( $operator, is_front_page(), true );

			case 'posts_page' :
				
				return wdc_do_operator( $operator, is_home(), true );

			case 'search_page' :
				
				return wdc_do_operator( $operator, is_search(), true );

			case '404_page' :
				
				return wdc_do_operator( $operator, is_404(), true );

			case 'date_page' :
				
				return wdc_do_operator( $operator, is_date(), true );

			case 'author_page' :
				
				return wdc_do_operator( $operator, is_author(), true );

			case 'top_level' :
				
				if ( ! is_page() ) 
				{
					return false;
				}

				$ancestors = get_post_ancestors( $queried_object->ID );

				return wdc_do_operator( $operator, count( $ancestors ) == 0, true );

			case 'parent' :
				
				if ( ! is_page() ) 
				{
					return false;
				}

				$children = get_children( array
				(
					'post_parent' => $queried_object->ID,
					'post_type'   => $queried_object->post_type,
					'numberposts' => 1,
					'post_status' => 'any'
				));

				return wdc_do_operator( $operator, count( $children ) > 0, true );

			case 'child' :

				if ( ! is_page() ) 
				{
					return false;
				}

				$ancestors = get_post_ancestors( $queried_object->ID );

				return wdc_do_operator( $operator, count( $ancestors ) > 0, true );
		}

		return false;
	}
}

wdc_register_condition( 'WDC_Page_Type_Condition' );