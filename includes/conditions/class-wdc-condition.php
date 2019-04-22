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

	/**
	 * Constructor
	 *
	 * @param string $id
	 * @param string $title
	 * @param array  $args
	 */
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

	/**
	 * Get operator objects
	 *
	 * @return array
	 */
	public function get_operator_objects()
	{
		return array_intersect_key( get_operators(), array_flip( $this->operators ) );
	}

	/**
	 * Get operator field items
	 *
	 * @return array
	 */
	public function get_operator_field_items()
	{
		$operators = $this->get_operator_objects();

		uasort( $operators, 'wdc\sort_order' );

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

	/**
	 * Get value field items
	 *
	 * @return array
	 */
	public function get_value_field_items()
	{
		return array();
	}

	/**
	 * Apply
	 *
	 * @return bool
	 */
	public function apply( $operator, $value )
	{
		return false;
	}
}
