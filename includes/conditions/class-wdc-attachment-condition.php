<?php 

namespace wdc;

class Attachment_Condition extends Condition
{
	public function __construct()
	{
		parent::__construct( 'attachment', __( 'Attachment', 'wdc' ), array
		(
			'category' => 'attachment',
			'order'    => 10,
		));
	}

	public function get_value_field_items()
	{
		return get_post_field_items( 'attachment' );
	}

	public function apply( $operator, $value )
	{
		return apply_operator( $operator, is_attachment( $value ), true );
	}
}

register_condition( 'wdc\Attachment_Condition' );
