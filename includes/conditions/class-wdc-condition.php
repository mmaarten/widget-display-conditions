<?php 
/**
 * Condition
 */

namespace wdc;

class Condition
{
	public $id        = null;
	public $title     = null;
	public $operators = null;
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
		$args = wp_parse_args( $args, array
		(
			'operators' => array(),
			'order'     => 10,
		));
		
		$this->id        = $id;
		$this->title     = $title;
		$this->operators = (array) $args['operators'];
		$this->order     = (int) $args['order'];

		add_filter( "wdc/do_condition/param={$this->id}"        , array( $this, 'apply' ), 10, 3 );
		add_filter( "wdc/operator_field_items/param={$this->id}", array( $this, 'operator_field_items' ) );
		add_filter( "wdc/value_field_items/param={$this->id}"   , array( $this, 'value_field_items' ) );

		do_action_ref_array( 'wdc/condition', array( &$this ) );
	}

	/**
	 * Operator field items
	 *
	 * @param array $items
	 *
	 * @return array
	 */
	public function operator_field_items( $items )
	{
		$operators = get_operator_objects( $this->operators );

		uasort( $operators, 'wdc\sort_order' );
		
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
	 * Value field items
	 *
	 * @param array $items
	 *
	 * @return array
	 */
	public function value_field_items( $items )
	{
		return $items;
	}

	/**
	 * Apply
	 *
	 * @param bool   $return
	 * @param string $operator
	 * @param mixed  $value
	 *
	 * @return bool
	 */
	public function apply( $return, $operator, $value )
	{
		return $return;
	}
}
