<?php 
/*
Plugin Name:  Widget Display Conditions
Plugin URI:   https://wordpress.org/plugins/widget-display-conditions/
Description:  Control on which page you want a particular widget to be displayed.
Version:      0.2.1
Author:       Maarten Menten
Author URI:   https://profiles.wordpress.org/maartenm/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wdc
Domain Path:  /languages
*/

namespace wdc;

define( 'WDC_FILE', __FILE__ );
define( 'WDC_VERSION', '0.2.3' );
define( 'WDC_NONCE_NAME', 'wdc_nonce' );
defined( 'WDC_MAX_NUMBERPOSTS' ) || define( 'WDC_MAX_NUMBERPOSTS', 5000 );

require_once plugin_dir_path( WDC_FILE ) . 'includes/common.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions-api.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/widgets.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/operators/class-wdc-operator.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/operators.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions/class-wdc-condition.php';
require_once plugin_dir_path( WDC_FILE ) . 'includes/conditions.php';

if ( is_admin() )
{
	require_once plugin_dir_path( WDC_FILE ) . 'includes/updater.php';
	require_once plugin_dir_path( WDC_FILE ) . 'includes/ui.php';
}

require_once plugin_dir_path( WDC_FILE ) . 'includes/application.php';
