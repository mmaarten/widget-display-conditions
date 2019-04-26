<?php

namespace wdc;

class Attachment_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'attachment', __( 'Attachment', 'wdc' ), array
		(
			'category' => 'attachment'
		));
	}

	public function value_field_items( $items )
	{
		return wdc_post_choices( array
		(
			'post_type' => 'attachment'
		));
	}

	public function apply( $return, $operator, $value )
	{
		return do_operator( $operator, is_attachment( $value ), true );
	}
}

register_condition( 'wdc\Attachment_Condition' );
