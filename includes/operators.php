<?php 
/**
 * Params
 */

namespace wdc;

$wdc_operators = array();

/**
 * Add operator
 *
 * @param string $id
 * @param string $title
 * @param array  $args
 */
function add_operator( $id, $title, $args = array() )
{
	$args = wp_parse_args( $args, array
	(
		'operators' => array(),
		'order'     => 10,
	));

	$operator = array
	(
		'id'        => $id,
		'title'     => $title,
		'operators' => (array) $args['operators'],
		'order'     => (int) $args['order'],
	);

	$operator = apply_filters( 'wdc/operator', $operator );

	$GLOBALS['wdc_operators'][ $operator['id'] ] = $operator;
}

/**
 * Remove operator
 *
 * @param string $id
 */
function remove_operator( $id )
{
	unset( $GLOBALS['wdc_operators'][ $id ] );
}

/**
 * Get operators
 *
 * @return array
 */
function get_operators()
{
	return $GLOBALS['wdc_operators'];
}

/**
 * Get operator
 *
 * @param string $id
 *
 * @return mixed
 */
function get_operator( $id )
{
	$operators = get_operators();

	if ( isset( $operators[ $id ] ) ) 
	{
		return $operators[ $id ];
	}

	return null;
}

/**
 * Get operator Objects
 *
 * @param array $ids
 *
 * @return array
 */
function get_operator_objects( $ids )
{
	return array_intersect_key( get_operators(), array_flip( (array) $ids ) );
}
