<?php 
/**
 * Operator
 */

namespace wdc;

class Operator
{
	public $id    = null;
	public $title = null;
	public $order = null;
	
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
			'order' => 10,
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args, EXTR_SKIP );

		$this->id        = $id;
		$this->title     = $title;
		$this->order     = (int) $order;

		do_action( 'wdc/operator', $this );
	}

	/**
	 * Apply
	 *
	 * @param mixed $a
	 * @param mixed $b
	 *
	 * @return bool
	 */
	public function apply( $a, $b )
	{
		return false;
	}
}
