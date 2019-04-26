<?php

namespace wdc;

class Page_Parent_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'page_parent', __( 'Page Parent', 'wdc' ), array
		(
			'category' => 'page'
		));
	}

	public function value_field_items( $items )
	{
		return wdc_post_choices( array
		(
			'post_type' => 'page'
		));
	}

	public function apply( $return, $operator, $value )
	{
		if ( ! is_page() ) 
		{
			return false;
		}

		$ancestors = get_post_ancestors( get_the_ID() );

		return do_operator( $operator, in_array( $value, $ancestors ), true );
	}
}

register_condition( 'wdc\Page_Parent_Condition' );
