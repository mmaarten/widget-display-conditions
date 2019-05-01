<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.
/**
 * Operator
 */

class WDC_Operator
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
		$args = wp_parse_args( $args, array
		(
			'order' => 10,
		));
		
		$this->id    = $id;
		$this->title = $title;
		$this->order = (int) $args['order'];

		add_filter( "wdc/do_operator/operator={$this->id}", array( &$this, 'apply' ), 10, 3 );

		do_action_ref_array( 'wdc/operator', array( &$this ) );
	}

	/**
	 * Apply
	 *
	 * @param bool  $return
	 * @param mixed $a
	 * @param mixed $b
	 *
	 * @return bool
	 */
	public function apply( $return, $a, $b )
	{
		return $return;
	}
}
