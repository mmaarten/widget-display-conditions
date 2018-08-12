<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Condition
{
	public $id        = null;
	public $title     = null;
	public $category  = null;
	public $operators = array();

	public function __construct( $id, $title, $args = null )
	{
		$defaults = array
		(
			'category'  => 'default',
			'operators' => apply_filters( 'wdc_default_operators', array
			(
				'==', 
				'!='
			))
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args, EXTR_SKIP );

		$this->id        = $id;
		$this->title     = $title;
		$this->category  = $category;
		$this->operators = (array) $operators;
	}

	/**
	 * Get Values
	 *
	 * @return array
	 *
	 * Format
	 * ======
	 *
	 * basic
	 * -----
	 * array
	 * (
	 *   array( 'id' => 'php', 'text' => 'PHP' ),
	 *   array( 'id' => 'python', 'text' => 'Python' )
	 * );
	 *
	 * renders:
	 *
	 * <option value="php">PHP</option>
	 * <option value="python">Python</option>
	 *
	 * grouped
	 * -------
	 * array
	 * (
	 *   array
	 *	 ( 
	 *	   'text'     => 'Programming language', 
	 *     'children' => array
	 *     (
	 *        array( 'id' => 'php', 'text' => 'PHP' ),
	 *        array( 'id' => 'python', 'text' => 'Python' )	
	 *     )
	 * );
	 *
	 * renders:
	 *
	 * <optgroup label="Programming language">
	 *   <option value="php">PHP</option>
	 *   <option value="python">Python</option>
	 * </optgroup>
	 */
	public function get_values()
	{
		return array();
	}

	/**
	 * Apply
	 *
	 * Returns the result of the condition.
	 * 
	 * @param $value mixed The value the user has choosen.
	 * @param $operator WDC_Operator_Base The operator object
	 * @return boolean
	 */
	public function apply( $value, $operator )
	{
		return false;
	}
}