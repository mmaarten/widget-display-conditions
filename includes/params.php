<?php 
/**
 * Params
 */

namespace wdc;

$wdc_params           = array();
$wdc_param_categories = array();

/**
 * Add param
 *
 * @param string $id
 * @param string $title
 * @param array  $args
 */
function add_param( $id, $title, $args = array() )
{
	$args = wp_parse_args( $args, array
	(
		'category'  => null,
		'operators' => array(),
		'order'     => 10,
	));

	$param = array
	(
		'id'        => $id,
		'title'     => $title,
		'category'  => $args['category'],
		'operators' => (array) $args['operators'],
		'order'     => (int) $args['order'],
	);

	$param = apply_filters( 'wdc/param', $param );

	$GLOBALS['wdc_params'][ $param['id'] ] = $param;
}

/**
 * Remove param
 *
 * @param string $id
 */
function remove_param( $id )
{
	unset( $GLOBALS['wdc_params'][ $id ] );
}

/**
 * Get params
 *
 * @return array
 */
function get_params()
{
	return $GLOBALS['wdc_params'];
}

/**
 * Get param
 *
 * @param string $id
 *
 * @return mixed
 */
function get_param( $id )
{
	$params = get_params();

	if ( isset( $params[ $id ] ) ) 
	{
		return $params[ $id ];
	}

	return null;
}

/**
 * Add param category
 *
 * @param string $id
 * @param string $title
 * @param array  $args
 */
function add_param_category( $id, $title, $args = array() )
{
	$args = wp_parse_args( $args, array
	(
		'order' => 10,
	));

	$category = array
	(
		'id'        => $id,
		'title'     => $title,
		'order'     => (int) $args['order'],
	);

	$category = apply_filters( 'wdc/category', $category );

	$GLOBALS['wdc_param_categories'][ $category['id'] ] = $category;
}

/**
 * Remove param category
 *
 * @param string $id
 */
function remove_param_category( $id )
{
	unset( $GLOBALS['wdc_param_categories'][ $id ] );
}

/**
 * Get param categories
 *
 * @return array
 */
function get_param_categories()
{
	return $GLOBALS['wdc_param_categories'];
}

/**
 * Get param categories
 *
 * @param string $id
 *
 * @return mixed
 */
function get_param_category( $id )
{
	$categories = get_param_categories();

	if ( isset( $categories[ $id ] ) ) 
	{
		return $categories[ $id ];
	}

	return null;
}
