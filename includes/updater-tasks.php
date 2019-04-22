<?php 
/**
 * Updater Tasks
 */

namespace wdc;

add_update_task( '0.2.0', '0.2.0', 'wdc\updater_task_0_2_0' );

/**
 * Version 0.2.0
 *
 * Convert widget condition param value to instance id.
 * Convert widget condition operator value to instance id.
 * Convert widget condition to array
 */
function updater_task_0_2_0()
{
	$param_map = array
	(
		'WDC_Condition_PageType'        => 'page_type',
		'WDC_Condition_PageTemplate'    => 'page_template',
		'WDC_Condition_PageParent'      => 'page_parent',
		'WDC_Condition_Page'            => 'page',
		'WDC_Condition_PostType'        => 'post_type',
		'WDC_Condition_PostTag'         => 'post_tag',
		'WDC_Condition_PostTaxonomy'    => 'post_taxonomy',
		'WDC_Condition_PostCategory'    => 'post_category',
		'WDC_Condition_PostStatus'      => 'post_status',
		'WDC_Condition_PostTemplate'    => 'post_template',
		'WDC_Condition_PostFormat'      => 'post_format',
		'WDC_Condition_Post'            => 'post',
		'WDC_Condition_Attachment'      => 'attachment',
		'WDC_Condition_ArchivePostType' => 'archive_post_type',
		'WDC_Condition_ArchiveAuthor'   => 'archive_author',
		'WDC_Condition_ArchiveTaxonomy' => 'archive_taxonomy',
		'WDC_Condition_UserRole'        => 'user_role',
		'WDC_Condition_UserLoggedIn'    => 'user_logged_in',
		'WDC_Condition_User'            => 'user',
	);

	$operator_map = array
	(
		'WDC_Operator_IsEqualTo'              => '==',
		'WDC_Operator_IsNotEqualTo'           => '!=',
		'WDC_Operator_isGreaterThan'          => '>',
		'WDC_Operator_IsGreaterThanOrEqualTo' => '>=',
		'WDC_Operator_IsLessThan'             => '<',
		'WDC_Operator_IsLessThanOrEqualTo'    => '<=',
	);

	$sidebars_widgets = get_option( 'sidebars_widgets' );

	if ( is_array( $sidebars_widgets ) ) 
	{
		if ( isset( $sidebars_widgets['array_version'] ) ) 
		{
			unset( $sidebars_widgets['array_version'] );
		}

		foreach ( $sidebars_widgets as $widgets ) 
		{
			if ( ! is_array( $widgets ) ) continue;

			foreach ( $widgets as $widget_id ) 
			{
				$conditions = get_widget_conditions( $widget_id );

				if ( ! is_array( $conditions ) ) continue;

				$updated = array();

				foreach ( $conditions as $group_id => $group ) 
				{
					foreach ( $group as $condition_id => $condition ) 
					{
						$updated[ $group_id ][ $condition_id ] = array
						(
							'param'    => $param_map[ $condition->param ],
							'operator' => $operator_map[ $condition->operator ],
							'value'    => $condition->value,
						);
					}
				}

				set_widget_conditions( $widget_id, $updated );
			}
		}
	}

	return true;
}
