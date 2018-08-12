<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Page_Type_Condition extends WDC_Condition
{
	public function __construct()
	{
		parent::__construct( 'page_type', __( 'Page Type', 'wdc' ), array
		(
			'category' => 'page'
		));
	}

	public function get_values()
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

	public function apply( $value, $operator )
	{
		$queried_object = get_queried_object();
		
		switch ( $value ) 
		{
			case 'front_page' :
				
				return $operator->apply( is_front_page(), true );

			case 'posts_page' :
				
				return $operator->apply( is_front_page() && ! is_home(), true );

			case 'search_page' :
				
				return $operator->apply( is_search(), true );

			case '404_page' :
				
				return $operator->apply( is_404(), true );

			case 'date_page' :
				
				return $operator->apply( is_date(), true );

			case 'author_page' :
				
				return $operator->apply( is_author(), true );

			case 'top_level' :
				
				if ( ! is_page() ) 
				{
					return false;
				}

				$ancestors = get_post_ancestors( $queried_object->ID );

				return $operator->apply( count( $ancestors ) == 0, true );

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

				return $operator->apply( count( $children ) > 0, true );

			case 'child' :

				if ( ! is_page() ) 
				{
					return false;
				}

				$ancestors = get_post_ancestors( $queried_object->ID );

				return $operator->apply( count( $ancestors ) > 0, true );
		}

		return false;
	}
}

wdc_register_condition( 'WDC_Page_Type_Condition' );
