<?php 

namespace wdc;

defined( 'WP_UNINSTALL_PLUGIN' ) or exit;

require_once dirname( __FILE__ ) . '/includes/widgets.php';

// Remove options
delete_option( 'wdc_version' );
delete_option( 'wdc_update' );

// Remove widget conditions
delete_widgets_conditions();
