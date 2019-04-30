<?php 

namespace wdc;

/**
 * Condition
 */
class Condition
{
	public $id        = null;
	public $title     = null;
	public $operators = null;
	public $category  = null;
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
			'category'  => null,
			'order'     => 10,
		));
		
		$this->id        = $id;
		$this->title     = $title;
		$this->operators = (array) $args['operators'];
		$this->category  = $args['category'];
		$this->order     = (int) $args['order'];

		add_filter( "wdc/condition_value_field_items/condition={$this->id}", array( &$this, 'value_field_items' ) );
		add_filter( "wdc/do_condition/param={$this->id}" , array( &$this, 'apply' ), 10, 3 );

		do_action_ref_array( 'wdc/condition', array( &$this ) );
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
