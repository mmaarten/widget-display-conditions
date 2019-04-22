<?php 

namespace wdc;

class Archive_Author_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'archive_author', __( 'Archive Author', 'wdc' ), array
		(
			'category' => 'archive',
			'order'    => 20,
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
