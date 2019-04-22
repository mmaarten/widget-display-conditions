<?php 
/**
 * Operator
 */

namespace wdc;

class Operator
{
	public $id    = null;
	public $title = null;

	/**
	 * Constructor
	 *
	 * @param string $id
	 * @param string $title
	 */
	public function __construct( $id, $title )
	{
		$this->id    = $id;
		$this->title = $title;

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
