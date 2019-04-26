<?php 
/*
Plugin Name:  Widget Display Conditions
Plugin URI:   https://wordpress.org/plugins/widget-display-conditions/
Description:  Control on which page you want a particular widget to be displayed.
Version:      0.2.0
Author:       Maarten Menten
Author URI:   https://profiles.wordpress.org/maartenm/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wdc
Domain Path:  /languages
*/

namespace wdc;

define( 'WDC_FILE', __FILE__ );
define( 'WDC_VERSION', '0.2.0' );
define( 'WDC_NONCE_NAME', 'wdc_nonce' );
defined( 'WDC_MAX_NUMBERPOSTS' ) || define( 'WDC_MAX_NUMBERPOSTS', 5000 );

require_once plugin_dir_path( WDC_FILE ) . 'includes/common.php';

inc( array
(
	'operators/class-wdc-operator.php',
	'operators.php',
	'conditions/class-wdc-condition.php',
	'conditions.php',
	'conditions-api.php',
	'widgets.php',
));

// Operators
inc( array
(
	'operators/class-wdc-is_equal_to-operator.php',
	'operators/class-wdc-is_not_equal_to-operator.php',
));

// Conditions
inc( array
(
	'conditions/class-wdc-post-condition.php',
	'conditions/class-wdc-page-condition.php',
	'conditions/class-wdc-attachment-condition.php',
));

if ( is_admin() )
{
	inc( array
	(
		'fields.php',
		'ui.php',
	));
}
