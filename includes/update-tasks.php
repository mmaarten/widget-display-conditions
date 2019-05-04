<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.
/**
 * Update Tasks
 */

class WDC_Update_Tasks
{
	public static function init()
	{
		wdc_add_update_task( '0_2_0', '0.2.0', array( __CLASS__, 'task_0_2_0' ) );
		wdc_add_update_task( '0_2_4', '0.2.4', array( __CLASS__, 'task_0_2_4' ) );

		add_filter( 'wdc/db_version', array( __CLASS__, 'db_version' ) );
	}

	public static function db_version( $version )
	{
		if ( false === $version && wdc_has_widgets_conditions() ) 
		{
			return '0.1.0';
		}

		return $version;
	}

	/**
	 * Version 0.2.0
	 *
	 * Convert condition param value to class instance id.
	 * Convert condition operator value to class instance id.
	 * Convert condition object to array
	 */
	public static function task_0_2_0()
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

		if ( ! is_array( $sidebars_widgets ) ) return;

		foreach ( $sidebars_widgets as $widgets ) 
		{
			if ( ! is_array( $widgets ) ) continue;

			foreach ( $widgets as $widget_id ) 
			{
				$conditions = wdc_get_widget_conditions( $widget_id );

				if ( ! isset( $conditions ) ) continue;

				$updated = array();

				foreach ( $conditions as $group_id => $group ) 
				{
					foreach ( $group as $condition_id => $condition ) 
					{
						if ( is_object( $condition ) ) 
						{
							$condition = get_object_vars( $condition );
						}

						$condition['param']    = $param_map[ $condition['param'] ];
						$condition['operator'] = $operator_map[ $condition['operator'] ];

						$updated[ $group_id ][ $condition_id ] = $condition;
					}
				}

				wdc_set_widget_conditions( $widget_id, $updated );
			}
		}
	}

	/**
	 * Version 0.2.4
	 *
	 * Rename condition 'param' to 'type'
	 */
	public static function task_0_2_4()
	{
		$sidebars_widgets = get_option( 'sidebars_widgets' );

		if ( ! is_array( $sidebars_widgets ) ) return;

		foreach ( $sidebars_widgets as $widgets ) 
		{
			if ( ! is_array( $widgets ) ) continue;

			foreach ( $widgets as $widget_id ) 
			{
				$conditions = wdc_get_widget_conditions( $widget_id );

				if ( ! isset( $conditions ) ) continue;

				$updated = array();

				foreach ( $conditions as $group_id => $group ) 
				{
					foreach ( $group as $condition_id => $condition ) 
					{
						$condition['type'] = $condition['param'];
						
						unset( $condition['param'] );

						$updated[ $group_id ][ $condition_id ] = $condition;
					}
				}

				wdc_set_widget_conditions( $widget_id, $updated );
			}
		}
	}
}

WDC_Update_Tasks::init();
