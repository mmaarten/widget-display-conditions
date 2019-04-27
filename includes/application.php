<?php
/**
 * Application
 */

namespace wdc;

// Operators
require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-is_not_equal_to-operator.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-is_equal_to-operator.php';

// Conditions
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page_type-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_type-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page_template-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page_parent-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-attachment-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_tag-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_taxonomy-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-user-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_format-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-archive_post_type-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_template-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-page-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-archive_author-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_status-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-archive_taxonomy-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-user_logged_in-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-user_role-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-post_category-condition.php';
