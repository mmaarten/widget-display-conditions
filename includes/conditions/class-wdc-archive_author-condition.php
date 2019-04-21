<?php 

namespace wdc;

class Archive_Author_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'author_archive', __( 'Archive Author', 'wdc' ), array
		(
			'category' => 'archive',
		));
	}

	public function get_value_field_items()
	{
		return get_user_field_items( array
		(
			'who' => 'authors'
		));
	}

	public function apply( $operator, $value )
	{
		return apply_operator( $operator, is_author( $value ), true );
	}
}

register_condition( 'wdc\Archive_Author_Condition' );
