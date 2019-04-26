<?php

namespace wdc;

class Attachment_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'attachment', __( 'Attachment', 'wdc' ), array
		(
			'operators' => array( '==', '!=' ),
			'order'     => 130,
		));
	}

	public function value_field_items( $items )
	{
		return get_post_field_items( 'attachment' );
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, is_attachment( $value ), true );
	}
}

register_condition( 'wdc\Attachment_Condition' );
