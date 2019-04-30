<?php 
/**
 * UI
 */

namespace wdc\ui;

use function \wdc\get_dropdown_options;
use function \wdc\get_condition_param_field_items;
use function \wdc\get_widget_conditions;
use function \wdc\set_widget_conditions;
use function \wdc\prepare_field_items_json;
use function \wdc\get_condition_field_items as get_field_items;

/**
 * In widget form
 *
 * @param WP_Widget $widget
 */
function in_widget_form( $widget )
{
	// Output button to open UI
	$button = sprintf( '<button class="button wdc-open-ui" type="button" data-widget="%s" data-noncename="%s" data-nonce="%s">%s</button>',
		esc_attr( $widget->id ), esc_attr( WDC_NONCE_NAME ), esc_attr( wp_create_nonce( 'ui' ) ), esc_html__( 'Display conditions', 'wdc' ) );

	printf( '<p class="wdc-open-ui-wrap">%s<span class="spinner"></span></p>', $button );
}

add_action( 'in_widget_form', __NAMESPACE__ . '\in_widget_form', 999 );

/**
 * Get condition field items
 */
function get_condition_field_items()
{
	if ( ! doing_ajax() ) return;

	$items = get_field_items( $_POST['param'], true );

	wp_send_json( $items );
}

add_action( 'wp_ajax_wdc_ui_get_condition_field_items', __NAMESPACE__ . '\get_condition_field_items' );

/**
 * Preload
 */
function preload()
{
	if ( ! doing_ajax() ) return;

	$conditions = get_widget_conditions( $_POST['widget'] );

	$fields = array();

	if ( isset( $conditions ) ) 
	{
		foreach ( $conditions as $group ) 
		{
			foreach ( $group as $condition ) 
			{
				$param = $condition['param'];

				if ( ! isset( $fields[ $param ] ) ) 
				{
					$fields[ $param ] = get_field_items( $param, true );
				}
			}
		}
	}

	wp_send_json( array
	(
		'conditions' => $conditions,
		'fieldItems' => $fields,
	));
}

add_action( 'wp_ajax_wdc_ui_preload', __NAMESPACE__ . '\preload' );

/**
 * Update
 */
function update()
{
	if ( ! doing_ajax() ) return;

	$conditions = isset( $_POST['conditions'] ) ? $_POST['conditions'] : array();

	$result = set_widget_conditions( $_POST['widget'], $conditions );

	wp_send_json( $result );
}

add_action( 'wp_ajax_wdc_ui_update', __NAMESPACE__ . '\update' );

/**
 * Scripts
 */
function scripts()
{
	if ( 'widgets.php' != $GLOBALS['pagenow'] ) return;

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style( 'dashicons' );

	// Featherlight
	wp_enqueue_script( 'featherlight', plugins_url( "assets/js/featherlight$min.js", WDC_FILE ), array( 'jquery' ), '1.7.13', true );

	// Core
	wp_enqueue_style( 'wdc-ui', plugins_url( "assets/css/ui$min.css", WDC_FILE ), array(), WDC_VERSION );
	wp_enqueue_script( 'wdc-ui', plugins_url( "assets/js/ui$min.js", WDC_FILE ), array( 'jquery', 'wp-util' ), WDC_VERSION, true );

	wp_localize_script( 'wdc-ui', 'wdc', array
	(
		'messages' => array
		(
			'notSaved' => __( 'Confirm unsaved changes.', 'wdc' ),
		),
	));
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\scripts' );

/**
 * Template Scripts
 */
function template_scripts()
{
	if ( 'widgets.php' != $GLOBALS['pagenow'] ) return;

	?>

	<script id="tmpl-wdc-ui" type="text/html">
		
		<div class="wdc-ui">

			<h1><?php _e( 'Widget Display Conditions', 'wdc' ); ?></h1>

			<form method="post">
			
				<?php wp_nonce_field( 'ui', WDC_NONCE_NAME ); ?>

				<input type="hidden" name="action" value="wdc_ui_update">
				<input type="hidden" name="widget" value="{{ data.widget }}">

				<div class="notice notice-info wdc-hide-if-conditions">
					<p><?php esc_html_e( __( 'No conditions set.', 'wdc' ) ); ?></p>
				</div>
				
				<h4 class="wdc-show-if-conditions"><?php _e( 'Show widget if', 'wdc' ); ?></h4>

				<div class="wdc-condition-groups"></div>

				<p>
					<button class="button wdc-add-condition-group" type="button"><?php esc_html_e( 'Add group', 'wdc' ); ?></button>
				</p>

				<p class="submit alignright">
					<span class="spinner"></span>
					<button class="button button-primary" type="submit" data-saved="<?php esc_attr_e( 'Saved', 'wdc' ); ?>"><?php esc_html_e( 'Save', 'wdc' ); ?></button>
				</p>

			</form>

		</div><!-- .wdc-ui -->

	</script>

	<script id="tmpl-wdc-condition-group" type="text/html">
		
		<div class="wdc-condition-group" data-id="{{ data.id }}">

			<table class="wdc-conditions"></table>

			<h4><?php _e( 'or', 'wdc' ); ?></h4>

		</div>
		
	</script>

	<script id="tmpl-wdc-condition" type="text/html">
		
		<tr class="wdc-condition" data-id="{{ data.id }}" data-group="{{ data.group }}">

			<td>
				<select class="wdc-param" name="conditions[{{ data.group }}][{{ data.id }}][param]">
					<?php echo get_dropdown_options( get_condition_param_field_items() ); ?>
				</select>
			</td>

			<td>
				<select class="wdc-operator" name="conditions[{{ data.group }}][{{ data.id }}][operator]"></select>
			</td>

			<td>
				<select class="wdc-value" name="conditions[{{ data.group }}][{{ data.id }}][value]"></select>
			</td>

			<td>
				<button class="button wdc-add-condition" type="button"><?php esc_html_e( 'and', 'wdc' ); ?></button>
			</td>

			<td>
				<button class="button-link dashicons-before dashicons-trash wdc-remove-condition" type="button">
					<span class="screen-reader-text"><?php esc_html_e( 'remove', 'wdc' ); ?></span> 
				</button>
			</td>

		</tr>
		
	</script>

	<?php
}

add_action( 'admin_footer', __NAMESPACE__ . '\template_scripts' );

/**
 * Doing ajax
 *
 * @return bool
 */
function doing_ajax()
{
	return wp_doing_ajax() && check_admin_referer( 'ui', WDC_NONCE_NAME );
}
