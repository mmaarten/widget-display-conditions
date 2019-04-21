<?php 
/**
 * Condition
 */

namespace wdc;

class Condition
{
	public $id        = null;
	public $title     = null;
	public $category  = null;
	public $operators = array();
	public $order     = null;

	public function __construct( $id, $title, $args = array() )
	{
		$defaults = array
		(
			'category'  => '',
			'operators' => array( '==', '!=' ), // TODO : not here
			'order'     => 10,
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args, EXTR_SKIP );

		$this->id        = $id;
		$this->title     = $title;
		$this->category  = $category;
		$this->operators = (array) $operators;
		$this->order     = (int) $order;

		do_action( 'wdc/condition', $this );
	}

	public function get_operator_objects()
	{
		return array_intersect_key( get_operators(), array_flip( $this->operators ) );
	}

	public function get_operator_field_items()
	{
		$operators = $this->get_operator_objects();

		$items = array();

		foreach ( $operators as $operator ) 
		{
			$items[ $operator->id ] = array
			(
				'id'   => $operator->id,
				'text' => $operator->title,
			);
		}

		return $items; 
	}

	public function get_value_field_items()
	{
		return array();
	}

	public function apply( $operator, $value )
	{
		return false;
	}
}
