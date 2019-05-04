<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.
/**
 * Fields
 */

/**
 * Get condition type choices
 *
 * @return array
 */
function wdc_get_condition_type_choices()
{
	$conditions = wdc_get_conditions();
	$categories = wdc_get_condition_categories();

	uasort( $categories, 'wdc_sort_order' );

	// Get choices

	$choices = array();

	foreach ( $categories as $category ) 
	{
		// Get category conditions

		$category_conditions = wp_filter_object_list( $conditions, array( 'category' => $category['id'] ) );

		if ( ! $category_conditions ) continue;

		// Sort conditions

		uasort( $category_conditions, 'wdc_sort_order' );

		//

		$choices[ $category['title'] ] = wp_list_pluck( $category_conditions, 'title', 'id' );
	}

	// Return

	return $choices;
}

/**
 * Get condition operator choices
 *
 * @param string $condition_id
 *
 * @return mixed
 */
function get_condition_operator_choices( $condition_id )
{
	// Get condition

	$condition = wdc_get_condition( $condition_id );

	if ( ! $condition ) return null;

	// Get condition operators

	$operators = wdc_get_operator_objects( $condition->operators );

	// Sort operators

	uasort( $operators, 'wdc_sort_order' );

	//

	$choices = wp_list_pluck( $operators, 'title', 'id' );

	// Return

	return $choices;
}

/**
 * Get condition value choices
 *
 * @param string $condition_id
 *
 * @return mixed
 */
function get_condition_value_choices( $condition_id )
{
	// Get condition

	$condition = wdc_get_condition( $condition_id );

	if ( ! $condition ) return null;

	// Get choices

	$choices = array();
	$choices = apply_filters( "wdc/condition_values/condition={$condition->id}", $choices, $condition );
	$choices = apply_filters( "wdc/condition_values"                           , $choices, $condition );

	// Return

	return $choices;
}

/**
 * Get condition choices
 *
 * @param string $condition_id
 *
 * @return mixed
 */
function wdc_get_condition_choices( $condition_id )
{
	// Get condition

	$condition = wdc_get_condition( $condition_id );

	if ( ! $condition ) return null;

	return array
	(
		'operator' => get_condition_operator_choices( $condition->id ),
		'value'    => get_condition_value_choices( $condition->id ),
	);
}

/**
 * Get condition choices
 *
 * @param string $condition_id
 *
 * @return mixed
 */
function wdc_get_condition_field_items( $condition_id )
{
	$choices = wdc_get_condition_choices( $condition_id );

	if ( isset( $choices ) ) 
	{
		return array_map( 'wdc_get_field_items', $choices );
	}

	return null;
}

/**
 * Get field items
 *
 * @param array $choices
 *
 * @return array
 */
function wdc_get_field_items( $choices )
{
	$items = array();

	foreach ( $choices as $id => $text ) 
	{
		if ( is_array( $text ) ) 
		{
			$items[] = array
			(
				'text'     => $id,
				'children' => wdc_get_field_items( $text ),
			);
		}

		else
		{
			$items[] = array
			(
				'id'   => $id,
				'text' => $text,
			);
		}
	}

	return $items;
}

/**
 * Get dropdown options
 *
 * @param array $choices
 *
 * @return string
 */
function wdc_get_dropdown_options( $choices )
{
	$return = '';

	foreach ( $choices as $value => $text ) 
	{
		if ( is_array( $text ) ) 
		{
			$return .= sprintf( '<optgroup label="%s">', esc_attr( $value ) );
			$return .= wdc_get_dropdown_options( $text );
			$return .= '</optgroup>'; 
		}

		else
		{
			$return .= sprintf( '<option value="%s">%s</option>', esc_attr( $value ), esc_html( $text ) );
		}
	}

	return $return;
}
