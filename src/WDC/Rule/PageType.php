<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Rule_PageType extends WDC_Rule_Base
{
	public function __construct()
	{
		parent::__construct( 'page_type', __( 'Page Type', 'wdc' ) );
	}

	public function choices()
	{
		return array
		(
			array( 'id' => 'front_page', 	'text' => __( 'Front Page', 'wdc' ) ),
			array( 'id' => 'posts_page', 	'text' => __( 'Posts Page', 'wdc' ) ),
			array( 'id' => 'search_page', 	'text' => __( 'Search Page', 'wdc' ) ),
			array( 'id' => '404_page', 		'text' => __( '404 Page (not found)', 'wdc' ) ),
			array( 'id' => 'date_page', 	'text' => __( 'Date Page', 'wdc' ) ),
			array( 'id' => 'top_level', 	'text' => __( 'Top Level Page (no parent)', 'wdc' ) ),
			array( 'id' => 'parent', 		'text' => __( 'Parent Page (has children)', 'wdc' ) ),
			array( 'id' => 'child', 		'text' => __( 'Child Page (has parent)', 'wdc' ) ),
		);
	}

	public function apply( $value, $operator )
	{
		switch ( $value ) 
		{
			case 'front_page' :
				
				return $operator->apply( is_front_page(), true );
			
			case 'posts_page' :

				return $operator->apply( is_home() && ! is_front_page(), true );
				
			case 'search_page' :
				
				return $operator->apply( is_search(), true );

			case '404_page' :
				
				return $operator->apply( is_404(), true );

			case 'date_page' :

				return $operator->apply( is_date(), true );

			case 'top_level' :

				if ( is_singular() )
				{
					$ancestors = get_post_ancestors( $queried_object->ID, $queried_object->post_type, 'post_type' );

					return $operator->apply( count( $ancestors ) == 0, true );
				}

			case 'parent' :
				
				if ( is_singular() )
				{
					$children = get_children( array
					( 
						'post_parent' => $queried_object->ID,
						'post_type'   => $queried_object->post_type, 
						'numberposts' => 1
					));

					return $operator->apply( count( $children ) > 0, true );
				}

			case 'child' :

				if ( is_singular() )
				{
					$ancestors = get_post_ancestors( $queried_object->ID, $queried_object->post_type, 'post_type' );

					return $operator->apply( count( $ancestors ) > 0, true );
				}
		}

		return false;

	}
}