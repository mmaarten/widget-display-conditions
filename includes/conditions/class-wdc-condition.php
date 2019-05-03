<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Condition
 */
class WDC_Condition
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

		add_filter( "wdc/condition_values/condition={$this->id}", array( &$this, 'values' ) );
		add_filter( "wdc/do_condition/condition={$this->id}"    , array( &$this, 'apply' ), 10, 3 );

		do_action_ref_array( 'wdc/condition', array( &$this ) );
	}

	/**
	 * Values
	 *
	 * @param array $choices
	 *
	 * @return array
	 */
	public function values( $choices )
	{
		return $choices;
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
