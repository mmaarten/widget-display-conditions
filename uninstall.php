<?php defined( 'WP_UNINSTALL_PLUGIN' ) or exit; // Exit when accessed directly.

require_once dirname( __FILE__ ) . '/includes/widgets.php';

// Delete options
delete_option( 'wdc_version' );
delete_option( 'wdc_update' );

// Delete widget conditions
wdc_delete_widgets_conditions();
