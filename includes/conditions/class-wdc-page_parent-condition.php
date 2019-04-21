<?php 

namespace wdc;

class Page_Parent_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'page_parent', __( 'Page Parent', 'wdc' ), array
		(
			'category' => 'page',
		));
	}

	public function get_value_field_items()
	{
		return get_post_field_items( 'page' );
	}

	public function apply( $operator, $value )
	{
		if ( ! is_page() ) 
		{
			return false;
		}

		$ancestors = get_post_ancestors( get_the_ID() );

		return apply_operator( $operator, in_array( $value, $ancestors ), true );
	}
}

register_condition( 'wdc\Page_Parent_Condition' );
