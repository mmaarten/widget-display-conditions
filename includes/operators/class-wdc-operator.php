<?php 
/**
 * Operator
 */

namespace wdc;

class Operator
{
	public $id    = null;
	public $title = null;

	public function __construct( $id, $title )
	{
		$this->id    = $id;
		$this->title = $title;

		do_action( 'wdc/operator', $this );
	}

	public function apply( $a, $b )
	{
		return false;
	}
}
