<?php

namespace wdc;

class Archive_Author_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'archive_author', __( 'Archive Author', 'wdc' ), array
		(
			'category' => 'archive'
		));
	}

	public function value_field_items( $items )
	{
		return wdc_user_choices( array
		(
			'who' => 'authors'
		));
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, is_author( $value ), true );
	}
}

register_condition( 'wdc\Archive_Author_Condition' );
