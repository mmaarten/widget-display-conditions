<?php

namespace wdc;

class Archive_Author_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'archive_author', __( 'Archive Author', 'wdc' ), array
		(
			'category'  => 'archive',
			'operators' => array( '==', '!=' ),
			'order'     => 10,
		));
	}

	public function value_field_items( $items )
	{
		$users = get_users( array
		(
			'who'     => 'authors',
			'orderby' => 'display_name',
			'order'   => 'ASC'
		));

		foreach ( $users as $user ) 
		{
			$items[ $user->ID ] = array
			(
				'id'   => $user->ID,
				'text' => $user->display_name,
			);
		}
		
		return $items;
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, is_author( $value ), true );
	}
}

register_condition( 'wdc\Archive_Author_Condition' );
